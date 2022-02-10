<template>
    <div :class="{home_ct_margin:layout === 'home'}">
        <header class="header_area fixed-top" :class="{home_header:layout === 'home'}" >
            <div class="main_header cm_header show_desktop">
                <div class="main_header_inner">
                    <div class="logo">
                        <a href="/" v-if="$route.path === '/'">
                            <img v-if="headerContents && headerContents.logo" :src="headerContents.logo" alt="Site logo" @load="headerCommonMargin">
                        </a>
                        <router-link :to="'/'" v-else>
                            <img v-if="headerContents && headerContents.logo" :src="headerContents.logo" alt="Site logo" @load="headerCommonMargin">
                        </router-link>
                    </div>
                    <headerMenuComponent></headerMenuComponent>
                    <div class="header_others">
                        <ul>
                            <li data-toggle="collapse_noslide" data-target="#search" @click="searchCursorPointer">
                                <img class="default" src="/images/search-home-page.png" alt="search">
                                <img class="on_scoll" src="/images/search-white.png" alt="search">
                            </li>
                            <headerSearchComponent ref="headerComponent"></headerSearchComponent>
                            <li data-toggle="collapse_noslide" data-target="#user">
                                <img class="default" src="/images/user-home-page.png" alt="search">
                                <img class="on_scoll" src="/images/user-white.png" alt="search">
                            </li>
                            <headerUserComponent></headerUserComponent>
                            <headerWishlistComponent></headerWishlistComponent>
                            <li data-toggle="collapse_noslide" data-target="#cart" class="h_cart">
                                <span>
                                    <img class="default" src="/images/cart-home-page.png" alt="search">
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
        this.headerCommonMargin()
    },
    watch: {
        $route(to,from){
            this.$nextTick(() => {
                this.headerCommonMargin()
            })
        },
    },
    async beforeCreate() {
        if (!(this.$store && this.$store.state && this.$store.state['defaultStore'])) {
            this.$store.registerModule("defaultStore", defaultStore);
        }
    },
    mounted(){
        window.addEventListener('resize',  this.headerCommonMargin);
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
        headerCommonMargin() {
            var common_margin = $('.cm_header').outerHeight();
            if(common_margin === 0)
                common_margin = $('.header_area').outerHeight();

            if(this.layout != 'home')
                $('.ct_margin').css({'height' : `${common_margin}px`});
            else
                $('.ct_margin').css({'height' : `0px`});

            $('.p_filter_content').css({
                top : `${common_margin}px`,
                'height': `calc(100% - ${common_margin}px)`
            });

            $('.show_from_left , .show_from_right').css({
                top : `${common_margin}px`,
                'height': `calc(100% - ${common_margin}px)`
            });

            let width = $(".header_area").outerWidth();
            if(width <= 1024){
                $('.home_ct_margin .ct_margin').css({'height' : `${common_margin}px`});

            }else{
                $(".home_ct_margin .ct_margin").css({
                    'height' : '0px'
                });
            }

        },
        searchCursorPointer() {
            // if(this.layout === 'home')
            setTimeout(() => {
                this.$refs.headerComponent.$refs.searchinput.focus();
            }, 50);
        }
    }

}
</script>
