/* -------------------------------------------------------------------------- */
/*                                Routing Start                               */
/* -------------------------------------------------------------------------- */
export const routes = [{
    path: '/',
    name: 'home',
    component: () => import(/* webpackChunkName: "js/home" */ './components/Home.vue'),
},

/* --------------------------- Static Page Routing -------------------------- */
{
    path: '/about-us',
    name: 'about-us',
    component: () => import(/* webpackChunkName: "js/staticPage" */ './components/staticpage/static.vue'),
},
{
    path: '/contact-us',
    name: 'contact-us',
    component: () => import(/* webpackChunkName: "js/staticPage" */ './components/staticpage/static.vue'),
},
{
    path: '/terms-conditions',
    name: 'terms-conditions',
    component: () => import(/* webpackChunkName: "js/staticPage" */ './components/staticpage/static.vue'),
},
{
    path: '/privacy-policy',
    name: 'privacy-policy',
    component: () => import(/* webpackChunkName: "js/staticPage" */ './components/staticpage/static.vue'),
},
{
    path: '/shipping-returns',
    name: 'shipping-returns',
    component: () => import(/* webpackChunkName: "js/staticPage" */ './components/staticpage/static.vue'),
},
{
    path: '/faq',
    name: 'faq',
    component: () => import(/* webpackChunkName: "js/staticPage" */ './components/staticpage/static.vue'),
},
{
    path: '/size-chart',
    name: 'size-chart',
    component: () => import(/* webpackChunkName: "js/staticPage" */ './components/staticpage/static.vue'),
},
{
    path: '/search',
    name: 'search',
    component: () => import(/* webpackChunkName: "js/product" */ './components/product/Product.vue'),
},

/* --------------------------- Development Testing Routing -------------------------- */
{
    path: '/dev-testing',
    name: 'dev-testing',
    component: () => import(/* webpackChunkName: "js/developmentTesting" */ './components/development/Testing.vue'),
},
{
    path: '/slider',
    name: 'slider',
    component: () => import(/* webpackChunkName: "js/developmentSlider" */ './components/development/slider.vue'),
},

/* ----------------------------- Cart page route ---------------------------- */
{
    path: '/cart',
    name: 'cart',
    component: () => import(/* webpackChunkName: "js/cart" */ './components/Cart.vue'),
},

/* ------------------------------ Buyer Profile ----------------------------- */
{
    path: '/registration',
    name: 'registration',
    component: () => import(/* webpackChunkName: "js/buyerRegistration" */ './components/buyer/Registration.vue'),
},
{
    path: '/login',
    name: 'login',
    component: () => import(/* webpackChunkName: "js/buyerLogin" */ './components/buyer/Login.vue'),
},
{
    path: '/forgot-password',
    name: 'forgot-password',
    component: () => import(/* webpackChunkName: "js/forgotPassword" */ './components/forgotpassword.vue'),
},
{
    path: '/reset/new',
    name: 'reset-new',
    component: () => import(/* webpackChunkName: "js/newPassword" */ './components/Newpassword.vue'),
},
{
    path: '/buyer/profile',
    name: 'profile',
    component: () => import(/* webpackChunkName: "js/buyerIndex" */ './components/buyer/Index.vue'),
},
{
    path: '/wishlist',
    name: 'wishlist',
    component: () => import(/* webpackChunkName: "js/wishlist" */ './components/buyer/Wishlist.vue'),
},

/* -------------------------- Checkout page router -------------------------- */
{
    path: '/order',
    name: 'order',
    component: () => import(/* webpackChunkName: "js/checkout" */ './components/buyer/Checkout.vue'),
},
{
    path: '/order-complete',
    name: 'order-complete',
    component: () => import(/* webpackChunkName: "js/checkoutComplete" */ './components/buyer/CheckoutComplete.vue'),
},

/* --------------------------- Product Page Route --------------------------- */
{
    path: '/product',
    name: 'product',
    component: () => import(/* webpackChunkName: "js/product" */ './components/product/Product.vue'),
},
{
    path: '/single/:slug',
    name: 'single-product',
    component: () => import(/* webpackChunkName: "js/item" */ './components/product/item/Single.vue'),
},
{
    path: '/404',
    name: '404',
    component: () => import(/* webpackChunkName: "js/page404" */ './components/404.vue'),
},
{
    path: '/:parent',
    name: 'parent-category',
    component: () => import(/* webpackChunkName: "js/product" */ './components/product/Product.vue'),
},
{
    path: '/:parent/:sub',
    name: 'second-category',
    component: () => import(/* webpackChunkName: "js/product" */ './components/product/Product.vue'),
},
{
    path: '/:parent/:sub/:third',
    name: 'third-category',
    component: () => import(/* webpackChunkName: "js/product" */ './components/product/Product.vue'),
},
{
    path: '*',
    name: 'errorpage',
    component: () => import(/* webpackChunkName: "js/page404" */ './components/404.vue'),
},
]