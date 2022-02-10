<template>
    <!-- ============================
        START PRODUCT SINGLE SECTION
    ============================== -->
    <div>
        <section class="cart_area" v-if="cartInputs.length">
            <div class="cart_title">
                <h2>Shopping Cart</h2>
            </div>
            <div class="cart_content">
                <div class="cart_left">
                    <div class="cart_table" >
                        <table class="table">
                            <colgroup>
                                <col width="12%">
                                <col width="50%">
                                <col width="15%">
                                <col width="15%">
                                <col width="5%">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th></th>
                                    <th>SUBTOTAL</th>
                                    <th>Quantity</th>
                                    <th></th>
                                </tr>
                            </thead>
                        </table>
                        <div :class="{scroll: cartInputs.length > 4}">
                            <table class="table" >
                                <colgroup>
                                    <col width="12%">
                                    <col width="50%">
                                    <col width="15%">
                                    <col width="15%">
                                    <col width="5%">
                                </colgroup>
                                <tbody >
                                    <template v-if="cartInputs && cartInputs.length > 0">
                                        <tr v-for="(cartItem, cartItemKey) in cartInputs" :key="'cartItem_' + cartItemKey">
                                            <td>
                                                <div class="cart_product">
                                                    <router-link :to="{ name: 'single-product', params: { parent: cartItem.item.slug }}">
                                                        <img v-lazy="{
                                                            error: cartItem.item_images.length ? (cartItem.item_images[0].compressed_img_webp) : ('/' + defaultImage.value) ,
                                                            src: imgErrHndl(cartItem.item_images.length ? (cartItem.item_images[0].compressed_img_jpg) : ('/' + defaultImage.value))
                                                         }" :alt="'Hologram Product ' + cartItem.style_no" class="img-fluid">
                                                    </router-link>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="cart_product_name">
                                                    <h2>
                                                        <router-link :to="{ name: 'single-product', params: { parent: cartItem.item.slug }}">
                                                            {{cartItem.item && cartItem.item.name ? cartItem.item.name : 'No Specific Name'}}
                                                        </router-link>
                                                    </h2>
                                                    <p>
                                                        <span>USD ${{cartItem.item.price | round(2)}}</span>
                                                    </p>
                                                    <ul>
                                                        <li v-if="cartItem.color">Color : <span>{{cartItem.color ? cartItem.color.name : 'Not set'}}</span> </li>
                                                        <li v-if="cartItem.itemsize">Size : <span> {{cartItem.itemsize ? cartItem.itemsize.name : 'Not set'}}</span> </li>
                                                        <!-- <li>Price : <span> USD${{cartItem.item ? cartItem.item.price : '0'}}</span></li> -->
                                                    </ul>
                                                    <div class="err_msg" v-for="(formError, errorIndex) in ecommerceFormErrors['cartItems.' + cartItemKey + '.quantity']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError.replace( 'cartItems.' + cartItemKey + '.quantity', 'quantity')}}</span></div>
                                                </div>
                                            </td>
                                            <td>USD$ {{cartItem.item ? cartItem.item.price * cartItem.quantity : 0 | round(2)}}</td>
                                            <td>
                                                <div class="num_count">
                                                    <input type="text" class="qty" v-model="cartInputs[cartItemKey].quantity">
                                                    <button @click.prevent="updateCartItem(cartInputs[cartItemKey])">update</button>
                                                </div>
                                            </td>
                                            <td><span class="close" @click.prevent="deleteFromCart(cartItem)"><i class="fas fa-times"></i></span></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <div class="update_cart">
                            <span @click.prevent="updateCart">Update Cart</span> <!-- Large Screen-->
                        </div>
                    </div>

                    <div class="checkout_table_summery cart_table_mobile"  :class="{scroll: cartInputs.length > 4}">
                        <table class="table mb_0">
                            <colgroup>
                                <col style="width: 92px;">
                                <col>
                            </colgroup>
                            <tbody>
                                <template v-if="cartInputs && cartInputs.length > 0">
                                    <tr v-for="(cartItem, cartItemKey) in cartInputs" :key="'mob_cartItem_' + cartItemKey">
                                        <td>
                                            <div class="c_t_img">
                                                <router-link :to="{ name: 'single-product', params: { parent: cartItem.item.slug }}">
                                                    <img v-lazy="{
                                                            error: cartItem.item_images.length ? (cartItem.item_images[0].compressed_img_webp) : ('/' + defaultImage.value),
                                                            src: imgErrHndl(cartItem.item_images.length ? (cartItem.item_images[0].compressed_img_jpg) : ('/' + defaultImage.value))
                                                         }" :alt="'Hologram Product ' + cartItem.style_no" class="img-fluid">
                                                </router-link>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="c_t_text">
                                                <h2><router-link :to="{ name: 'single-product', params: { parent: cartItem.item.slug }}"> {{cartItem.item && cartItem.item.name ? cartItem.item.name : 'No Specific Name'}} </router-link></h2>
                                                <p>
                                                    <span>USD:</span>
                                                     <span class="float-right">${{cartItem.item.price | round(2)}}</span>
                                                </p>
                                                <ul>
                                                    <li v-if="cartItem.color">Color : <span>{{cartItem.color ? cartItem.color.name : 'Not set'}}</span> </li>
                                                    <li v-if="cartItem.itemsize">Size : <span> {{cartItem.itemsize ? cartItem.itemsize.name : 'Not set'}}</span> </li>
                                                    <li>Qty: <span><input type="text" class="qty text-right" v-model="cartInputs[cartItemKey].quantity"></span></li>
                                                    <li class="text-right width_full" @click.prevent="updateCartItem(cartInputs[cartItemKey])"><span></span> <span>Update</span> </li>
                                                    <li class="text-right width_full" @click.prevent="deleteFromCart(cartItem)"><span></span> <span><i class="fas fa-times"></i></span> </li>
                                                </ul>

                                                <div class="err_msg" v-for="(formError, errorIndex) in ecommerceFormErrors['cartItems.' + cartItemKey + '.quantity']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError.replace( 'cartItems.' + cartItemKey + '.quantity', 'quantity')}}</span></div>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                        <div class="update_cart xx" >
                            <span @click.prevent="updateCart">Update Cart</span>
                        </div>
                    </div>
                    <div class="promo_code_wrap">
                        <div class="promo_code" v-if="customer">
                            <ul v-if="customer">
                                <li data-toggle="collapse_slide" data-target="#promo_code">
                                    Promotional code / Gift voucher code
                                    <span v-if="promoCode && promoCode.coupon_code"> (Applied promo is: {{promoCode.coupon_code}}. Discount is: {{promoCode.discount}})</span>
                                </li>
                            </ul>
                            <div class="promo_code_details" id="promo_code" v-if="customer">
                                <div class="promo_code_details_inner">
                                    <input type="text" class="form-control" placeholder="Enter Promotional code / Gift voucher" v-model="newCoupon">
                                    <button class="btn_common coupon_apply" @click.prevent="applyCoupon" :disabled="couponLoader" ref="couponButton">apply</button>
                                    <div class="err_msg mt_10" v-for="(formError, errorIndex) in defaultFormErrors['coupon']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cart_right">
                    <div class="cart_right_table">
                        <table class="table">
                            <tbody>
                            <tr>
                                <td>SUBTOTAL</td>
                                <td>USD${{computedSubTotal | round(2)}}</td>
                            </tr>
                            <tr v-if="computedDiscount && computedDiscount > 0">
                                <td class="align-middle">
                                    <div class="discount-container">
                                        Discount<small v-if="promoCode && promoCode.coupon_code"> (Promo : {{promoCode.coupon_code}}. Discount : {{promoCode.discount}})</small>
                                    </div>
                                </td>
                                <td class="text-danger">-USD${{computedDiscount | round(2)}}</td>
                            </tr>
                            <tr>
                                <td><b>GRAND TOTAL</b></td>
                                <td><b>USD${{computedTotal | round(2)}}</b></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="proceed_btn text-center">
                        <router-link :to="{ name: 'checkout' }" class="btn btn_common" v-if="cartItems && cartItems.length > 0">
                            <i class="lni lni-lock"></i> <span class="ml_5">Pay Securely Now</span>
                        </router-link>
                        <router-link :to="{ name: 'home'}" class="btn btn_transparent" v-else>
                            <i class="fas fa-shopping-cart"></i> <span class="ml_5">Continue Shopping</span>
                        </router-link>
                    </div>
                </div>
            </div>
            <div class="shipping_return">
                <div class="s_r_inner">
                    <h2>SHIPPING</h2>
                    <div v-html="shipping.shipping"></div>
                    <router-link :to="'/shipping-returns'">More</router-link>
                </div>
                <div class="s_r_inner">
                    <h2>RETURNS</h2>
                    <div v-html="shipping.order_notice"></div>
                    <router-link :to="'/privacy-policy'">More</router-link>
                </div>
            </div>
        </section>
        <section class="hl_checkout" style="min-height: 60vh" v-if="loaded && !cartInputs.length">
            <div class="col-12">
                <div class="empty_checkout">
                    <img src="https://img.icons8.com/ios/100/000000/empty-box.png"/>
                    <p>Your cart is empty!</p>
                    <router-link :to="{name:'home'}" class="btn_common width_200p">Back to home</router-link>
                </div>
            </div>
        </section>
        <div class="p_d_mobile">
            <ul>
                <li data-toggle="collapse_noslide" data-target="#Promotional">
                    <span v-if="promoCode && promoCode.coupon_code"> (Applied promo is: {{promoCode.coupon_code}}. Discount is: {{promoCode.discount}}) <br></span>
                    Promotional code / Gift voucher code
                </li>
                <div class="p_d_mobile_inner" id="Promotional"  v-if="customer">
                    <div class="promo_code_details_inner">
                        <input type="text" class="form-control" placeholder="Enter Promotional code / Gift voucher" v-model="newCoupon">
                        <button class="btn_common" @click.prevent="applyCoupon" :disabled="couponMobLoader" ref="couponMobButton">apply</button>
                        <div class="err_msg mt_10" v-for="(formError, errorIndex) in defaultFormErrors['coupon']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></div>
                    </div>
                </div>
                <div class="p_d_mobile_inner" id="Promotional"  v-else>
                   <router-link :to="{ name: 'customer-login'}" class="btn btn_transparent">
                        Login To Apply Coupon
                    </router-link>
                </div>
                <li data-toggle="collapse_noslide" data-target="#shipping">shipping</li>
                <div class="p_d_mobile_inner" id="shipping">
                    <div v-html="shipping.shipping"></div>
                    <router-link :to="'/shipping-returns'">More</router-link>
                </div>
                <li data-toggle="collapse_noslide" data-target="#Returns11">returns</li>
                <div class="p_d_mobile_inner" id="Returns11">
                    <div v-html="shipping.order_notice"></div>
                    <router-link :to="'/privacy-policy'">More</router-link>
                </div>
            </ul>
        </div>
        <!-- =================================
            START RELATED PRODUCT SECTION
        =================================== -->
        <section class="related_product_area">
            <div class="related_product_title">
                <h2>You Might Like</h2>
            </div>
            <div class="related_product_content">
                <div class="title">You Might Like</div>
                <div class="product_content_wrap">
                    <productComponent v-for="(product, productKey) in recentlyViewedProducts" :key="'product_' + productKey"  :product="product" :defaultImage="defaultImage"></productComponent>
                </div>
            </div>

            <div class="r_p_c_mobile">
                <div v-if="recentlyViewedProducts.length > 0">
                    <carousel :responsive="{0:{items:2},400:{items:2},768:{items:3},1200:{items:4}}" :dots="false" :nav="true">
                        <div class="home_slider_inner" v-for="(product, productKey) in recentlyViewedProducts" :key="'product_' + productKey">
                            <div class="home_slider_inner_content">
                                <router-link :to="{ name: 'single-product', params: { parent: product.slug }}">

                                    <img v-lazy="{
                                                error: product.images.length ? (product.images[0].compressed_img_webp) : ('/' + defaultImage.value),
                                                src: imgErrHndl(product.images.length ? (product.images[0].compressed_img_jpg) : ('/' + defaultImage.value))
                                             }" :alt="'Hologram Product ' + product.style_no" class="img-fluid">

                                    <h2>{{product && product.name ? product.name : 'No Specific Name'}}</h2>
                                    <p>{{product.style_no}} | USD${{product.price | round(2)}}</p>
                                </router-link>
                            </div>
                        </div>
                    </carousel>
                </div>
            </div>
        </section>
    </div>
    <!-- ============================
        END PRODUCT SINGLE SECTION
    ============================== -->
</template>
<script>
    import validate from 'validate.js'
    import mixin from '../helpers/mixins'
    import ecommerceStore from './ecommerceStore'
    import defaultStore from '../layouts/defaultStore'
    import customerStore from '../customer/customerStore'
    import carousel from 'vue-owl-carousel/src/Carousel'
    export default {
        name:'cart',
        mixins: [mixin],
        data() {
            return {
                loaded: false,
                cartInputs: [
                ],
                newCoupon: '',
                couponLoader: null,
                couponMobLoader: null,
                shipping: [],
            }
        },
        metaInfo(){
            return {
            title: 'Cart - Hologram'
            }
        },
        created() {
            axios.get('/api/v1/get/return-shipping').then((response) => this.shipping = response.data);
        },
        beforeCreate() {
            if (!(this.$store && this.$store.state && this.$store.state['ecommerceStore'])) {
                this.$store.registerModule("ecommerceStore", ecommerceStore);
            }
            if (!(this.$store && this.$store.state && this.$store.state['defaultStore'])) {
                this.$store.registerModule("defaultStore", defaultStore);
            }
            if (!(this.$store && this.$store.state && this.$store.state['customerStore'])) {
                this.$store.registerModule("customerStore", customerStore);
            }
        },
        components: {
            productComponent: () => import(/* webpackChunkName: "js/ecomerce/productComponent" */ './components/Product.vue'),
            mobileProductComponent: () => import(/* webpackChunkName: "js/ecomerce/mobileProductComponent" */ './components/MobileProduct.vue'),
            carousel,
        },
        filters: {
            doubleSlashFilter: function (value) {
                return value.replace("//", "/");
            },
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
        mounted(){
            this.pagePreloads();
            this.defaultFormErrors = {}
            this.ecommerceFormErrors = {}
        },
        watch: {
            defaultSuccessMessage(successMessage) {
                if (successMessage) {
                    this.$swal({
                        icon: 'success',
                        title: successMessage
                    })
                    this.$store.commit('defaultStore/setSuccessMessage', '');
                }
            },
            defaultErrorMessage(errorMessage) {
                if (errorMessage) {
                    this.$swal({
                        icon: 'error',
                        title: errorMessage
                    })
                    this.$store.commit('defaultStore/setErrorMessage', '');
                }
            },
            ecommerceSuccessMessage(successMessage) {
                if (successMessage) {
                    this.$swal({
                        icon: 'success',
                        title: successMessage
                    })
                    this.$store.commit('ecommerceStore/setSuccessMessage', '');
                }
            },
            ecommerceErrorMessage(errorMessage) {
                if (errorMessage) {
                    this.$swal({
                        icon: 'error',
                        title: errorMessage
                    })
                    this.$store.commit('ecommerceStore/setErrorMessage', '');
                }
            },
            cartItems(cartItems) {
                this.cartInputs = cartItems;
            },
            defaultLoading(loading) {
                if(this.couponLoader && loading === false) {
                    this.couponLoader.hide();
                    this.couponLoader = null;
                }
                if(this.couponMobLoader && loading === false) {
                    this.couponMobLoader.hide();
                    this.couponMobLoader = null;
                }
            },
        },
        computed:{
            customer() {
                return this.$store.getters['customerStore/getCustomer'];
            },
            defaultLoading() {
                return this.$store.getters['defaultStore/getLoading'];
            },
            defaultSuccessMessage() {
                return this.$store.getters['defaultStore/getSuccessMessage'];
            },
            defaultErrorMessage() {
                return this.$store.getters['defaultStore/getErrorMessage'];
            },
            ecommerceSuccessMessage() {
                return this.$store.getters['ecommerceStore/getSuccessMessage'];
            },
            ecommerceErrorMessage() {
                return this.$store.getters['ecommerceStore/getErrorMessage'];
            },
            cartItems() {
                return this.$store.getters['defaultStore/getCartItems']
            },
            defaultImage:{
                get: function () {
                    return this.$store.getters['defaultStore/getDefaultImage']
                },
                set: function (defaultImage = null) {
                    this.$store.commit('defaultStore/setDefaultImage', defaultImage);
                }
            },
            computedSubTotal () {
                let total = 0;
                this.cartInputs.forEach(function (cartItem) {
                    if(cartItem.item) {
                        total += Number(cartItem.item.price * cartItem.quantity);
                    }
                })
                return total;
            },
            computedDiscount () {
                if(this.promoCode && this.promoCode.discount) {
                    return this.promoCode.discount;
                }
                return 0.00;
            },
            computedTotal () {
                return Number(this.computedSubTotal) - Number(this.computedDiscount);
            },
            promoCode () {
                return this.$store.getters['defaultStore/getPromoCode']
            },
            defaultFormErrors: {
                get: function () {
                    return this.$store.getters['defaultStore/getFormErrors']
                },
                set: function (errorClear = {}) {
                    this.$store.commit('defaultStore/setFormErrors', errorClear);
                }
            },
            ecommerceFormErrors: {
                get: function () {
                    return this.$store.getters['ecommerceStore/getFormErrors']
                },
                set: function (errorClear = {}) {
                    this.$store.commit('ecommerceStore/setFormErrors', errorClear);
                }
            },
            recentlyViewedProducts() {
                return this.$store.getters['ecommerceStore/getRecentlyViewedProducts'];
            },
        },
        methods:{
            pagePreloads(){
                this.$Progress.start()
                this.$store.dispatch('defaultStore/cartItems').finally(() => this.loaded = true);
                this.$store.dispatch('ecommerceStore/recentlyViewed');
                this.$Progress.finish()
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
            async updateCartItem(cartItem) {
                this.$Progress.start()
                this.ecommerceFormErrors = {}
                if(cartItem.quantity <= 0) {
                    this.$Progress.fail()
                    let errors = [];
                    errors['cartItems.' + this.cartItems.findIndex(item => item.id === cartItem.id) + '.quantity'] = ['Invalid quantity'];
                    this.ecommerceFormErrors = errors
                    return;
                }
                let that = this;
                let formData = [];
                if(this.customer){
                    formData = {
                        index: this.cartItems.findIndex(item => item.id === cartItem.id),
                        id: cartItem.id,
                        quantity: cartItem.quantity,
                    }
                }else {
                    formData = cartItem
                }
                await this.$store.dispatch('ecommerceStore/updateCartItem',  formData)
                    .then((response)=>{
                        that.$store.dispatch('defaultStore/cartItems',  formData)
                        this.$Progress.finish()
                    })
                    .catch((error) => {

                    });
            },
            async updateCart() {
                this.$Progress.start()
                this.ecommerceFormErrors = {}
                let errors = [];
                let isValid = true;
                for (let index = 0; index < this.cartInputs.length; index++) {
                    const cartItem = this.cartInputs[index];
                    if(cartItem.quantity <= 0) {
                        this.$Progress.fail()
                        this.ecommerceFormErrors = {}
                        errors['cartItems.' + index + '.quantity'] = ['Invalid quantity'];
                        isValid = false;
                    }
                }
                this.ecommerceFormErrors = errors
                if (!isValid) return;
                let that = this;
                let formData = {
                    cartItems: this.cartInputs
                }
                await this.$store.dispatch('ecommerceStore/updateCart',  formData)
                    .then((response)=>{
                        that.$store.dispatch('defaultStore/cartItems',  formData)
                        this.$Progress.finish()
                    })
                    .catch((error) => {

                    });
            },
            async applyCoupon() {
                this.$Progress.start()
                if(!this.validateCoupon()) return;
                this.couponLoader = this.inlineLoader({ container: this.$refs.couponButton })
                this.couponMobLoader = this.inlineLoader({ container: this.$refs.couponMobButton })
                let formData = {
                    coupon: this.newCoupon
                }
                await this.$store.dispatch('defaultStore/applyCoupon',  formData)
                    .then((response) => {
                        $('li[data-target="#promo_code"]').click().trigger('change');
                    })
                    .catch((error) => {
                        // $('li[data-target="#promo_code"]').click().trigger('change');
                    })

            },
            validateCoupon() {
                let isValid = true;

                const constraints = {
                    newCoupon: {
                        presence: {
                            allowEmpty: false,
                            message:'^ Field is required.'
                        },
                    },
                }

                const newCouponErrors = validate(this.$data, constraints);
                if (newCouponErrors) {
                    this.$Progress.fail()
                    this.$swal({
                        icon: 'error',
                        title: 'Please Insert a coupon code.'
                    })
                    let errors = {
                        coupon: newCouponErrors['newCoupon']
                    };
                    this.defaultFormErrors = errors
                    isValid = false;
                }

                return isValid;
            },
        },
    }
</script>
