<template>
    <!-- ============================
        START HERO SECTION
    ============================== -->
    <cartComponent v-if="form && form.route == 'cart'"></cartComponent>
    <checkoutComponent v-else-if="form && form.route == 'checkout'"></checkoutComponent>
    <orderCompleteComponent v-else-if="form && form.route == 'order-complete'"></orderCompleteComponent>
    <productSingleComponent v-else-if="productFound === true"></productSingleComponent>
    <productListComponent v-else-if="categoryFound === true"></productListComponent>
    <div class="footer_hero_area" v-else>
        <h2 style="padding-top: 40px; text-align:center;">
            404 Not Found
        </h2>
        <h4 style="text-align:center;">
            The page you looking for, Is not active.
        </h4>
    </div>
    <!-- try product load -->
    <!-- try category load -->
    <!-- ============================
        END HERO SECTION
    ============================== -->
</template>
<script>

    import pageStore from './pageStore'
    import productListComponent from '../ecommerce/ProductList'
    import productSingleComponent from '../ecommerce/ProductSingle'
    import cartComponent from '../ecommerce/Cart'
    import checkoutComponent from '../ecommerce/Checkout'
    import orderCompleteComponent from '../ecommerce/OrderComplete'

    export default {
        name:'dynamicPage',
        components: {
            productListComponent: productListComponent,
            productSingleComponent: productSingleComponent,
            cartComponent: cartComponent,
            checkoutComponent: checkoutComponent,
            orderCompleteComponent: orderCompleteComponent,
            //productListComponent: () => import(/* webpackChunkName: "js/dynamic/productListComponent" */ '../ecommerce/ProductList.vue'),
            //productSingleComponent: () => import(/* webpackChunkName: "js/dynamic/productSingleComponent" */ '../ecommerce/ProductSingle.vue'),
            //cartComponent: () => import(/* webpackChunkName: "js/dynamic/CartComponent" */ '../ecommerce/Cart.vue'),
            //cartComponent: () => import(/* webpackChunkName: "js/dynamic/CartComponent" */ '../ecommerce/Cart.vue'),
            //checkoutComponent: () => import(/* webpackChunkName: "js/dynamic/checkoutComponent" */ '../ecommerce/Checkout.vue'),
            //orderCompleteComponent: () => import(/* webpackChunkName: "js/dynamic/orderCompleteComponent" */ '../ecommerce/OrderComplete.vue'),
        },
        data(){
            return{
                productSlug: 'null',
                form: {
                    route: '',
                    PerPage: 40,
                    page: 1,
                    sorting: 1,
                    size: null,
                    color: null,
                    search_text:'',
                    order_by:'sorting',
                    sort_by:'asc',
                    value_id: null,
                    brand_id: null,
                    price: {
                        first: null,
                        last: null,
                    },
                },
            }
        },
        beforeCreate() {
            if (!(this.$store && this.$store.state && this.$store.state['pageStore'])) {
                this.$store.registerModule("pageStore", pageStore);
            }
        },
        mounted(){
            this.pagePreloads();
        },
        watch: {
            $route(to) {
                this.pagePreloads();
            }
        },
        computed:{
            productFound: {
                get: function () {
                    return this.$store.getters['pageStore/getProductFound']
                },
                set: function (payload = false) {
                    this.$store.commit('pageStore/setProductFound', payload);
                }
            },
            categoryFound: {
                get: function () {
                    return this.$store.getters['pageStore/getCategoryFound']
                },
                set: function (payload = false) {
                    this.$store.commit('pageStore/setCategoryFound', payload);
                }
            },
        },
        methods:{
            async pagePreloads(){
                this.$Progress.start()
                this.makeRoute()
                let route = this.form.route;
                switch (route) {
                    case 'cart':
                        // $('#preloader').delay(0).fadeIn('fast');
                        break;
                    case 'checkout':
                        // $('#preloader').delay(0).fadeIn('fast');
                        break;
                    case 'order-complete':
                        // $('#preloader').delay(0).fadeIn('fast');
                        break;
                    default:
                        const currentQuery = this.$route.query;
                        this.form = this.$route.query.filters ? JSON.parse(this.$route.query.filters) : {
                            route: '',
                            PerPage: 40,
                            page: 1,
                            sorting: 1,
                            size: null,
                            color: null,
                            search_text:'',
                            order_by:'sorting',
                            sort_by:'asc',
                            value_id: null,
                            brand_id: null,
                            price: {
                                first: null,
                                last: null,
                            },
                        };
                        this.makeRoute()

                        let singleFound = null;
                        let productsFound = null;
                        await this.$store.dispatch('pageStore/singleProduct',  this.productSlug)
                        .then((response) => {
                            singleFound = response;
                        });

                        await this.$store.dispatch('pageStore/products',  this.form)
                        .then((response)=>{
                            productsFound = response;
                        })
                        .catch((error) => {
                        })

                        // if (!singleFound && !productsFound)
                        //     this.$router.push({name: 'home'});

                        // $('#preloader').delay(350).fadeOut('slow');
                        break;
                }
            },
            makeRoute(){
                this.form.search_text = this.$route.query.s
                if(this.$route.params.third){
                    this.form.route = this.$route.params.third
                    this.productSlug = null;
                    return;
                }
                if(this.$route.params.sub){
                    this.form.route = this.$route.params.sub
                    this.productSlug = null;
                    return;
                }
                if(this.$route.params.parent){
                    this.form.route = this.$route.params.parent
                    this.productSlug = this.$route.params.parent;
                    return;
                }
            },
        },
    }
</script>
