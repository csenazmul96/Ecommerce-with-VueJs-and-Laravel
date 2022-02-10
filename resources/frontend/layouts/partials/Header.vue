<template>
    <div>
        <header class="header_area fixed-top" :class="{home_header:layout === 'home'}">
            <div class="main_header cm_header show_desktop">
                <div class="main_header_inner">
                    <div class="logo">
                        <a href="/" v-if="$route.path === '/'">
                            <img v-if="headerContents && headerContents.logo" :src="headerContents.logo" alt="Site logo">
                        </a>
                        <router-link :to="'/'" v-else>
                            <img v-if="headerContents && headerContents.logo" :src="headerContents.logo" alt="Site logo">
                        </router-link>
                    </div>
                    <headerMenuComponent></headerMenuComponent>
                    <div class="header_others">
                        <ul>
                            <li data-toggle="collapse_noslide" data-target="#search">
                                <img class="default" src="/images/search.png" alt="search">
                                <img class="on_scoll" src="/images/search-white.png" alt="search">
                            </li>
                            <headerSearchComponent></headerSearchComponent>
                            <li data-toggle="collapse_noslide" data-target="#user">
                                <img class="default" src="/images/user.png" alt="search">
                                <img class="on_scoll" src="/images/user-white.png" alt="search">
                            </li>
                            <headerUserComponent></headerUserComponent>
                            <li v-if="layout != 'home'" data-toggle="collapse_noslide" data-target="#wishlist"><span><i class="far fa-heart"></i> <b>{{wishlistItems.length}}</b></span></li>
                            <headerWishlistComponent></headerWishlistComponent>
                            <li data-toggle="collapse_noslide" data-target="#cart" class="h_cart">
                                <span>
                                    <img class="default" src="/images/cart.png" alt="search">
                                    <img class="on_scoll" src="/images/cart-white.png" alt="search">
                                    <b>{{cartItems ? cartItems.length : 0}}</b>
                                </span>
                            </li>
                            <headerCartComponent></headerCartComponent>
                        </ul>
                    </div>
                </div>
            </div>

            <mobileHeaderComponent v-if="layout != 'checkout'"></mobileHeaderComponent>
        </header>
        <div  class="ct_margin"></div>
    </div>
</template>

<script>
import defaultStore from '../defaultStore'
import mixin from '../../helpers/mixins'
import mobileHeaderComponent from './MobileHeader';
import headerMenuComponent from './Components/HeaderMenu'
import headerSearchComponent from './Components/HeaderSearch'
import headerCartComponent from './Components/HeaderCart'
import headerWishlistComponent from './Components/HeaderWishlist'
import headerUserComponent from './Components/HeaderUser'
export default {
    name:'headerComponent',
    components: {
        mobileHeaderComponent: mobileHeaderComponent,
        headerMenuComponent: headerMenuComponent,
        headerSearchComponent: headerSearchComponent,
        headerCartComponent: headerCartComponent,
        headerWishlistComponent: headerWishlistComponent,
        headerUserComponent: headerUserComponent
    },
    mixins: [mixin],

    created() {
        setTimeout(() => {
            var common_margin = $('.cm_header').outerHeight();
            if(common_margin === 0)
                common_margin = $('.header_area').outerHeight();
            if(this.layout != 'home')
                $('.ct_margin').css({'height' : `${common_margin}px`});
            else
                $('.ct_margin').css({'height' : `0px`});

        }, 100);
    },
    watch: {
        $route(to,from){
            var common_margin = $('.cm_header').outerHeight();
            if(common_margin === 0)
                common_margin = $('.header_area').outerHeight();
            if(this.layout != 'home')
                $('.ct_margin').css({'height' : `${common_margin}px`});
            else
                $('.ct_margin').css({'height' : `0px`});
        },
    },
    beforeCreate() {
        if (!(this.$store && this.$store.state && this.$store.state['defaultStore'])) {
            this.$store.registerModule("defaultStore", defaultStore);
        }
    },
    mounted(){
        window.addEventListener('resize',  this.resizeWindow);
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
        layout() {
            let routePath = this.$route.path;
            return this.$route.meta.layout || (routePath.replace('/','') == 'checkout' ? 'checkout' : '');
        },
    },
    methods:{
        resizeWindow() {
            var common_margin = $('.cm_header').outerHeight();
            if(common_margin === 0)
                common_margin = $('.header_area').outerHeight();
            if(this.layout != 'home')
                $('.ct_margin').css({'height' : `${common_margin}px`});
            else
                $('.ct_margin').css({'height' : `0px`});
        }
    }

}
</script>
