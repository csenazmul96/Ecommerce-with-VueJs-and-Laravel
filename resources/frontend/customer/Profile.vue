<template>
    <div>
        <!-- ============================
            START PRODUCT SINGLE SECTION
        ============================== -->
        <div class="account_bredcrumbs">
            <div class="a_c_bredcrumbs_left">
                <a href="javascript:void(0)" onclick="window.history.back()">← back </a>
            </div>
            <div class="a_c_bredcrumbs_right">
                <a href="javascript:void(0)" @click.prevent="buyerLogout">Log Out</a>
            </div>
        </div>
        <div class="account_bredcrumbs_mobile">
            <h2>Account Information <span class="s"><a href="#">Back</a></span></h2>
        </div>
        <div class="my_account_dashboard">
            <div class="m_a_dashboard_title my_acc_container">
                <h2>My Account Information</h2>
                <p>Please fill out the forms below and press save.</p>
            </div>
            <div class="my_acc_container">
                <div class="account_info">
                    <div class="account_info_left" style="position:relative">
                        <h2 class="my_acc_subtitle">ACCOUNT INFORMATION</h2>
                        <div class="preloader_wrap" id="userProfilePreload">
                            <div class="loader-container">
                                <div class="loader-circle"></div>
                            </div>
                        </div>
                        <template v-if="customerInput">
                            <div class="form_inline_border form_inline">
                                <label class="required">First Name</label>
                                <input type="text" class="form-control" placeholder="First Name" v-model="customerInput.first_name">
                                <small v-for="(formError, errorIndex) in formErrors['first_name']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></small>
                            </div>
                            <div class="form_inline_border form_inline">
                                <label class="required">Last Name</label>
                                <input type="text" class="form-control" placeholder="Last Name" v-model="customerInput.last_name">
                                <small v-for="(formError, errorIndex) in formErrors['last_name']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></small>
                            </div>
                            <div class="form_inline_border form_inline">
                                <label class="required">Email</label>
                                <input type="text" class="form-control" placeholder="Email Address" v-model="customerInput.email">
                                <small v-for="(formError, errorIndex) in formErrors['email']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></small>
                            </div>
                        </template>
                    </div>
                    <div class="account_info_right">
                    </div>
                    <button class="btn_grey width_200p" @click="updateCustomer" ref="customerProfileButton">save</button>
                </div>
            </div>
        </div>
        <!-- ============================
            END PRODUCT SINGLE SECTION
        ============================== -->
        <div class="logout_mobile">
            <button class="btn_transparent" @click.prevent="buyerLogout">logout</button>
        </div>

    </div>
</template>
<script>
    import customerStore from './customerStore';
    import validate from 'validate.js'
    import mixins from '../helpers/mixins'
    export default {
        name:'customerProfile',
        mixins: [mixins],
        data() {
            return {
                customerInput: null,
                customerProfileLoader: null,
            }
        },
        components: {
            productComponent: () => import(/* webpackChunkName: "js/customer/productComponent" */ './components/WishlistProduct.vue'),
        },
        beforeCreate() {
            if (!(this.$store && this.$store.state && this.$store.state['customerStore'])) {
                this.$store.registerModule("customerStore", customerStore);
            }
        },
        mounted(){
            this.pagePreloads();
            this.formErrors = {}
        },
        watch: {
            userInformation(userInformation) {
                this.customerInput = userInformation;
            },
            loading(loading) {
                if(this.customerProfileLoader && loading === false) {
                    this.customerProfileLoader.hide();
                    this.customerProfileLoader = null;
                }
            },
        },
        computed:{
            userInformation() {
                return this.$store.getters['customerStore/getUserInformation']
            },
            loading() {
                return this.$store.getters['customerStore/getLoading'];
            },
            formErrors: {
                get: function () {
                    return this.$store.getters['customerStore/getFormErrors']
                },
                set: function (errorClear = {}) {
                    this.$store.commit('customerStore/setFormErrors', errorClear);
                }
            },
        },
        methods:{
            async pagePreloads(){
                this.$Progress.start()
                await this.$store.dispatch('customerStore/profile')
                    .then((response)=>{
                        $("#userProfilePreload").fadeOut("slow");
                    })
            },
            buyerLogout() {
                this.$Progress.start()
                this.$store.dispatch('customerStore/tryLogout');
            },
            updateCustomer() {
                if(!this.validateCustomerInput()) return;
                this.customerProfileLoader = this.inlineLoader({ container: this.$refs.customerProfileButton })
                let formData = this.customerInput;
                this.$store.dispatch('customerStore/updateCustomer', formData);
            },
            validateCustomerInput() {
                let isValid = true;

                const constraints = {
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
                }

                const customerInputErrors = validate(this.$data.customerInput, constraints);
                if (customerInputErrors) {
                    this.$Progress.fail()
                    this.formErrors = customerInputErrors
                    isValid = false;
                }
                return isValid;
            },
        },
    }
</script>
