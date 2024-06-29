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
import SurveyResults from "../views/SurveyResults.vue";
import DefaultLayout from "../components/DefaultLayout.vue";
import AuthLayout from "../components/AuthLayout.vue";

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
                path: "/survey/:slug",
                name: "SurveyAnswerView",
                component: () => import("../views/SurveyAnswerView.vue"),
            },
            {
                path: "/survey/results",
                name: "SurveyResults",
                component: SurveyResults,
            },
        ],
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

router.beforeEach((to, from, next) => {
    if (to.meta.requiresAuth && !store.state.user.token) {
        next({ name: "Login" });
    } else if (store.state.user.token && to.meta.isGuest) {
        next({ name: "Dashboard" });
    } else {
        next();
    }
});

export default router;
