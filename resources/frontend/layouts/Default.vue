<template>
    <div>
        <!--        <vue-progress-bar></vue-progress-bar>-->
        <div id="loaderSvgWrapper" :class="{home_page:$route.name === 'home'}" v-if="showLoader.includes($route.name)">
            <svg xmlns:svg="http://www.w3.org/2000/svg" viewbox="0 0 100 100" id="preLoader" width="100px" height="100px">
                <path style="stroke-width:0.26458332px;stroke-linecap:butt;stroke-linejoin:miter" d="m 58.26475,43.628481 15.7247,-27.287018 -31.4936,0.02553 z" id="T1"/>
                <path style="stroke-width:0.26458332px;stroke-linecap:butt;stroke-linejoin:miter" d="m 58.26475,43.628481 31.4936,-0.02553 -15.7689,-27.261492 z" id="T2"/>
                <path style="stroke-width:0.26458332px;stroke-linecap:butt;stroke-linejoin:miter" d="M 58.26475,43.628481 74.03365,70.88997 89.75835,43.602954 Z" id="T3"/>
                <path style="stroke-width:0.26458332px;stroke-linecap:butt;stroke-linejoin:miter" d="M 58.26475,43.628481 42.54006,70.915503 74.03365,70.889973 Z" id="T4"/>
                <path style="stroke-width:0.26458332px;stroke-linecap:butt;stroke-linejoin:miter" d="m 58.26475,43.628481 -31.49359,0.02553 15.7689,27.261491 z" id="T5"/>
                <path style="stroke-width:0.26458332px;stroke-linecap:butt;stroke-linejoin:miter" d="M 58.26475,43.628481 42.49585,16.366995 26.77116,43.654011 Z" id="T6"/>
            </svg>
        </div>
        <checkoutHeaderComponent v-if="layout == 'checkout'"></checkoutHeaderComponent>
        <home-header v-else></home-header>
        <!--        <home-header v-if="layout == 'home'"></home-header>-->
        <!--        <headerComponent v-else-if="layout != 'checkout'"></headerComponent> -->
        <!-- <innerHeader v-if="layout && layout == 'customer'"></innerHeader> -->

        <router-view></router-view>

        <!-- <innerFooter v-if="layout && layout == 'customer'"></innerFooter> -->
        <newsLetter v-if="layout !== 'checkout'"></newsLetter>
        <footerComponent v-if="layout !== 'checkout'"></footerComponent>
    </div>
</template>
<script>
import defaultStore from './defaultStore'
import customerStore from '../customer/customerStore'
import pageStore from '../pages/pageStore'
import ecommerceStore from '../ecommerce/ecommerceStore'
import checkoutHeaderComponent from './partials/CheckoutHeader.vue'
import headerComponent from './partials/Header'
import homeHeader from './partials/HomeHeader'
import footerComponent from './partials/Footer'
import newsLetter from '../sections/Newsletter'
import { mapGetters } from 'vuex'

export default {
    name:'frontendLayout',
    components: {
        checkoutHeaderComponent: checkoutHeaderComponent,
        headerComponent: headerComponent,
        homeHeader: homeHeader,
        footerComponent: footerComponent,
        newsLetter: newsLetter,
    },
    data(){
        return {
            showLoader:['product', 'search', 'single-product', 'parent-category', 'second-category']
        }
    },
    beforeCreate() {
        if (!(this.$store && this.$store.state && this.$store.state['defaultStore'])) {
            this.$store.registerModule("defaultStore", defaultStore);
        }
        if (!(this.$store && this.$store.state && this.$store.state['customerStore'])) {
            this.$store.registerModule("customerStore", customerStore);
        }
        if (!(this.$store && this.$store.state && this.$store.state['pageStore'])) {
            this.$store.registerModule("pageStore", pageStore);
        }
        if (!(this.$store && this.$store.state && this.$store.state['ecommerceStore'])) {
            this.$store.registerModule("ecommerceStore", ecommerceStore);
        }
    },
    watch: {
        $route(to,from){
            this.hideAllSubmenu();
            this.mobCloseMenu();
            $('body').removeClass('overflow_hidden');
            // if(to.name === 'home')
            //     $('#loaderSvgWrapper').addClass('home_page');
            // else
            //     $('#loaderSvgWrapper').removeClass('home_page');

        },
        defaultLoading(loading) {
            if (this.allLoading() === false) {
                this.$Progress.finish()
            }
        },
        pageLoading(loading) {
            if (this.allLoading() === false) {
                this.$Progress.finish()
            }
        },
        ecommerceLoading(loading) {
            if (this.allLoading() === false) {
                this.$Progress.finish()
            }
        },
        blogLoading(loading) {
            if (this.allLoading() === false) {
                this.$Progress.finish()
            }
        },
        customerLoading(loading) {
            if (this.allLoading() === false) {
                this.$Progress.finish()
            }
        },
        loading() {
            this.changeLoadingState();
        },
        allLoadingPreloader(loading) {
            if (loading === true) {
                this.$nextTick(function () {
                    setTimeout(() => {
                        this.$Progress.finish()
                        $('#loaderSvgWrapper').fadeOut(600);
                    }, 600);
                })
            } else {
                $('#loaderSvgWrapper').fadeIn(0);
            }
        },
        customerSuccessMessage(successMessage) {
            if (successMessage) {
                this.$swal({
                    icon: 'success',
                    title: successMessage,
                })
                this.$store.commit('customerStore/setSuccessMessage', '');
            }
        },
        customerErrorMessage(errorMessage) {
            if (errorMessage) {
                this.$swal({
                    icon: 'error',
                    title: errorMessage,
                })
                this.$store.commit('customerStore/setErrorMessage', '');
            }
        },
        defaultSuccessMessage(successMessage) {
            if (successMessage) {
                this.$swal({
                    icon: 'success',
                    title: successMessage,
                })
                this.$store.commit('defaultStore/setSuccessMessage', '');
            }
        },
        defaultErrorMessage(errorMessage) {
            if (errorMessage) {
                this.$swal({
                    icon: 'error',
                    title: errorMessage,
                })
                this.$store.commit('defaultStore/setErrorMessage', '');
            }
        },
    },
    computed: {
        ...mapGetters({
            // map `this.doneCount` to `this.$store.getters.doneTodosCount`
            loading: 'pageStore/getLoading'
        }),
        layout() {
            let routePath = this.$route.path;
            return this.$route.meta.layout || (routePath.replace('/','') == 'checkout' ? 'checkout' : '');
        },
        allLoadingPreloader() {
            return this.allLoadingCounter();
        },
        defaultLoading() {
            return this.$store.getters['defaultStore/getLoading'];
        },
        pageLoading() {
            return this.$store.getters['pageStore/getLoading'];
        },
        ecommerceLoading() {
            return this.$store.getters['ecommerceStore/getLoading'];
        },
        customerLoading() {
            return this.$store.getters['customerStore/getLoading'];
        },
        defaultLoadingCounter() {
            return this.$store.getters['defaultStore/getLoadingCounter'];
        },
        pageLoadingCounter() {
            return this.$store.getters['pageStore/getLoadingCounter'];
        },
        ecommerceLoadingCounter() {
            return this.$store.getters['ecommerceStore/getLoadingCounter'];
        },
        customerLoadingCounter() {
            return this.$store.getters['customerStore/getLoadingCounter'];
        },
        defaultSuccessMessage() {
            return this.$store.getters['defaultStore/getSuccessMessage'];
        },
        defaultErrorMessage() {
            return this.$store.getters['defaultStore/getErrorMessage'];
        },
        customerSuccessMessage() {
            return this.$store.getters['customerStore/getSuccessMessage'];
        },
        customerErrorMessage() {
            return this.$store.getters['customerStore/getErrorMessage'];
        },
    },
    mounted() {
        this.hideAllSubmenu();
        this.preloads();
        this.loadHeader();
        this.changeLoadingState();
    },
    methods: {
        changeLoadingState() {
            if (this.loading) {
                $('#loaderSvgWrapper').fadeIn(0);
            } else {
                $('#loaderSvgWrapper').fadeOut(300);
            }
        },
        allLoading() {
            return (this.defaultLoading === false &&
                this.pageLoading === false &&
                this.ecommerceLoading === false &&
                this.customerLoading === false)
        },
        allLoadingCounter() {
            return (this.defaultLoadingCounter === 0 &&
                this.pageLoadingCounter === 0 &&
                this.ecommerceLoadingCounter === 0 &&
                this.customerLoadingCounter === 0)
        },
        gotoTop(){
            window.scrollTo(0,0);
        },
        hideAllSubmenu(){
            $('.h_o_dropdown').each(function() {
                var dropdown4 = $(this);
                dropdown4.hide();
            });
        },
        mobCloseMenu() {
            $('.show_from_left , .show_from_right').removeClass('open_h_menu');
            $('.menu').removeClass('open');
        },
        preloads() {
            this.$Progress.start()
            this.$store.dispatch('defaultStore/headerContents');
            this.$store.dispatch('defaultStore/cartItems');
            this.$store.dispatch('defaultStore/wishlistItems');
            this.$store.dispatch('defaultStore/defaultCategories');
            this.$store.dispatch('customerStore/checkCustomer');
            // getFbLoginStatus(); //facebook socialite login. not implemented yet.
        },
        loadHeader() {
            let DHeaderHeight = $('.main_header').outerHeight();
            let mHeaderHeight = $('.main_header_mobile').outerHeight();
            let checkoutHeaderHeight = $('.checkout_header .main_header').outerHeight()
            let topHeight = $('.header_top').outerHeight();
            //$('.main_header_inner , .checkout_others li img').css({'height' : '80px'});
            // $('.show_from_left , .show_from_right').css({
            //     'top': `${(mHeaderHeight + topHeight)}px`,
            //     'height': `calc(100% - ${mHeaderHeight + topHeight}px)`,
            // });
            $('.product_filter_mobile .p_filter_content').css({
                'top': `${(mHeaderHeight + topHeight)}px`,
                'height': `calc(100% - ${mHeaderHeight + topHeight}px)`,
            });

            $(window).on('scroll',function() {
                let scrollTop = $(window).scrollTop();
                let topHeight = $('.header_top').outerHeight();
                let mHeaderHeight = $('.main_header_mobile').outerHeight();
                let checkoutHeaderHeight = $('.checkout_header .main_header').outerHeight();
                let DHeaderHeight = $('.main_header').outerHeight();

                if (scrollTop > topHeight) {
                    $(".main_header ").addClass("fixed-top");
                    $(".main_header_mobile").addClass("fixed-top");
                    // $(".checkout_header").addClass("fixed-top");
                    // $('.show_from_left , .show_from_right').css({
                    //     'top': `${mHeaderHeight}px`,
                    //     'height': `calc(100% - ${mHeaderHeight}px)`,
                    // });
                    $('.product_filter_mobile .p_filter_content').css({
                        'top': `${mHeaderHeight}px`,
                        'height': `calc(100% - ${mHeaderHeight}px)`,
                    });
                    // if ($(window).width() <= 1025) {
                    //     $(".ct_margin").css({
                    //         'padding-top' : `${mHeaderHeight}px`
                    //     });
                    //     $(".ct_margin").css({
                    //         'padding-top' : `${checkoutHeaderHeight}px`
                    //     });
                    // }
                    // else {
                    //     $(".ct_margin").css({
                    //         'padding-top' : `${DHeaderHeight}px`
                    //     });
                    //     $(".ct_margin").css({
                    //         'padding-top' : `${checkoutHeaderHeight}px`
                    //     });
                    // }
                } else {
                    $(".main_header").removeClass("fixed-top");
                    $(".main_header_mobile").removeClass("fixed-top");
                    // $(".checkout_header").removeClass("fixed-top");
                    // $('.show_from_left , .show_from_right').css({
                    //     'top': `${(mHeaderHeight + topHeight)}px`,
                    //     'height': `calc(100% - ${mHeaderHeight + topHeight}px)`,
                    // });
                    $('.product_filter_mobile .p_filter_content').css({
                        'top': `${(mHeaderHeight + topHeight)}px`,
                        'height': `calc(100% - ${mHeaderHeight + topHeight}px)`,
                    });
                    // $(".ct_margin").css({
                    //     'padding-top' : '0px'
                    // });
                }
            });
        }
    }
}
</script>
