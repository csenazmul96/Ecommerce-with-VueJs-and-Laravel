import { customerRoutes } from './customer/routes';
import { pageRoutes } from './pages/routes';
import { ecommerceRoutes } from './ecommerce/routes';
//import { blog } from './blog/routes';

import Vue from 'vue'
import VueMeta from 'vue-meta';
Vue.use(VueMeta);

const routesArray = [];
routesArray.push(...customerRoutes);
routesArray.push(...pageRoutes);
routesArray.push(...ecommerceRoutes);

export const routes = routesArray;