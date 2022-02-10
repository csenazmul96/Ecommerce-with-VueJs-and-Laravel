<template>
    <!-- ============================
        START PROFILE DASHBOARD SECTION
    ============================== -->
    <div>
        <div class="account_bredcrumbs">
            <div class="a_c_bredcrumbs_left">
                <a href="javascript:void(0)" onclick="window.history.back()">← back </a>
            </div>
            <div class="a_c_bredcrumbs_right">
                <a href="javascript:void(0)" @click.prevent="buyerLogout">Log Out</a>
            </div>
        </div>
        <div class="my_account_dashboard ">
            <div class="m_a_dashboard_title my_acc_container">
                <h2>My Wishlist </h2>
                <p>Displayed below are all of your wishlist products with Well Made Clothes.</p>
            </div>
            <div class="wishlist_area my_acc_container">
                <div class="wishlist_item" style="position:relative; min-height:100px;">
                    <div class="preloader_wrap" id="wishlistPreload">
                        <div class="loader-container">
                            <div class="loader-circle"></div>
                        </div>
                    </div>
                    <template v-if="wishlistItems && wishlistItems.length > 0">
                        <productComponent v-for="(product, productKey) in wishlistItems" :key="'product_' + productKey"  :product="product" :defaultImage="defaultImage"></productComponent>
                    </template>
                </div>
            </div>
            <div class="my_acc_container">
                <div class="my_acc_back">
                    <router-link :to="{ name: 'customer-dashboard'}" class="btn btn_transparent width_240p">
                        <i class="fas fa-shopping-cart"></i> <span class="ml_5">Back to Dashboard</span>
                    </router-link>
                    <button class="btn_transparent width_240p" @click.prevent="buyerLogout">Log Out</button>
                </div>
            </div>

            <div class="logout_mobile">
                <button class="btn_transparent" @click.prevent="buyerLogout">logout</button>
            </div>
        </div>

    </div>
    <!-- ============================
        END PROFILE DASHBOARD SECTION
    ============================== -->
</template>
<script>
    import productComponent from '../ecommerce/components/Product'
    import defaultStore from '../layouts/defaultStore'
    import customerStore from './customerStore'
    export default {
        name:'customerDashboard',
        components: {
            productComponent: productComponent,
        },
        beforeCreate() {
            if (!(this.$store && this.$store.state && this.$store.state['defaultStore'])) {
                this.$store.registerModule("defaultStore", defaultStore);
            }
            if (!(this.$store && this.$store.state && this.$store.state['customerStore'])) {
                this.$store.registerModule("customerStore", customerStore);
            }
        },
        mounted(){
            this.pagePreloads();
        },
        watch: {
            // wishlistItems(wishlistItems) {
            //     if(wishlistItems && wishlistItems.length > 0) $("#wishlistPreload").fadeOut("slow");
            // },
        },
        computed:{
            wishlistItems() {
                return this.$store.getters['defaultStore/getWishlistItems']
            },
            defaultImage() {
                return this.$store.getters['defaultStore/getDefaultImage']
            },
        },
        methods:{
            deleteFromWhislist(whishlist) {
                let formData = {
                    item_id: whishlist.id
                }
                this.$store.dispatch('defaultStore/deleteFromWishlist', formData);
            },
            async pagePreloads(){
                await this.$store.dispatch('defaultStore/wishlistItems')
                    .then((response)=>{
                        $("#wishlistPreload").fadeOut("slow");
                    })
                    .catch((error) => {
                        // console.log(error)
                    })
            },
            buyerLogout() {
                this.$store.dispatch('customerStore/tryLogout');
            },
        },
    }
</script>
