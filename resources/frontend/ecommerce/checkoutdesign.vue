<template>
    <section class="hl_checkout">
        <div class="hl_checkout_wrap">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="checkout_step_head">
                            <ul>
                                <li class="active"><span>1</span> Information</li>
                                <li><span>2</span> Shipping</li>
                                <li><span>3</span> Payment</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="hl_checkout_left">
                            <div class="step_1">
                                <h3 class="hl_checkout_subtitle">Email Address</h3>
                                <div class="form-group mb_5">
                                    <input type="text" placeholder="Email Address" class="form-control">
                                </div>
                                <div class="c_checkbox mb_20">
                                    <input type="checkbox" id="51" name="000">
                                    <label for="51">Send me this email setting</label>
                                </div>
                                <h3 class="hl_checkout_subtitle pt_20">Shipping Address</h3>
                                <div class="form-row">
                                    <div class="form-group col-6">
                                        <input type="text" placeholder="First Name" class="form-control">
                                    </div>
                                    <div class="form-group col-6">
                                        <input type="text" placeholder="Last Name" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" placeholder="Street Address" class="form-control">
                                </div>
                                <div class="form-group">
                                    <input type="text" placeholder="Apt/Suite/Building/FLoor" class="form-control">
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-4">
                                        <input type="text" placeholder="Postal Code" class="form-control">
                                    </div>
                                    <div class="form-group col-4">
                                        <input type="text" placeholder="City" class="form-control">
                                    </div>
                                    <div class="form-group col-4">
                                        <input type="text" placeholder="Address" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group mb_5">
                                    <input type="text" placeholder="Phone" class="form-control">
                                </div>
                                <div class="c_checkbox">
                                    <input type="checkbox" id="52" name="1">
                                    <label for="52">Save This information for next time</label>
                                </div>
                                <div class="d_flex_end c_shipping">
                                    <a href="#">Return to cart</a>
                                    <button class="btn_common width_200p step_1_btn"> Continue Shipping</button>
                                </div>
                            </div>

                            <div class="step_2">
                                <div class="inner">
                                    <div class="c_custom_section">
                                        <div class="inner">
                                            <div class="c_custom_address">
                                                <span>Email</span>
                                                <p>jesonebourne@gmail.com</p>
                                                <span>Change</span>
                                            </div>
                                        </div>
                                        <div class="inner">
                                            <div class="c_custom_address">
                                                <span>Ship to</span>
                                                <p>936 West 23rd Street, Los Angeles, CA 90007, United States</p>
                                                <span>Change</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h3 class="hl_checkout_subtitle pt_20 mt_20">Shipping Method</h3>
                                <div class="c_custom_section">
                                    <div class="inner">
                                        <div class="custom_radio">
                                            <input type="radio" id="74"  checked="checked" name="10">
                                            <label for="74">Saturday, May 22 — Monday, May 24 <br> UPS Ground</label>
                                        </div>
                                        <span>$4.50</span>
                                    </div>
                                    <div class="inner">
                                        <div class="custom_radio">
                                            <input type="radio" id="41"   name="10">
                                            <label for="41">Saturday, May 22 — Monday, May 24 <br> UPS Ground</label>
                                        </div>
                                        <span>$4.50</span>
                                    </div>
                                    <div class="inner">
                                        <div class="custom_radio">
                                            <input type="radio" id="11"  name="10">
                                            <label for="11">Saturday, May 22 — Monday, May 24 <br> UPS Ground</label>
                                        </div>
                                        <span>$4.50</span>
                                    </div>
                                    <div class="inner">
                                        <div class="custom_radio">
                                            <input type="radio" id="111"  name="101">
                                            <label for="111">Saturday, May 22 — Monday, May 24 <br> UPS Ground</label>
                                        </div>
                                        <span>$4.50</span>
                                    </div>
                                </div>
                                <div class="d_flex_end c_shipping">
                                    <a href="#">Return to cart</a>
                                    <button class="btn_common width_200p step_2_btn"> Continue to Payment</button>
                                </div>
                            </div>

                            <div class="step_3">
                                <div class="inner">
                                    <div class="c_custom_section">
                                        <div class="inner">
                                            <div class="c_custom_address">
                                                <span>Email</span>
                                                <p>jesonebourne@gmail.com</p>
                                                <span>Change</span>
                                            </div>
                                        </div>
                                        <div class="inner">
                                            <div class="c_custom_address">
                                                <span>Ship to</span>
                                                <p>936 West 23rd Street, Los Angeles, CA 90007, United States</p>
                                                <span>Change</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h3 class="hl_checkout_subtitle pt_20 mt_20">Shipping Method</h3>
                                <div class="inner billing_img">
                                    <div class="custom_radio">
                                        <input type="radio" id="Checkme4"  checked="checked" name="2">
                                        <label for="Checkme4">
                                            Credit Card
                                            <div class="img">
                                                <img src="images/pay1.png" alt="" class="img-fluid">
                                                <img src="images/pay2.png" alt="" class="img-fluid">
                                                <img src="images/pay3.png" alt="" class="img-fluid">
                                                <img src="images/pay4.png" alt="" class="img-fluid">
                                            </div>
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" placeholder="Card Number" class="form-control">
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-4">
                                            <select class="form-control">
                                                <option value="">MM</option>
                                                <option value="">MM</option>
                                                <option value="">MM</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-4">
                                            <select class="form-control">
                                                <option value="">YY</option>
                                                <option value="">YY</option>
                                                <option value="">YY</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-4">
                                            <input type="text" placeholder="CVV" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" placeholder="Card Holder Name" class="form-control">
                                    </div>
                                    <div class="custom_radio c_paypal">
                                        <input type="radio" id="Checkme3"  name="2">
                                        <label for="Checkme4"><img src="images/paypay.gif" alt="" class="img-fluid"></label>
                                    </div>
                                </div>
                                <div class="d_flex_end c_shipping">
                                    <a href="#">Return to cart</a>
                                    <button class="btn_common width_200p step_3_btn"> Continue to Payment</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="hl_checkout_right">
                            <h2 class="hl_checkout_title">Order Summery</h2>
                            <div class="hl_order_show">
                                <table>
                                    <tr>
                                        <td>
                                            <div class="inner">
                                                <img src="images/menu_image/d498b1a0-b920-11eb-8ac4-15a52b05ef00.webp" alt="" class="img-fluid">
                                                <div class="text">
                                                    <h2>Product Title</h2>
                                                    <p class="fwb">$10.50</p>
                                                    <p>Qty : 1</p>
                                                </div>

                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="inner">
                                                <img src="images/menu_image/d498b1a0-b920-11eb-8ac4-15a52b05ef00.webp" alt="" class="img-fluid">
                                                <div class="text">
                                                    <h2>Product Title</h2>
                                                    <p class="fwb">$10.50</p>
                                                    <p>Qty : 1</p>
                                                </div>

                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="checkout_promo">
                                <input type="text" placeholder="Enter Promo Code" class="form-control">
                                <button class="btn_common">apply now</button>
                            </div>
                            <div class="hl_total_show">
                                <table>
                                    <tr>
                                        <td>Items(1)</td>
                                        <td>$21.00</td>
                                    </tr>
                                    <tr>
                                        <td>Shipping</td>
                                        <td>$5.00</td>
                                    </tr>
                                    <tr>
                                        <td>Sales Tax</td>
                                        <td>$21.00</td>
                                    </tr>
                                    <tr class="total">
                                        <td>Total</td>
                                        <td>$215.00</td>
                                    </tr>
                                </table>
                            </div>
                            <!-- <button class="btn_common mb_15"><i class="lni lni-lock pr_10"></i> Pay with Credit card</button>
                            <button class="btn_common">Pay with paypal</button> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hl_checkout_footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="hl_footer_inner">
                            <img src="images/hl-c-left.png" alt="" class="img-fluid">
                            <p>All major card accepted</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="hl_footer_inner right">
                            <img src="images/hl-c-right.png" alt="" class="img-fluid">
                            <p>100% Satisfied guarranted</p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="hl_footer_copyright">
                            <p>© 2021 hologram, LLC. All Rights Reserved. | Call Us On 1000</p>
                            <p>Register office : olympic road 1000 L A</p>
                            <p>Company No 00000</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import validate from 'validate.js'
import mixin from '../helpers/mixins'
import ecommerceStore from './ecommerceStore'
import defaultStore from '../layouts/defaultStore'
import customerStore from '../customer/customerStore'
export default {
    name:'checkout',
    mixins: [mixin],
    data() {
        return {
            cartInputs: [

            ],
            cardInput:{
                card_full_name: '',
                card_number: '',
                card_expire: '',
                card_cvc: '',
                card_month: '01',
                card_year: '21',
            },
            billingInput:{
                id: '',
                first_name: '',
                last_name: '',
                address: '',
                address2: '',
                city: '',
                phone: '',
                zip: '',
                country_id: '',
                state_id: '',
                state_text: '',
            },
            shippingInput:{
                id: '',
                first_name: '',
                last_name: '',
                address: '',
                address2: '',
                city: '',
                phone: '',
                zip: '',
                country_id: '',
                state_id: '',
                state_text: '',
            },
            userInput:{
                first_name: '',
                last_name: '',
                email: '',
                password: '',
                password_confirmation: '',
            },
            paymentMethod: '2',
            shippingMethod: null,
            checkoutLoader: null,
            couponLoader: null,
            newCoupon: '',
            note: '',
            user_point: 0,
            pointDiscount: 0,
            storecredit:0,
            storeCreditDiscount: 0,
            grandTotal: 0,
        }
    },
    metaInfo(){
        return {
            title: 'Checkout - ShopHologram'
        }
    },
    async beforeCreate() {
        if (!(this.$store && this.$store.state && this.$store.state['ecommerceStore'])) {
            this.$store.registerModule("ecommerceStore", ecommerceStore);
        }
        if (!(this.$store && this.$store.state && this.$store.state['defaultStore'])) {
            this.$store.registerModule("defaultStore", defaultStore);
        }
        if (!(this.$store && this.$store.state && this.$store.state['customerStore'])) {
            this.$store.registerModule("customerStore", customerStore);
        }

        await this.$loadScript("https://www.paypal.com/sdk/js?client-id=AZY1OYQIi8vS8BVPyFOw3wK1JfGldpuIOWcWo20V8uJyMeHQE1lLaWAXCELsWRMb34Wc--eXBZ7Lf38C&disable-funding=credit,card");
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
        ecommerceLoading(loading) {
            if(this.checkoutLoader && loading === false) {
                this.checkoutLoader.hide();
                this.checkoutLoader = null;
            }
        },
        defaultLoading(loading) {
            if(this.couponLoader && loading === false) {
                this.couponLoader.hide();
                this.couponLoader = null;
            }
        },
        cartItems(cartItems) {
            this.cartInputs = cartItems;
        },
        defaultBilling(defaultBilling) {
            this.billingInput = defaultBilling;
        },
        defaultShipping(defaultShipping) {
            this.shippingInput = defaultShipping;
        },
        shippingMethods(shippingMethods) {
            this.shippingMethod = shippingMethods[0];
        },
        userInformation(userInformation) {
            this.userInput = userInformation;
        },
        computedTotal(total) {
            this.renderPaypal();
        },
    },
    computed:{
        customer() {
            return this.$store.getters['customerStore/getCustomer'];
        },
        ecommerceLoading() {
            return this.$store.getters['ecommerceStore/getLoading'];
        },
        successMessage() {
            return this.$store.getters['ecommerceStore/getSuccessMessage'];
        },
        errorMessage() {
            return this.$store.getters['ecommerceStore/getErrorMessage'];
        },
        formErrors: {
            get: function () {
                return this.$store.getters['ecommerceStore/getFormErrors']
            },
            set: function (errorClear = {}) {
                this.$store.commit('ecommerceStore/setFormErrors', errorClear);
            }
        },
        billingAddresses() {
            return this.$store.getters['ecommerceStore/getAddresses']
        },
        shippingAddresses() {
            return this.$store.getters['ecommerceStore/getAddresses']
        },
        defaultLoading() {
            return this.$store.getters['defaultStore/getLoading'];
        },
        countries() {
            return this.$store.getters['defaultStore/getCountries']
        },
        states() {
            return this.$store.getters['defaultStore/getStates']
        },
        shippingMethods() {
            return this.$store.getters['defaultStore/getShippingMethods']
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
        defaultFormErrors: {
            get: function () {
                return this.$store.getters['defaultStore/getFormErrors']
            },
            set: function (errorClear = {}) {
                this.$store.commit('defaultStore/setFormErrors', errorClear);
            }
        },
        isLoggedIn() {
            return this.$store.getters['customerStore/getIsLoggedIn'];
        },
        defaultBilling() {
            return this.$store.getters['customerStore/getDefaultBilling']
        },
        defaultShipping() {
            return this.$store.getters['customerStore/getDefaultShipping']
        },
        defaultShipping() {
            return this.$store.getters['customerStore/getDefaultShipping']
        },
        userInformation() {
            return this.$store.getters['customerStore/getUserInformation']
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
        computedShipping () {
            if(this.promoCode && this.promoCode.free_shipping == 1) {
                return 0.00;
            }
            if(this.shippingMethod && this.shippingMethod.fee) {
                return this.shippingMethod.fee;
            }
            return 0.00;
        },
        computedDiscount () {
            if(this.promoCode && this.promoCode.discount) {
                return this.promoCode.discount;
            }
            return 0.00;
        },
        computedTotal () {
            let total =  Number(this.computedSubTotal) + Number(this.computedShipping) - Number(this.computedDiscount) - Number(this.pointDiscount) - Number(this.storeCreditDiscount);
            if(total <= 0){
                return
            }
            return Number(this.computedSubTotal) + Number(this.computedShipping) - Number(this.computedDiscount) - Number(this.pointDiscount) - Number(this.storeCreditDiscount);
        },
        promoCode () {
            return this.$store.getters['defaultStore/getPromoCode']
        },
        pointExists () {
            return this.$store.getters['defaultStore/getPointExists']
        },
        dollarDiscount () {
            return this.$store.getters['defaultStore/getDollarDiscount']
        },
        computedBillingStates() {
            let country_id = this.billingInput.country_id;
            let states = this.states;
            let computedStates = [];
            if (country_id) {
                computedStates = states.filter(state => state.country_id == country_id);
            } else {

            }

            return computedStates;
        },
        computedShippingStates() {
            let country_id = this.shippingInput.country_id;
            let states = this.states;

            let computedStates = [];
            if (country_id) {
                computedStates = states.filter(state => state.country_id == country_id);
            } else {

            }
            return computedStates;
        }
    },
    methods:{
        async pagePreloads(){
            this.$store.dispatch('defaultStore/states');
            this.$store.dispatch('defaultStore/countries');
            this.$store.dispatch('defaultStore/shippingMethods');
            this.$store.dispatch('defaultStore/cartItems');
            this.$Progress.start()
            await this.$store.dispatch('customerStore/checkCustomer')
                .then((response)=>{
                    if(this.isLoggedIn) {
                        this.$store.dispatch('defaultStore/cartItems');
                        this.$store.dispatch('ecommerceStore/checkoutAddresses');
                        this.$store.dispatch('customerStore/profile');
                    }
                })
                .catch((error) => {
                    this.$router.push('/login');
                })
        },
        applyPoints(){
            if(this.userInformation){
                var usablePoints = this.userInformation.buyer.points - this.userInformation.buyer.points_spent;
                if(usablePoints< this.user_point ){
                    alert('You can not use more than '+ usablePoints.toFixed(2));
                    this.user_point = 0;
                    this.pointDiscount = 0;
                    return false;
                }
            }
            var calculatedDiscount = this.dollarDiscount.dollar_disounts * this.user_point / this.dollarDiscount.points_use
            this.pointDiscount = calculatedDiscount;
        },
        applyStoreCredit(){
            if(Number(this.storecredit) > Number(this.userInformation.storecredit.amount)){
                alert('You can not use more than '+ this.userInformation.storecredit.amount + ' Credit');
                this.storecredit = 0;
                this.storeCreditDiscount = 0;
                return false;
            }
            this.storeCreditDiscount = this.storecredit;
        },
        checkout(paymentMethod, paymentId){
            this.$Progress.start()
            this.paymentMethod = paymentMethod;

            if (!this.validateCheckout ()) {
                this.$Progress.fail()
                return;
            }
            this.checkoutLoader = this.inlineLoader({ container: this.$refs.checkoutButton })
            let formData = {
                //paymentMethod: this.paymentMethod,
                cardInput: this.cardInput,
                billingInput: this.billingInput,
                shippingInput: this.shippingInput,
                userInput: this.userInput,
                shippingMethod: this.shippingMethod,
                note: this.note,
                user_point: this.user_point,
                storeCredit: this.storecredit,
                grandTotal: this.computedTotal,
                paymentMethod: paymentMethod,
                paymentId: paymentId
            }
            this.$store.dispatch('ecommerceStore/checkout', formData);
        },
        validateCheckout() {
            this.formErrors = {}

            let isValid = true;

            let errors = {};

            // validate customer
            const userConstraints = {
                first_name: {
                    presence: {
                        allowEmpty: false,
                        message:'^ Field is required.'
                    },
                },
                last_name: {
                    presence: {
                        allowEmpty: false,
                        message:'^ Field is required.'
                    },
                },
                email: {
                    presence: {
                        allowEmpty: false,
                        message:'^ Field is required.'
                    },
                    email: true
                },
            };
            const userErrors = validate(this.$data.userInput, userConstraints);
            if (userErrors) {
                for (const [key, value] of Object.entries(userErrors)) {
                    errors['userInput.' + key] = value;
                }
                isValid = false;
            }
            // validate billing address
            const billingConstraints = {
                address: {
                    presence: {
                        allowEmpty: false,
                        message:'^ Field is required.'
                    },
                },
                country_id: {
                    presence: {
                        allowEmpty: false,
                        message:'^ Field is required.'
                    },
                },
                zip: {
                    presence: {
                        allowEmpty: false,
                        message:'^ Field is required.'
                    },
                },
            };
            const billingErrors = validate(this.$data.billingInput, billingConstraints);
            if (billingErrors) {
                for (const [key, value] of Object.entries(billingErrors)) {
                    errors['billingInput.' + key] = value;
                }
                isValid = false;
            }
            // validate shipping address
            const shippingConstraints = {
                address: {
                    presence: {
                        allowEmpty: false,
                        message:'^ Field is required.'
                    },
                },
                country_id: {
                    presence: {
                        allowEmpty: false,
                        message:'^ Field is required.'
                    },
                },
                zip: {
                    presence: {
                        allowEmpty: false,
                        message:'^ Field is required.'
                    },
                },
            };
            const shippingErrors = validate(this.$data.shippingInput, shippingConstraints);
            if (shippingErrors) {
                for (const [key, value] of Object.entries(shippingErrors)) {
                    errors['shippingInput.' + key] = value;
                }
                isValid = false;
            }
            // validate ship method
            const shippingMethodConstraints = {
                id: {
                    presence: {
                        allowEmpty: false,
                        message:'^ Field is required.'
                    },
                },
            };
            const shippingMethodErrors = validate(this.$data.shippingMethod, shippingMethodConstraints);
            if (shippingMethodErrors) {
                for (const [key, value] of Object.entries(shippingMethodErrors)) {
                    errors['shippingMethod.' + key] = value;
                }
                isValid = false;
            }
            // validate card only if paymentMethod = 2
            if (this.paymentMethod == 2) {
                const cardConstraints = {
                    card_full_name: {
                        presence: {
                            allowEmpty: false,
                            message:'^ Field is required.'
                        },
                    },
                    card_number: {
                        presence: {
                            allowEmpty: false,
                            message:'^ Field is required.'
                        },
                    },
                    card_expire: {
                        presence: {
                            allowEmpty: false,
                            message:'^ Field is required.'
                        },
                    },
                    card_cvc: {
                        presence: {
                            allowEmpty: false,
                            message:'^ Field is required.'
                        },
                    },
                };
                const cardErrors = validate(this.$data.cardInput, cardConstraints);
                if (cardErrors) {
                    for (const [key, value] of Object.entries(cardErrors)) {
                        errors['cardInput.' + key] = value;
                    }
                    isValid = false;
                }
            }
            // validate point
            if (this.user_point < 0) {
                errors['user_point'] = ['point at least 0'];
                isValid = false;
            }
            if (this.userInformation && this.userInformation.buyer && (this.userInformation.buyer.points - this.userInformation.buyer.points_spent) < this.user_point) {
                errors['user_point'] = ['Unsufficient point'];
                isValid = false;
            }
            this.formErrors = errors
            return isValid;
        },
        billingChange() {
            let inputValue = this.billingInput.id;
            let newBilling = {
                id: '-1',
                first_name: '',
                last_name: '',
                address: '',
                address2: '',
                city: '',
                phone: '',
                zip: '',
                country_id: '',
                state_id: '',
            }
            if (inputValue != '-1') {
                newBilling = this.billingAddresses.find(allAddress => allAddress.id == inputValue)
            }
            let that = this;
            setTimeout(() => {
                that.$set(that, 'billingInput', newBilling);
                that.$store.dispatch('ecommerceStore/checkoutAddresses');
            }, 100);
        },
        shippingChange() {
            let inputValue = this.shippingInput.id;
            let newShipping = {
                id: '-1',
                first_name: '',
                last_name: '',
                address: '',
                address2: '',
                city: '',
                phone: '',
                zip: '',
                country_id: '',
                state_id: '',
            }
            if (inputValue != '-1') {
                newShipping = this.shippingAddresses.find(address => address.id == inputValue)
            }
            let that = this;
            setTimeout(() => {
                that.$set(that, 'shippingInput', newShipping);
                that.$store.dispatch('ecommerceStore/checkoutAddresses');
            }, 200);
        },
        cardExpire(event) {
            let outputValue = this.cardInput.card_month+'/'+this.cardInput.card_year
            this.$set(this.cardInput, 'card_expire', outputValue);
        },
        cardCvc(event) {
            event = (event) ? event : window.event;
            let inputValue = event.target.value;
            let outputValue = inputValue.replace(/\D/g, "");
            outputValue = outputValue.substring(0,4)
            this.$set(this.cardInput, 'card_cvc', outputValue);
        },
        async applyCoupon() {
            this.$Progress.start()
            if(!this.validateCoupon()) return;
            this.couponLoader = this.inlineLoader({ container: this.$refs.couponButton })
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
        removeCoupon() {
            this.$Progress.start()
            this.$store.dispatch('defaultStore/removeCoupon')
        },
        renderPaypal() {
            let div = $('#paypal-button-container').html('');
            let user = this.userInput;
            let total = this.computedTotal;
            let paypalThis = this;
            if(div.length) {
                paypal.Buttons({
                    env: 'sandbox',
                    style: {
                        color: 'black',
                        shape: 'rect',
                        label: 'pay',
                        height: 40
                    },
                    createOrder: function (data, actions) {
                        // This function sets up the details of the transaction, including the amount and line item details.

                        return actions.order.create({
                            purchase_units: [{
                                amount: {
                                    currency_code: 'USD',
                                    value: total,
                                },
                            }]
                        });
                    },
                    onApprove: function (data, actions) {
                        // This function captures the funds from the transaction.
                        return actions.order.capture().then(function (details) {
                            paypalThis.checkout(3, details.id);
                            // This function shows a transaction success message to your buyer.
                            //alert('Transaction completed by ' + details.payer.name.given_name);
                        });
                    },
                    onClick: function (data, actions) {
                        paypalThis.$Progress.start()
                        paypalThis.paymentMethod = 3;
                        if (!paypalThis.validateCheckout()) {
                            paypalThis.$Progress.fail()
                            return actions.reject();
                        }

                        return actions.resolve();
                    }
                }).render('#paypal-button-container');
            }
        }
    },
}
</script>
