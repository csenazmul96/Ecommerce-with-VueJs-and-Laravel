<template>
    <form class="w3-container w3-display-middle w3-card-4 w3-padding-16" method="POST" id="payment-form" action="/paypal">
        <input type="hidden" name="discount" :value="discount ? discount : 0">
        <input type="hidden" name="shippingCost" :value="shippingCost">
        <input type="hidden" name="totalAmount" :value="totalAmount">
        <input type="hidden" name="cartTotal" :value="cartTotal">
        <input type="hidden" name="email" :value="user ? user.email : 'email'">
        <input type="hidden" name="first_name" :value="user ? user.first_name : ' '">
        <input type="hidden" name="last_name" :value="user ? user.last_name : ''">
        <input type="hidden" name="password" :value="user ? user.password : '123456'">
        <input type="hidden" name="shippingMethod" :value="getShippingMethod()">
        <input type="hidden" name="shippingInput" :value="getShippingInputInfo()">
        <input type="hidden" name="billingInput" :value="getBillingInputInfo()">
        <input type="hidden" name="guest_user_id" :value="guestUserId()">
        <input type="hidden" name="cartItems" :value="getCartItems()">
        <button type="submit" @click="buttonDisable = true"  :class="{disable:buttonDisable}" name="submit" alt="PayPal - The safer, easier way to pay online!" class="btn btn_common btn_paypal">
            <img width="65px" src="/images/paypal_btn.png" alt="">  <i v-if="buttonDisable" class="fas fa-spinner fa-pulse"></i>
        </button>
    </form>
</template>


<script>
import mixin from '../../helpers/mixins'
export default {
    mixins: [mixin],
    name: "ShippingAddress",
    data(){
        return {
            shippingCountries: this.countries,
            shippingStates: this.states,
            buttonDisable:false,
        }
    },
    watch:{
        errorMessage(errorMessage) {
            if (errorMessage) {
                this.$swal({
                    icon: 'error',
                    title: errorMessage
                })
                this.$store.commit('ecommerceStore/setErrorMessage', '');
            }
        },
        states(states) {
            if (states) {
                this.shippingStates = states
            }
        },
        countries(countries) {
            if (countries) {
                this.shippingCountries = countries
            }
        },
    },
    created() {
        if(this.$route.query.error){
            this.buttonDisable = false
            this.$swal({
                icon: 'success',
                title: this.$route.query.error === 'Addressing' ? "Shippping Address invalid" : ''
            })
        }
    },
    computed:{
        formErrors: {
            get: function () {
                return this.$store.getters['ecommerceStore/getFormErrors']
            },
            set: function (errorClear = {}) {
                this.$store.commit('ecommerceStore/setFormErrors', errorClear);
            }
        },
    },
    props: {
        shippingInput: {
            type: Object,
            default() {
                return {};
            }
        },
        billingInfo: {
            type: Object,
            default() {
                return {};
            }
        },
        cartItems: {},
        shippingMethod: {},
        user: {},
        states: {},
        countries: {},
        discount:{},
        totalAmount:{},
        shippingCost:{},
        cartTotal:{},
    },
    methods:{
        phoneNumberA(number){
            return number.substr(0, 3)
        },
        phoneNumberB(number){
            return number.substr(3, 3)
        },
        phoneNumberC(number){
            return number.slice(6, number.length);
        },
        appUrl(){
            return process.env.MIX_APP_URL
        },
        getCartItems(){
            return JSON.stringify(this.cartItems);
        },
        getShippingMethod(){
            return JSON.stringify(this.shippingMethod);
        },
        guestUserId(){
            return window.localStorage.getItem('cart')
        },
        getShippingInputInfo(){
            return JSON.stringify(this.shippingInput);
        },
        getBillingInputInfo(){
            return JSON.stringify(this.billingInfo);
        },
    }
}
</script>

