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
                path: 'configuration/router',
                name: 'MikrotikRouter',
                component: () => import('../pages/config/MikrotikRouter.vue'),
            },
            {
                path: 'configuration/zone',
                name: 'Zone',
                component: () => import('../pages/config/Zone.vue'),
            },
            {
                path: 'configuration/sub-zone',
                name: 'SubZone',
                component: () => import('../pages/config/SubZone.vue'),
            },
            {
                path: 'configuration/box',
                name: 'Box',
                component: () => import('../pages/config/Box.vue'),
            },
            {
                path: 'configuration/client-type',
                name: 'ClientType',
                component: () => import('../pages/config/ClientType.vue'),
            },
            {
                path: 'configuration/connection-type',
                name: 'ConnectionType',
                component: () => import('../pages/config/ConnectionType.vue'),
            },
            {
                path: 'olt/list',
                name: 'OltList',
                component: () => import('../pages/config/Olt.vue'),
            },
            {
                path: 'olt-configuration/olt-users',
                name: 'OltUsers',
                component: () => import('../pages/config/OltUsers.vue'),
            },
            {
                path: 'olt-configuration/service-profiles',
                name: 'ServiceProfiles',
                component: () => import('../pages/config/ServiceProfiles.vue'),
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
