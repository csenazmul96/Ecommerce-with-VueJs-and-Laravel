<template>
    <!-- ============================
        START CART SECTION
    ============================== -->
    <div>
        <div class="header_cart h_o_dropdown" id="cart" v-if="cartItems && cartItems.length > 0">
            <div class="header_cart_inner">
                <ul :class="{scroll:cartItems.length > 2}">
                    <li v-for="(cartItem, cartIndex) in cartItems" :key="'cartItem_' + cartIndex">
                        <div class="cross_ic" @click.prevent="deleteFromCart(cartItem)"></div>
                        <div class="h_c_inner_left">
                            <router-link :to="{ name: 'single-product', params: { parent: cartItem.item ? cartItem.item.slug : '' }}">
                                <img v-lazy="{ src:cartItem.item_images.length ? (cartItem.item_images[0].thumbs_img) : ('/' + defaultImage.value) }" :alt="'Hologram Product ' + cartItem.item.style_no" class="img-fluid">
                            </router-link>
                        </div>
                        <div class="h_c_inner_right">
                            <h3>
                                <router-link :to="{ name: 'single-product', params: { parent: cartItem.item ? cartItem.item.slug : '' }}">
                                    {{cartItem.item ? cartItem.item.name : 'Undefined'}}
                                </router-link>
                            </h3>
                            <div class="h_c_inner_info">
                                <ul>
                                    <li v-if="cartItem.color"><span>Color</span> {{cartItem.color ? cartItem.color.name : 'Not set'}}</li>
                                    <li v-if="cartItem.itemsize"><span>Size</span> {{cartItem.itemsize ? cartItem.itemsize.name : 'Not set'}}</li>
                                    <li><span>Price</span> USD$ {{cartItem.item ? cartItem.item.price : 0 | round(2)}}</li>
                                    <li><span>Quantity</span> {{cartItem.quantity}}</li>
                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>
                <p class="subototal">
                    <span>CART SUBTOTAL:</span>
                    <span>USD$ {{computedSubTotal | round(2)}}</span>
                </p>
                <div class="h_c_button">
                    <router-link :to="{ name: 'checkout'}" class=" btn btn_common mb_10">
                        <img src="/images/cart-white.png" alt=""> <span class="ml_5">Checkout</span>
                    </router-link>
                    <router-link :to="{ name: 'cart'}" class=" btn btn_common">
                        View cart
                    </router-link>
                </div>
            </div>
        </div>
        <div class="header_cart h_o_dropdown empty_cart" id="cart" v-else>
            <div class="header_cart_inner">
                <h2>You have no items in your shopping cart.</h2>
            </div>
        </div>
    </div>
    <!-- ============================
        END CART SECTION
    ============================== -->
</template>
<script>
    import mixins from '../../../helpers/mixins'
    import defaultStore from '../../defaultStore'
    import customerStore from "../../../customer/customerStore";
    export default {
        mixins: [mixins],
        name:'headerCartComponent',
        beforeCreate() {
            if (!(this.$store && this.$store.state && this.$store.state['defaultStore'])) {
                this.$store.registerModule("defaultStore", defaultStore);
            }
            if (!(this.$store && this.$store.state && this.$store.state['customerStore'])) {
                this.$store.registerModule("customerStore", customerStore);
            }
        },
        mounted(){
            this.cartPreloads();
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
            doubleSlashFilter: function (value) {
                return value.replace("//", "/");
            },
        },
        computed:{
            customer() {
                return this.$store.getters['customerStore/getCustomer'];
            },
            defaultImage() {
                return this.$store.getters['defaultStore/getDefaultImage']
            },
            cartItems() {
                return this.$store.getters['defaultStore/getCartItems']
            },
            computedSubTotal () {
                let total = 0;
                this.cartItems.forEach(function (cartItem) {
                    if(cartItem.item) {
                        total += Number(cartItem.item.price * cartItem.quantity);
                    }
                })
                return total;
            },
        },
        methods: {
            cartPreloads(){
                // this.$store.dispatch('defaultStore/cartItems');
            },
            deleteFromCart(cartItem) {
                this.$Progress.start()
                let formData = [];
                if(this.customer){
                    formData = {
                        id: cartItem.id
                    }
                }else{
                    formData = cartItem
                }
                this.$store.dispatch('defaultStore/deleteFromCart', formData);
                this.$Progress.finish()
            },
        },
    }
</script>
