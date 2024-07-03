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
import ErrorPage from "../views/ErrorPage.vue";
import SurveyResults from "../views/SurveyResults.vue";
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
                path: "/survey/results",
                name: "SurveyResults",
                component: SurveyResults,
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
        component: SurveyAnswerView, // You can use the same component or create a new one if needed
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
