export const customerRoutes = [
    {
        path: '/login',
        name: 'customer-login',
        component: () => import(/* webpackChunkName: "js/customerLogin" */ './Login.vue'),
    },
    {
        path: '/register',
        name: 'customer-register',
        component: () => import(/* webpackChunkName: "js/customerRegister" */ './Register.vue'),
    },
    {
        path: '/password-reset',
        name: 'customer-passwordReset',
        component: () => import(/* webpackChunkName: "js/customerPasswordReset" */ './PasswordReset.vue'),
    },
    {
        path: '/new-password',
        name: 'new-password',
        component: () => import(/* webpackChunkName: "js/newPassword" */ './NewPassword.vue'),
    },
    {
        path: '/dashboard',
        name: 'customer-dashboard',        
        meta: {
            requiresAuth: true,
            layout: 'customer',
        },
        component: () => import(/* webpackChunkName: "js/customerDashboard" */ './Dashboard.vue'),
    },
    {
        path: '/wishlist',
        name: 'customer-wishlist',        
        meta: {
            requiresAuth: true,
            layout: 'customer',
        },
        component: () => import(/* webpackChunkName: "js/customerWishlist" */ './Wishlist.vue'),
    },
    {
        path: '/profile',
        name: 'customer-profile',        
        meta: {
            requiresAuth: true,
            layout: 'customer',
        },
        component: () => import(/* webpackChunkName: "js/customerProfile" */ './Profile.vue'),
    },
    {
        path: '/change-password',
        name: 'customer-change-password',        
        meta: {
            requiresAuth: true,
            layout: 'customer',
        },
        component: () => import(/* webpackChunkName: "js/customerChangePassword" */ './ChangePassword.vue'),
    },
    {
        path: '/addresses',
        name: 'customer-addresses',        
        meta: {
            requiresAuth: true,
            layout: 'customer',
        },
        component: () => import(/* webpackChunkName: "js/customerAddresses" */ './Addresses.vue'),
    },
    {
        path: '/order-detail/:order_number?',
        name: 'order-detail',        
        meta: {
            requiresAuth: true,
            layout: 'customer',
        },
        component: () => import(/* webpackChunkName: "js/orderDetail" */ './OrderDetail.vue'),
    },
];