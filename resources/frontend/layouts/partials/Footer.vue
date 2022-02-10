<template>
    <!-- ============================
        START FOOTER SECTION
    ============================== -->
    <footer :class="['footer_area', {'category_page_footer': isCategoryPage}]">
        <!-- <span class="footer_wave"></span>
        <span class="footer_wave_center"></span>
        <span class="footer_wave_left"></span> -->
        <div class="footer_wave">
            <img src="/themes/front/media/images/wave-footer4.png" alt="" class="above_mobile">
            <!-- <img src="/themes/front/media/images/wave-footer5.png" alt="" class="mobile"> -->
        </div>
        <div class="footer_inner">
            <div class="container footer_container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="footer_top_inner">
                            <img src="/images/hl-c-left.png" alt="" class="img-fluid">
                            <p>All major cards are accepted | Fast checkout with Paypal</p>
                        </div>
                   </div>
                    <div class="col-md-6">
                        <div class="footer_top_inner right">
                            <img src="/images/hl-c-right.png" alt="" class="img-fluid">
                            <p>100% Satisfaction Guaranteed | We provide easy returns</p>
                        </div>
                    </div>
                </div>
                <div class="row above_mobile">
                    <div class="col-md-4">
                        <div class="footer_inner_col">
                            <h2>ACCOUNT</h2>
                            <ul>
                                <li><router-link to="/dashboard">My Account</router-link></li>
                                <li><router-link to="/wishlist">Wishlist</router-link></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="footer_inner_col">
                            <h2>COMPANY</h2>
                            <ul>

                                <li><router-link to="/shipping-returns">Shipping & Returns</router-link></li>
                                <li><router-link to="/terms-conditions">TERMS & CONDITIONS</router-link></li>
                                <li><router-link to="/privacy-policy">Privacy Policy</router-link></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="footer_inner_col">
                            <h2>Contact</h2>
                            <ul>
                                <li><router-link to="/contact-us">Contact us</router-link></li>
                                <li><router-link to="/faq">Faq</router-link></li>
                                <li><router-link to="/about-us">About us</router-link></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row below_mobile">
                    <div class="col-12">
                        <div class="footer_mobile_menu">
                            <ul>
                                <li data-toggle="collapse_noslide" data-target="#ACCOUNT">Account</li>
                                <div class="footer_mobile_menu_inner" id="ACCOUNT">
                                    <ul>
                                        <li><router-link to="/shipping-returns">Shipping & Returns</router-link></li>
                                        <li><router-link to="/terms-conditions">TERMS & CONDITIONS</router-link></li>
                                        <li><router-link to="/privacy-policy">Privacy Policy</router-link></li>
                                    </ul>
                                </div>
                                <li data-toggle="collapse_noslide" data-target="#COMPANY">Company</li>
                                <div class="footer_mobile_menu_inner" id="COMPANY">
                                    <ul>
                                        <li><router-link to="/shipping-returns">Shipping & Returns</router-link></li>
                                        <li><router-link to="/terms-conditions">TERMS & CONDITIONS</router-link></li>
                                        <li><router-link to="/privacy-policy">Privacy Policy</router-link></li>
                                    </ul>
                                </div>
                                <li data-toggle="collapse_noslide" data-target="#Contact">Contact</li>
                                <div class="footer_mobile_menu_inner" id="Contact">
                                    <ul>
                                        <li><router-link to="/contact-us">Contact us</router-link></li>
                                        <li><router-link to="/faq">Faq</router-link></li>
                                        <li><router-link to="/about-us">About us</router-link></li>
                                    </ul>
                                </div>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="footer_bottom">
            <p>#shophologram</p>
            <ul>
                <li><a :href="socialLinks && socialLinks.facebook ? socialLinks.facebook : 'javascript:voice(0)'" target="_blank" rel="noreferrer" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                <li><a :href="socialLinks && socialLinks.instagram ? socialLinks.instagram : 'javascript:voice(0)'" target="_blank" rel="noreferrer" aria-label="Instagram"><i class="fab fa-instagram"></i></a></li>
                <li><a :href="socialLinks && socialLinks.pinterest ? socialLinks.pinterest : 'javascript:voice(0)'" target="_blank" rel="noreferrer" aria-label="Pinterest"><i class="fab fa-pinterest-p"></i></a></li>
            </ul>
            <router-link :to="'/'"><img :src="headerContents && headerContents.logo ? headerContents.logo : ''" alt="Site logo"></router-link>
            <span>©2021 HOLOGRAM NAILS INC All Rights Reserved.</span>
        </div>
    </footer>
    <!-- ============================
        END FOOTER SECTION
    ============================== -->
</template>
<script>
    import defaultStore from '../defaultStore'
    export default {
        name:'footerComponent',
        beforeCreate() {
            if (!(this.$store && this.$store.state && this.$store.state['defaultStore'])) {
                this.$store.registerModule("defaultStore", defaultStore);
            }
        },
        mounted() {
            this.footerPreloads();
        },
        computed: {
            isCategoryPage() {
                return this.$route.params.parent && this.$route.name != 'single-product' ? true : false;
                //return this.$route.meta.layout || (routePath.replace('/','') == 'checkout' ? 'checkout' : '');
            },
            headerContents:{
                get: function () {
                    return this.$store.getters['defaultStore/getHeaderContents']
                },
                set: function (contents = null) {
                    this.$store.commit('defaultStore/setHeaderContents', contents);
                }
            },
            footerContents:{
                get: function () {
                    return this.$store.getters['defaultStore/getFooterContents']
                },
                set: function (contents = null) {
                    this.$store.commit('defaultStore/setFooterContents', contents);
                }
            },
            socialLinks:{
                get: function () {
                    return this.$store.getters['defaultStore/getSocialLinks']
                },
                set: function (contents = null) {
                    this.$store.commit('defaultStore/setSocialLinks', contents);
                }
            },
        },
        methods:{
            footerPreloads(){
                this.$Progress.start()
                this.$store.dispatch('defaultStore/footerContents');
            }
        },
    }
</script>
