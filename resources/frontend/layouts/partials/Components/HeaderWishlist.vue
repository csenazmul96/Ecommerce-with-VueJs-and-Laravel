<template>
    <!-- ============================
        START WISHLIST SECTION
    ============================== -->
    <div>
        <div class="header_cart h_o_dropdown" id="wishlist" v-if="wishlistItems && wishlistItems.length > 0">
            <div class="header_cart_inner">
                <ul :class="{scroll:wishlistItems.length > 2}">
                    <li v-for="(wishlistItem, cartIndex) in wishlistItems" :key="'wishlistItem_' + cartIndex">
                        <div class="cross_ic" @click.prevent="deleteFromWhislist(wishlistItem)"></div>
                        <div class="h_c_inner_left">
                            <img :src="wishlistItem.images && wishlistItem.images.length > 0 ? wishlistItem.images[0].thumbs_img : defultImage.value" :alt="'Hologram Product ' + wishlistItem.style_no" class="img-fluid">
                        </div>
                        <div class="h_c_inner_right">
                            <h3>
                                <router-link :to="{ name: 'single-product', params: { parent: wishlistItem ? wishlistItem.slug : '' }}">
                                    {{wishlistItem ? wishlistItem.name : ''}}
                                </router-link>
                            </h3>
                            <div class="h_c_inner_info">
                                <ul>
                                    <li><span>Price</span> USD$ {{wishlistItem ? wishlistItem.price : 0 | round(2)}}</li>
                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>
                <br>
                <div class="h_c_button">
                    <router-link :to="{ name: 'customer-wishlist'}" class="btn btn_common">
                        <i class="fas fa-shopping-cart"></i> <span class="ml_5">See Your Wishlist</span>
                    </router-link>
                </div>
            </div>
        </div>
        <div class="header_cart h_o_dropdown empty_cart" id="wishlist" v-else>
            <div class="header_cart_inner">
                <h2>You have no items in your wishlist.</h2>
            </div>
        </div>
    </div>
    <!-- ============================
        END WISHLIST SECTION
    ============================== -->
</template>
<script>
    import defaultStore from '../../defaultStore'
    export default {
        name:'headerWishlistComponent',
        beforeCreate() {
            if (!(this.$store && this.$store.state && this.$store.state['defaultStore'])) {
                this.$store.registerModule("defaultStore", defaultStore);
            }
        },
        mounted(){
            this.wishlistPreloads();
        },
        filters: {
            round: function (value, decimals) {
                if(!value) {
                    value = 0;
                }

                if(!decimals) {
                    decimals = 0;
                }
                var value = Number(value);
                value = value.toFixed(decimals);

                return value;
            },
        },
        computed: {
            defultImage() {
                return this.$store.getters['defaultStore/getDefaultImage']
            },
            wishlistItems() {
                return this.$store.getters['defaultStore/getWishlistItems']
            },
        },
        methods: {
            wishlistPreloads(){
                // this.$store.dispatch('defaultStore/wishlistItems');
            },
            deleteFromWhislist(whishlist) {
                this.$Progress.start()
                let formData = {
                    item_id: whishlist.id
                }
                this.$store.dispatch('defaultStore/deleteFromWishlist', formData);
                this.$Progress.finish()
            },
        },
    }
</script>
