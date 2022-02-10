require('./bootstrap');
import Vue from 'vue'

import Vuex from 'vuex'
Vue.use(Vuex)
Vue.config.productionTip = false

window.axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').getAttribute('content')
};

/* ------------------------ Import vuex store file ----------------------- */
import storeData from "./store/store.js"
const store = new Vuex.Store(storeData);

/* ----------------------------  Moment Support --------------------------- */
import {moment} from './Filter';

// =============================================================================
// Meta Information call
// =============================================================================

import VueMeta from 'vue-meta';
Vue.use(VueMeta, {
  refreshOnceOnNavigation: true
})
// =============================================================================
// Gate Class Import
// =============================================================================
import Gate from "./Gate";
Vue.prototype.$gate = new Gate(localStorage.getItem('UserData'));
/* --------------------------- vue router support --------------------------- */
import VueRouter from 'vue-router'
Vue.use(VueRouter)
import {routes} from './routes';

/* -------------------- public home page master component ------------------- */
Vue.component('pagination', require('laravel-vue-pagination'));

/* ---------------------------- // V-Form IMport ---------------------------- */
import { Form, HasError, AlertError } from 'vform'
window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

/* ---------------------------- Import InputMask ---------------------------- */

const VueInputMask = require('vue-inputmask').default
Vue.use(VueInputMask)



/* -------------------------- // Sweetalert support ------------------------- */
import Swal from 'sweetalert2';
window.swal = Swal;
const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  onOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})
window.toast = Toast;

const router = new VueRouter({
    mode: 'history',
    routes
  })

router.beforeEach((to, from, next) => {
    next()
})

const app = new Vue({
    el: '#app',
    components: {
      mastercomponent: () => import(/* webpackChunkName: "js/masterComponent" */ './components/default/MasterComponent.vue'),
    },
    router,
    store
});
