import Vue from 'vue'
import Vuex from 'vuex'

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

Vue.use(Vuex)
const store =  new Vuex.Store({})

// loader
import inlineLoader from 'vue-loading-overlay'
Vue.use(inlineLoader, {
    // Optional parameters
    loader: 'dots',
    backgroundColor: '#000',
    color: '#fff'
});

import swal from 'sweetalert2'
window.swal = swal
const toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: false,
})
window.toast = toast

import * as common from './modules/acl/helpers/commonHelper'
const currentUser = common.getLocalUser();
if(currentUser) {
    // authenticate check
    window.location = '/dashboard';
}

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#lynktoVueApp',
    components: {
        logincomponent: () => import(/* webpackChunkName: "js/logincomponent" */ './modules/acl/masterComponents/LoginComponent.vue')
    },
    store
});
