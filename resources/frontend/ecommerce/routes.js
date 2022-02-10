export const ecommerceRoutes = [
    /* ----------------------------- Cart page route ---------------------------- */
    {
        path: '/cart',
        name: 'cart',
        component: () => import(/* webpackChunkName: "js/cart" */ './Cart.vue'),
    },
    {
        path: '/checkout',
        name: 'checkout',
        meta: {
            // requiresAuth: true,
            layout: 'checkout',
        },
        component: () => import(/* webpackChunkName: "js/checkout" */ './Checkout.vue'),
    },
    /* -------------------------- Checkout page router -------------------------- */
    // {
    //     path: '/order',
    //     name: 'order',
    //     component: () => import(/* webpackChunkName: "js/checkout" */ './components/buyer/Checkout.vue'),
    // },
    {
        path: '/order-complete',
        name: 'order-complete',
        component: () => import(/* webpackChunkName: "js/orderComplete" */ './OrderComplete.vue'),
    },

    /* ----------------------------- Ecommerce route ---------------------------- */

    {
        path: '/search',
        name: 'search',
        component: () => import(/* webpackChunkName: "js/productList" */ './ProductList.vue'),
    },
    {
        path: '/details/:parent',
        name: 'single-product',
        component: () => import(/* webpackChunkName: "js/item" */ './ProductSingle.vue'),
    },
    {
        path: '/:parent',
        name: 'parent-category',
        component: () => import(/* webpackChunkName: "js/productList" */ './ProductList.vue'),
    },
    {
        path: '/:parent/:sub',
        name: 'second-category',
        component: () => import(/* webpackChunkName: "js/productList" */ './ProductList.vue'),
    },
    {
        path: '/:parent/:sub/:third',
        name: 'third-category',
        component: () => import(/* webpackChunkName: "js/productList" */ './ProductList.vue'),
    },
];
