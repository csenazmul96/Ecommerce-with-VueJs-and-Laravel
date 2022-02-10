<template>
    <!-- ============================
        START PRODUCT SINGLE SECTION
    ============================== -->
    <div>
        <section class="cart_area text-center">
            <div class="cart_title">
                <h2>Order complete</h2>
            </div>
            <div class="full_width">
                <div class="s_r_inner">
                    <p v-if="orderNumber">Your order <router-link :to="'/dashboard'"> {{orderNumber}} </router-link> is complete and getting ready to be shipped.

                    </p>
                    <p v-else>Your order is is complete and getting ready to be shipped.</p>
                    <p>If you need help, please contact us info@shophologram.com Monday-Friday 9AM-6PM PST</p>
                </div>
            </div>
            <div class="cart_title">
                <h2>Follow us on instagram</h2>
            </div>
        </section>
        <section class="instagram_area" v-if="instagrams.length">
            <div class="insta_slider" style="width: 100%;">
                <carousel :autoplay="false" :loop="true" :dots="false" :responsive="{0:{items:2,nav:false , margin : 10},768:{items:3,nav:false, margin : 10}, 1025:{items:5,nav:false, margin : 40}}">
                    <div class="instagram_inner" v-for="(insta, i) in instagrams" :key="'insta_'+i">
                        <a :href="insta.permalink" target="_blank">
                            <template v-if="!insta.children">
                                <img v-if="insta.media_type === 'IMAGE'" :src="insta.media_url" :alt="insta.caption" class="img-fluid">
                                <video v-else id='video' loop muted preload="metadata" width="100%" height="100%" class="embed-responsive-item" autoplay playsinline>
                                    <source :src="insta.media_url">
                                </video>
                            </template>
                            <template v-else>
                                <img v-if="insta.children && insta.children.data.length && insta.children.data[0].media_type === 'IMAGE'" :src="insta.children.data[0].media_url"  class="img-fluid">
                                <video v-else id='video' loop muted preload="metadata" width="100%" height="100%" class="embed-responsive-item" autoplay playsinline>
                                    <source :src="insta.children.data[0].media_url">
                                </video>
                            </template>
                            <div class="inner">
                                <span><i class="lni lni-instagram"></i></span>
                            </div>
                        </a>
                    </div>
                </carousel>
            </div>
        </section>
    </div>
    <!-- ============================
        END PRODUCT SINGLE SECTION
    ============================== -->
</template>
<script>
    import carousel from 'vue-owl-carousel/src/Carousel'
    import ecommerceStore from './ecommerceStore'
    import VueSlickCarousel from "vue-slick-carousel";
    import privacyNotice from "../sections/PrivacyNotice";
    import productComponent from "./components/Product";
    export default {
        name:'cart',
        data() {
            return {
                couponLoader: null,
                couponMobLoader: null,
                instagrams:[],
                orderNumber: null,
            }
        },
        components: {
            carousel:carousel,
        },
        beforeCreate() {
            if (!(this.$store && this.$store.state && this.$store.state['ecommerceStore'])) {
                this.$store.registerModule("ecommerceStore", ecommerceStore);
            }
        },
        mounted(){
            this.pagePreloads();
        },
        created() {
            if(this.$route.query.order_id){
                var guestId = localStorage.getItem('cart') ? localStorage.getItem('cart') : '';
                if(guestId)
                    axios.get('/api/v1/guest-cart/remove/'+guestId).then((response)=>{
                        this.$store.dispatch('defaultStore/cartItems');
                    })
                this.orderNumber = this.$route.query.order_id;
            }else{
                this.orderNumber = localStorage.getItem("orderNumber")
            }
        },
        watch: {
            successMessage(successMessage) {
                if (successMessage) {
                    this.$swal({
                        icon: 'success',
                        title: successMessage
                    })
                    this.$store.commit('ecommerceStore/setSuccessMessage', '');
                }
            },
            errorMessage(errorMessage) {
                if (errorMessage) {
                    this.$swal({
                        icon: 'error',
                        title: errorMessage
                    })
                    this.$store.commit('ecommerceStore/setErrorMessage', '');
                }
            },
        },
        computed:{
            successMessage() {
                return this.$store.getters['ecommerceStore/getSuccessMessage'];
            },
            errorMessage() {
                return this.$store.getters['ecommerceStore/getErrorMessage'];
            },
            // orderNumber() {
            //     return localStorage.getItem("orderNumber")
            // },
        },
        methods:{
            pagePreloads(){
                axios.get('/api/v1/get/instagram').then((response) => {
                    this.instagrams = response.data.data;
                });
                this.$Progress.start()
                let formData = {
                    'order_number': this.orderNumber
                }
                this.$store.dispatch('ecommerceStore/checkOrder', formData);
            },
        },
    }
</script>
