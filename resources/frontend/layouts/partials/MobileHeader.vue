<template>
  <div>
      <div class="main_header_mobile  cm_header show_mobile">
          <div class="main_header_mobile_wrap">
              <div class="main_header_mobile_inner">
                  <div class="h_m_left">
                      <ul>
                          <li data-toggle="collapse_l_r" data-target="#menu">
                              <div class="menu btn1">
                                  <div class="icon-left"></div>
                                  <div class="icon-right"></div>
                              </div>
                          </li>
                          <li data-toggle="collapse_l_r" data-target="#msearch" @click="searchCursorPointer">
                              <div class="h_m_search">
                                  <span><i class="lni lni-search-alt"></i></span>
                              </div>
                          </li>
                      </ul>
                  </div>
                  <div class="h_m_logo">
                      <a href="/" v-if="$route.path === '/'">
                          <img v-if="headerContents && headerContents.logo" :src="headerContents.logo" alt="Site logo">
                      </a>
                      <router-link :to="'/'" v-else>
                          <img v-if="headerContents && headerContents.logo" :src="headerContents.logo" alt="Site logo">
                      </router-link>

                  </div>
                  <div class="h_m_cart">
                      <ul>
                          <li data-toggle="collapse_l_r" data-target="#muser"><span><img src="/images/user-white.png" alt="user"></span></li>
                          <li data-toggle="collapse_l_r" data-target="#mcart"><span><img src="/images/cart-white.png" alt="cart"> <b>{{cartItems ? cartItems.length : 0}}</b></span></li>
                      </ul>
                  </div>
              </div>
          </div>
      </div>
        <mobileHeaderMenuComponent></mobileHeaderMenuComponent>
        <mobileHeaderSearchComponent ref="headerComponent"></mobileHeaderSearchComponent>
        <mobileHeaderUserComponent></mobileHeaderUserComponent>
        <mobileHeaderCartComponent></mobileHeaderCartComponent>
<!--      <div  class="ct_margin"></div>-->
  </div>
</template>
<script>
    import defaultStore from '../defaultStore'
    import mobileHeaderMenuComponent from './MobileComponents/MobileHeaderMenu'
    import mobileHeaderSearchComponent from './MobileComponents/MobileHeaderSearch'
    import mobileHeaderUserComponent from './MobileComponents/MobileHeaderUser'
    import mobileHeaderCartComponent from './MobileComponents/MobileHeaderCart'

    export default {
        name:'headerComponent',
        components: {
            mobileHeaderMenuComponent: mobileHeaderMenuComponent,
            mobileHeaderSearchComponent: mobileHeaderSearchComponent,
            mobileHeaderUserComponent: mobileHeaderUserComponent,
            mobileHeaderCartComponent: mobileHeaderCartComponent
        },
        beforeCreate() {
            if (!(this.$store && this.$store.state && this.$store.state['defaultStore'])) {
                this.$store.registerModule("defaultStore", defaultStore);
            }
        },
        data() {
            return {
                menuExpand: false,
            }
        },
        computed: {
            headerContents:{
                get: function () {
                    return this.$store.getters['defaultStore/getHeaderContents']
                },
                set: function (contents = null) {
                    this.$store.commit('defaultStore/setHeaderContents', contents);
                }
            },
            cartItems() {
                return this.$store.getters['defaultStore/getCartItems']
            },
        },
        methods: {
            validateRegister() {
                return true;
            },
            searchCursorPointer() {
                setTimeout(() => {
                    this.$refs.headerComponent.$refs.searchinput.focus();
                }, 50);
            },

        },
    }
</script>
