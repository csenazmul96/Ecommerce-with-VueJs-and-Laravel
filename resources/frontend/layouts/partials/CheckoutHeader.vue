<template>
    <!-- ============================
        START HEADER SECTION
    ============================== -->
    <div>
<!--        <div class="header_top d_flex_center" v-html="headerContents && headerContents.top_notification ? headerContents.top_notification : ''"-->
<!--             :style="{backgroundColor: headerContents && headerContents.top_notification_color ? '#'+headerContents.top_notification_color : '#F0F0F0' }"></div>-->

        <header class="header_area checkout_header">
            <div class="main_header cm_header">
                <!-- <div class="c_l_logo">
                    <router-link :to="'/'"><img :src="headerContents && headerContents.logo ? headerContents.logo : ''" alt="Site logo" class="img-fluid"></router-link>
                    <a href="javascript:void(0)"><img src="/themes/front/media/images/secure_checkout.png" alt="" class="img-fluid"></a>
                </div>
                <div class="checkout_nav">
                    <h2>Secure checkout</h2>
                    <div class="checkout_logo">
                        <router-link :to="'/'"><img :src="headerContents && headerContents.logo ? headerContents.logo : ''" alt="Site logo"></router-link>
                    </div>
                </div>
                <div class="checkout_others">
                    <ul>
                        <!-- <li><router-link to="/faq">Help</router-link></li> -->
                        <!-- <li><img src="/themes/front/media/images/secure_checkout.png" alt="" class="img-fluid"></li>
                    </ul> -->
                <!-- </div>  -->
                <div class="hl_checkout_head">
                    <router-link :to="'/'"><img v-if="headerContents && headerContents.logo" :src="headerContents.logo" alt="Site logo" class="img-fluid"></router-link>
                    <div class="head_text">
                        <i class="lni lni-lock pr_10"></i> Secure Checkout
                    </div>
                </div>
            </div>
        </header>

<!--        <div class="ct_margin"></div>-->
    </div>

    <!-- ============================
        END HEADER SECTION
    ============================== -->
</template>
<script>
    import defaultStore from '../defaultStore'
    import mixin from '../../helpers/mixins'
    export default {
        name:'checkoutHeaderComponent',
        components: {
            /*headerMenuComponent: () => import(/!* webpackChunkName: "js/headerMenuComponent" *!/ './Components/HeaderMenu.vue'),
            headerSearchComponent: () => import(/!* webpackChunkName: "js/headerSearchComponent" *!/ './Components/HeaderSearch.vue'),
            headerCartComponent: () => import(/!* webpackChunkName: "js/headerCartComponent" *!/ './Components/HeaderCart.vue'),
            headerWishlistComponent: () => import(/!* webpackChunkName: "js/headerWishlistComponent" *!/ './Components/HeaderWishlist.vue'),
            headerUserComponent: () => import(/!* webpackChunkName: "js/headerUserComponent" *!/ './Components/HeaderUser.vue'),*/
        },
        //mixins: [mixin],
        created() {
            /*setTimeout(() => {
                let that = this;
                var common_margin = $('.cm_header').outerHeight();
                $('.ct_margin').css({'height' : `${common_margin}px`});

                $(window).on('scroll',function() {
                    var scrollTop = $(window).scrollTop();
                    if(scrollTop > 200) {
                        $('.main_header , .checkout_others li img').css({'height' : '50px'});
                        $('.logo img').attr("src", `/themes/front/media/images/logo-small.png`);
                    } else {
                        $('.main_header , .checkout_others li img').css({'height' : '61px'});
                        $('.logo img ').attr("src", `${that.headerContents && that.headerContents.logo ? that.headerContents.logo : '/themes/front/media/images/logo-hasan.png'}`);
                    }
                });
            }, 100);*/
        },
        beforeCreate() {
            if (!(this.$store && this.$store.state && this.$store.state['defaultStore'])) {
                this.$store.registerModule("defaultStore", defaultStore);
            }
        },
        mounted(){
            //this.collapseSlide();
        },
        computed: {
            cartItems() {
                return this.$store.getters['defaultStore/getCartItems']
            },
            wishlistItems() {
                return this.$store.getters['defaultStore/getWishlistItems']
            },
            headerContents:{
                get: function () {
                    return this.$store.getters['defaultStore/getHeaderContents']
                },
                set: function (contents = null) {
                    this.$store.commit('defaultStore/setHeaderContents', contents);
                }
            },
        },
    }
</script>
