import { createRouter, createWebHistory } from "vue-router";
import store from "../store";
import Login from "../views/Login.vue";
import Register from "../views/Register.vue";
import Dashboard from "../views/Dashboard.vue";
import ManageAccount from "../views/ManageAccount.vue";
import Surveys from "../views/Surveys.vue";
import SurveyView from "../views/SurveyView.vue";
import UserManagement from "../views/UserManagement.vue";
import SurveyAnswerView from "../views/SurveyAnswerView.vue";
import RespondentSurveys from "../views/RespondentSurveys.vue";
import RespondentResponses from "../views/RespondentResponses.vue";
import ErrorPage from "../views/ErrorPage.vue";
import PublicSurveys from "../views/PublicSurveys.vue";
import AdminResults from "../views/AdminResults.vue";
import SurveyResults from "../views/SurveyResults.vue";
import SurveyResponse from "../views/SurveyResponse.vue";
import SurveyQuestion from "../views/SurveyQuestion.vue";
import ResultsTally from "../views/ResultsTally.vue";
import ResultsPCA from "../views/ResultsPCA.vue";
import ResultsDescriptive from "../views/ResultsDescriptive.vue";
import ResultsVisual from "../views/ResultsVisual.vue";
import ResultsVisualQuestions from "../views/ResultsVisualQuestions.vue";
import DefaultLayout from "../components/DefaultLayout.vue";
import AuthLayout from "../components/AuthLayout.vue";
import { useStore } from "vuex";

const routes = [
    {
        path: "/",
        redirect: "/dashboard",
        component: DefaultLayout,
        meta: { requiresAuth: true },
        children: [
            { path: "/dashboard", name: "Dashboard", component: Dashboard },
            { path: "/surveys", name: "Surveys", component: Surveys },
            {
                path: "/surveys/create",
                name: "SurveyCreate",
                component: SurveyView,
            },
            { path: "/surveys/:id", name: "SurveyView", component: SurveyView },
            {
                path: "/manage-account",
                name: "ManageAccount",
                component: ManageAccount,
            },
            {
                path: "/user-management",
                name: "UserManagement",
                component: UserManagement,
            },
            {
                path: "admin/results",
                name: "AdminResults",
                component: AdminResults,
            },

            {
                path: "/survey/:id/results",
                name: "SurveyResults",
                component: SurveyResults,
            },
            {
                path: "/survey/:id/results/tally",
                name: "ResultsTally",
                component: ResultsTally,
            },
            {
                path: "/survey/:id/results/PCA",
                name: "ResultsPCA",
                component: ResultsPCA,
            },
            {
                path: "/survey/:id/results/descriptive",
                name: "ResultsDescriptive",
                component: ResultsDescriptive,
            },
            {
                path: "/survey/:id/results/visualization",
                name: "ResultsVisual",
                component: ResultsVisual,
            },
            {
                path: "/survey/:id/results/visualization/questions",
                name: "ResultsVisualQuestions",
                component: ResultsVisualQuestions,
            },
            {
                path: "/survey/question/:id",
                name: "SurveyQuestion",
                component: SurveyQuestion,
            },
            {
                path: "/survey/responses/:id",
                name: "SurveyResponse",
                component: SurveyResponse,
            },
            {
                path: "/my-survey",
                name: "RespondentSurveys",
                component: RespondentSurveys,
            },
            {
                path: "/my-responses",
                name: "RespondentResponses",
                component: RespondentResponses,
            },
        ],
    },
    {
        path: "/survey/:slug",
        name: "SurveyAnswerView",
        component: SurveyAnswerView,
        meta: { requiresSurveyAuth: true },
    },
    {
        path: "/public/survey/:slug",
        name: "PublicSurveyAnswerView",
        component: SurveyAnswerView,
    },
    {
        path: "/public-surveys",
        name: "PublicSurveys",
        component: PublicSurveys,
    },

    {
        path: "/error",
        name: "ErrorPage",
        component: ErrorPage,
    },
    {
        path: "/auth",
        redirect: "/login",
        name: "Auth",
        component: AuthLayout,
        meta: { isGuest: true },
        children: [
            { path: "/login", name: "Login", component: Login },
            { path: "/register", name: "Register", component: Register },
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to, from, next) => {
    if (to.meta.requiresAuth && !store.state.user.token) {
        next({ name: "Login" });
    } else if (store.state.user.token && to.meta.isGuest) {
        next({ name: "Dashboard" });
    } else if (to.meta.requiresSurveyAuth) {
        const slug = to.params.slug;
        try {
            const response = await store.dispatch("fetchSurveyBySlug", slug);
            // console.log(response);
            const { data, status } = response;

            if (status === 200 || data.is_public) {
                next();
            } else if (status === 401) {
                next({ name: "Login" });
            } else if (status === 403) {
                next({ name: "Dashboard" });
            } else {
                next({ name: "ErrorPage" });
            }
        } catch (error) {
            next({ name: "ErrorPage" });
        }
    } else {
        next();
    }
});

export default router;
