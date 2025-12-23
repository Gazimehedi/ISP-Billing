import { createRouter, createWebHistory } from 'vue-router';
import ForgotPassword from '../pages/auth/ForgotPassword.vue';
import Register from '../pages/auth/Register.vue';
import Login from '../pages/auth/Login.vue';
import DashboardLayout from '../components/DashboardLayout.vue';
import Dashboard from '../pages/Dashboard.vue';

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
        component: DashboardLayout,
        children: [
            {
                path: 'dashboard',
                name: 'Dashboard',
                component: Dashboard,
            },
            {
                path: '',
                redirect: '/dashboard'
            }
        ]
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const isAuth = localStorage.getItem('is_auth') === 'true';
    const publicPages = ['/login', '/forgot-password', '/register'];
    const authRequired = !publicPages.includes(to.path);

    if (authRequired && !isAuth) {
        return next('/login');
    }

    if (isAuth && publicPages.includes(to.path)) {
        return next('/dashboard');
    }

    next();
});

export default router;
