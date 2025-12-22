import { createRouter, createWebHistory } from 'vue-router';
import ForgotPassword from '../components/ForgotPassword.vue';
import Register from '../components/Register.vue';
import Login from '../components/Login.vue';

const routes = [
    {
        path: '/login',
        name: 'Login',
        component: Login,
    },
    {
        path: '/forgot-password',
        name: 'ForgotPassword',
        component: ForgotPassword,
    },
    {
        path: '/register',
        name: 'Register',
        component: Register,
    },
    {
        path: '/',
        redirect: '/login',
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
