require('./bootstrap');
import Vue from 'vue'
import { initialize } from './customer/helpers/authHelper'
import VueProgressBar from 'vue-progressbar/src/index.js'
/**
 * vue router configuration
 */
import VueRouter from 'vue-router'
Vue.use(VueRouter)

// Validation component
import ValidationErrors from "./helperComponents/ValidationErrors"
Vue.component("v-errors", ValidationErrors);

import VueLazyload from 'vue-lazyload'

Vue.use(VueLazyload, {
    loading: '/images/image-load.jpg',
    attempt: 1,
    silent: true
})

import "./filter.js"

import { routes } from './routes'

const router = new VueRouter({
    routes,
    mode: 'history',
    scrollBehavior (to, from, savedPosition) {
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                if (savedPosition) {
                    resolve(savedPosition)
                } else {
                    resolve({ x: 0, y: 0 })
                }
            }, 1000)
        })
    }
});
initialize(router);

import Vuex from 'vuex'
Vue.use(Vuex)
const store = new Vuex.Store({})


// initialize(router);

//vue progressbar
const options = {
    color: '#1eb885',
    failedColor: '#874b4b',
    thickness: '5px',
    transition: {
        speed: '0.2s',
        opacity: '0.6s',
        termination: 300
    },
    autoRevert: true,
    location: 'top',
    inverse: false
}

Vue.use(VueProgressBar, options)
    // loader
import inlineLoader from 'vue-loading-overlay'
Vue.use(inlineLoader, {
    // Optional parameters
    loader: 'dots',
    backgroundColor: '#000',
    color: '#fff'
});

import VueSweetalert2 from 'vue-sweetalert2';
const swalOptions = {
    toast: true,
    position: 'top-end',
    timer: 2000,
    timerProgressBar: true,
    showConfirmButton: false,
    customClass: {
        container: 'sw_custom_container',
        icon: 'sw_custom_icon',
        loader: 'sw_custom_loader',
        title: 'sw_custom_title',
    }
};

Vue.use(VueSweetalert2, swalOptions);


// =============================================================================
// Meta Information call
// =============================================================================

import VueMeta from 'vue-meta';
Vue.use(VueMeta, {
    refreshOnceOnNavigation: true
})

import LoadScript from 'vue-plugin-load-script';
Vue.use(LoadScript);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import frontendlayout from './layouts/Default'

const app = new Vue({
    el: '#lynktoVueApp',
    components: {
        //frontendlayout: () => import ( /* webpackChunkName: "js/frontendlayout" */ './layouts/Default.vue'),
        frontendlayout: frontendlayout
    },
    router,
    store
});
