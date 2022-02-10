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
                    <div class="account_info_left">
                        <h2 class="my_acc_subtitle">CHANGE PASSWORD</h2>
                        <template v-if="customerInput">
                            <div class="form_inline_border form_inline">
                                <label class="required">Current Password</label>
                                <input type="password" class="form-control" placeholder="*******" v-model="customerInput.current_password">
                                <small v-for="(formError, errorIndex) in formErrors['current_password']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></small>
                            </div>
                            <div class="form_inline_border form_inline">
                                <label class="required">New Password</label>
                                <input type="password" class="form-control" placeholder="New Password" v-model="customerInput.password">
                                <small v-for="(formError, errorIndex) in formErrors['password']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></small>
                            </div>
                            <div class="form_inline_border form_inline">
                                <label class="required">Confirm New Password</label>
                                <input type="password" class="form-control" placeholder="Confirm New Password" v-model="customerInput.password_confirmation">
                                <small v-for="(formError, errorIndex) in formErrors['password_confirmation']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></small>
                            </div>
                        </template>
                    </div>
                    <div class="account_info_right">
                    </div>
                    <button class="btn_grey width_200p" @click="changePassword"   ref="changePasswordButton">save</button>
                </div>
            </div>
        </div>
        <!-- ============================
            END PRODUCT SINGLE SECTION
        ============================== -->
        <div class="logout_mobile">
            <button class="btn_transparent">logout</button>
        </div>

    </div>
</template>
<script>
    import customerStore from './customerStore'
    import validate from 'validate.js'
    import mixins from '../helpers/mixins'
    export default {
        name:'customerChangePassword',
        mixins: [mixins],
        data() {
            return {
                customerInput: {
                    current_password: '',
                    password: '',
                    password_confirmation: '',
                },
                changePasswordLoader: null,
            }
        },
        components: {
            productComponent: () => import(/* webpackChunkName: "js/custoemr/productComponent" */ './components/WishlistProduct.vue'),
        },
        beforeCreate() {
            if (!(this.$store && this.$store.state && this.$store.state['customerStore'])) {
                this.$store.registerModule("customerStore", customerStore);
            }
        },
        mounted() {
            this.formErrors = {}
        },
        watch: {
            loading(loading) {
                if(this.changePasswordLoader && loading === false) {
                    this.changePasswordLoader.hide();
                    this.changePasswordLoader = null;
                }
            },
        },
        computed:{
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
            buyerLogout() {
                this.$Progress.start()
                this.$store.dispatch('customerStore/tryLogout');
            },
            changePassword() {
                this.$Progress.start()
                if(!this.validateCustomerInput()) return;
                this.changePasswordLoader = this.inlineLoader({ container: this.$refs.changePasswordButton })
                let formData = this.customerInput;
                this.$store.dispatch('customerStore/changePassword', formData);
            },
            validateCustomerInput() {
                let isValid = true;

                const constraints = {
                    current_password: {
                        presence: {
                            allowEmpty: false,
                            message:'^ Field is required.'
                        },
                        length: {
                            minimum: 6,
                            message: "must be at least 6 characters"
                        },
                    },
                    password: {
                        presence: {
                            allowEmpty: false,
                            message:'^ Field is required.'
                        },
                        length: {
                            minimum: 6,
                            message: "must be at least 6 characters"
                        },
                        equality: "password_confirmation"
                    },
                    password_confirmation: {
                        presence: {
                            allowEmpty: false,
                            message:'^ Field is required.'
                        },
                        length: {
                            minimum: 6,
                            message: "must be at least 6 characters"
                        },
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
