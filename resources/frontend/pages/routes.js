import HomePage from './HomePage'
import DynamicPage from './DynamicPage'

export const pageRoutes = [{
        path: '/',
        name: 'home',
        meta: {
            layout: 'home',
        },
        //component: () => import ( /* webpackChunkName: "js/HomePage" */ './HomePage.vue'),
        component: HomePage
    },
    /* --------------------------- Static Page Routing -------------------------- */
    {
        path: '/about-us',
        name: 'about-us',
        component: () =>
            import ( /* webpackChunkName: "js/staticPage" */ './StaticPage.vue'),
    },
    {
        path: '/contact-us',
        name: 'contact-us',
        component: () =>
            import ( /* webpackChunkName: "js/staticPage" */ './Cantact.vue'),
    },
    {
        path: '/terms-conditions',
        name: 'terms-conditions',
        component: () =>
            import ( /* webpackChunkName: "js/staticPage" */ './StaticPage.vue'),
    },
    {
        path: '/privacy-policy',
        name: 'privacy-policy',
        component: () =>
            import ( /* webpackChunkName: "js/staticPage" */ './StaticPage.vue'),
    },
    {
        path: '/shipping-returns',
        name: 'shipping-returns',
        component: () =>
            import ( /* webpackChunkName: "js/staticPage" */ './StaticPage.vue'),
    },
    {
        path: '/faq',
        name: 'faq',
        component: () =>
            import ( /* webpackChunkName: "js/staticPage" */ './Faq.vue'),
    },
    {
        path: '/size-chart',
        name: 'size-chart',
        component: () =>
            import ( /* webpackChunkName: "js/staticPage" */ './StaticPage.vue'),
    },
    {
        path: '/blog',
        name: 'blogs',
        component: () =>
            import ( /* webpackChunkName: "js/staticPage" */ '../blog/Blog.vue'),
    },
    {
        path: '/blogs/search',
        name: 'blogs_search',
        component: () =>
            import ( /* webpackChunkName: "js/staticPage" */ '../blog/Blog.vue'),
    },
    {
        path: '/blogs/:slug',
        name: 'blogs-category',
        component: () =>
            import ( /* webpackChunkName: "js/staticPage" */ '../blog/Blog.vue'),
    },
    {
        path: '/blog-details/:slug',
        name: 'blog-single',
        component: () =>
            import ( /* webpackChunkName: "js/staticPage" */ '../blog/BlogSingle.vue'),
    },
    {
        path: '/page404',
        component: () =>
            import ( /* webpackChunkName: "js/pageNotFound" */ './PageNotFound.vue'),
    },
    {
        path: '/product/:parent',
        name: 'product',
        component: () =>
            import ( /* webpackChunkName: "js/item" */ '../ecommerce/ProductSingle.vue'),
    },
//     {
//         path: '/:parent',
//         //component: () => import ( /* webpackChunkName: "js/dynamicPage" */ './DynamicPage.vue'),
//         component: DynamicPage
//     },
//     {
//         path: '/:parent/:sub',
//         //component: () => import ( /* webpackChunkName: "js/dynamicPage" */ './DynamicPage.vue'),
//         component: DynamicPage
//     },
//     {
//         path: '/:parent/:sub/:third',
//         //component: () => import ( /* webpackChunkName: "js/dynamicPage" */ './DynamicPage.vue'),
//         component: DynamicPage
//     },
    { path: '*', redirect: '/page404' },
];
