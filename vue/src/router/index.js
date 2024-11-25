import { createRouter, createWebHistory } from "vue-router";
import store from "../store";
import Login from "../views/Login.vue";
import Register from "../views/Register.vue";
import ForgotPassword from "../views/ForgotPassword.vue";
import Dashboard from "../views/Dashboard.vue";
import ManageAccount from "../views/ManageAccount.vue";
import Surveys from "../views/Surveys.vue";
import Templates from "../views/Templates.vue";
import SurveyView from "../views/SurveyView.vue";
import TemplateView from "../views/TemplateView.vue";
import UserManagement from "../views/UserManagement.vue";
import SurveyAnswerView from "../views/SurveyAnswerView.vue";
import RespondentSurveys from "../views/RespondentSurveys.vue";
import RespondentResponses from "../views/RespondentResponses.vue";
import ErrorPage from "../views/ErrorPage.vue";
import PublicSurveys from "../views/PublicSurveys.vue";
import AdminResults from "../views/AdminResults.vue";
import SurveyReport from "../views/SurveyReport.vue";
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
            { path: "/surveys", name: "Surveys", component: Surveys ,meta: { requiresRole: ['surveymaker'] } },
            { path: "/templates", name: "Templates", component: Templates, meta: { requiresRole: ['surveymaker'] }  },
            {
                path: "/surveys/create",
                name: "SurveyCreate",
                component: SurveyView,
            },
            { path: "/surveys/:id", name: "SurveyView", component: SurveyView },
            {
                path: "/template/:id",
                name: "TemplateView",
                component: TemplateView,
                meta: { requiresRole: ['surveymaker'] } 
            },
            {
                path: "/manage-account",
                name: "ManageAccount",
                component: ManageAccount,
            },
            {
                path: "admin/user-management",
                name: "UserManagement",
                component: UserManagement,
                meta: { requiresRole: ['admin'] } 
            },
            {
                path: "admin/results",
                name: "AdminResults",
                component: AdminResults,
                meta: { requiresRole: ['admin','surveymaker'] } 
            },
            {
                path: "/survey/report/:surveyId",
                name: "SurveyReport",
                component: SurveyReport,
                meta: { requiresRole: ['admin','surveymaker'] } 
            },
            {
                path: "/survey/:id/results",
                name: "SurveyResults",
                component: SurveyResults,
                meta: { requiresRole: ['admin','surveymaker'] } 
            },
            {
                path: "/survey/:id/results/tally",
                name: "ResultsTally",
                component: ResultsTally,
                meta: { requiresRole: ['admin','surveymaker'] } 
            },
            {
                path: "/survey/:id/results/PCA",
                name: "ResultsPCA",
                component: ResultsPCA,
                meta: { requiresRole: ['admin','surveymaker'] } 
            },
            {
                path: "/survey/:id/results/descriptive",
                name: "ResultsDescriptive",
                component: ResultsDescriptive,
                meta: { requiresRole: ['admin','surveymaker'] } 
            },
            {
                path: "/survey/:id/results/visualization",
                name: "ResultsVisual",
                component: ResultsVisual,
                meta: { requiresRole: ['admin','surveymaker'] } 
            },
            {
                path: "/survey/:id/results/visualization/questions",
                name: "ResultsVisualQuestions",
                component: ResultsVisualQuestions,
                meta: { requiresRole: ['admin','surveymaker'] } 
            },
            {
                path: "/survey/question/:id",
                name: "SurveyQuestion",
                component: SurveyQuestion,
                meta: { requiresRole: ['admin','surveymaker'] } 
            },
            {
                path: "/survey/responses/:id",
                name: "SurveyResponse",
                component: SurveyResponse,
                meta: { requiresRole: ['admin','surveymaker'] } 
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
            { path: "/forgot-password", name: "ForgotPassword", component: ForgotPassword },
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to, from, next) => {
    const role = store.state.user.data.role;
    console.log(role);
    
    // Check if the route has role-based access control
    // Check if the route requires role-based access control
    if (to.meta.requiresRole) {
        const requiredRoles = to.meta.requiresRole;

        // If `requiredRoles` is an array, check if the user's role is included
        if (Array.isArray(requiredRoles)) {
            if (!requiredRoles.includes(role)) {
                return next({ name: "ErrorPage" });  // Redirect to error page if role is not in allowed roles
            }
        } else {
            // Fallback for single role (not an array)
            if (role !== requiredRoles) {
                return next({ name: "ErrorPage" });  // Redirect if the role does not match
            }
        }
    }
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
