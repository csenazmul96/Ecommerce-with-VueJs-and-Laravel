<template>
    <!-- ============================
        START LOGIN SECTION
    ============================== -->
    <section class="login_page">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="login_inner">
                        <h2>Password Reset Here</h2>
                        <div class="header_account_inner">
                            <div class="form_inline">
                                <label class="required">Email</label>
                                <input type="text" class="form-control" placeholder="Email Address" v-model="buyerForgetPassword.email" @keyup="checkForEnter">
                                <small v-for="(formError, errorIndex) in formErrors['email']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></small>
                            </div>
                            <p v-if="resetPasswordMessage" class="text-success">{{resetPasswordMessage}}</p>
                            <button class="btn_common" @click.prevent="buyerPasswordReset" ref="resetButton">Reset Password</button>
                            <div class="create_acc_btn">
                                <router-link :to="{ name: 'customer-login'}" class="btn btn_transparent">
                                    Back to Login
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ============================
        END LOGIN SECTION
    ============================== -->
</template>
<script>
    import validate from 'validate.js'
    import customerStore from './customerStore'
    import mixins from '../helpers/mixins'
    export default {
        name:'customerPasswordReset',
        mixins: [mixins],
        data() {
            return {
                buyerForgetPassword: {
                    email: '',
                },
                resetLoader: null,
            }
        },
        mounted() {
            this.formErrors = {}
        },
        watch: {
            resetPasswordMessage(message) {
                let that = this;
                if(message) {
                    this.$swal({
                        icon: 'success',
                        title: message
                    })
                    setTimeout(() => {
                        that.resetPasswordMessage = '';
                    }, 3000);
                }
            },
            loading(loading) {
                if(this.resetLoader && loading === false) {
                    this.resetLoader.hide();
                    this.resetLoader = null;
                }
            },
        },
        computed: {
            loading() {
                return this.$store.getters['customerStore/getLoading']
            },
            formErrors: {
                get: function () {
                    return this.$store.getters['customerStore/getFormErrors']
                },
                set: function (errorClear = {}) {
                    this.$store.commit('customerStore/setFormErrors', errorClear);
                }
            },
            resetPasswordMessage: {
                get: function () {
                    return this.$store.getters['customerStore/getResetPasswordMessage'];
                },
                set: function (message = '') {
                    this.$store.commit('customerStore/setResetPasswordMessage', message);
                }
            },
        },
        methods: {
            checkForEnter(event) {
                if (event.keyCode === 13) {
                    event.preventDefault();
                    this.buyerPasswordReset();
                }
            },
            buyerPasswordReset() {
                this.$Progress.start()
                if(!this.validateForgetPassword()) return;
                this.resetLoader = this.inlineLoader({ container: this.$refs.resetButton })
                let formData = {
                    email: this.buyerForgetPassword.email,
                }
                this.$store.dispatch('customerStore/sendResetPassword', formData);
            },
            validateForgetPassword() {
                let isValid = true;

                const constraints = {
                    email: {
                        presence: {
                            allowEmpty: false,
                            message:'^ Field is required.'
                        },
                        email: true
                    },
                }

                const buyerForgetPasswordErrors = validate(this.$data.buyerForgetPassword, constraints);
                if (buyerForgetPasswordErrors) {
                    this.$Progress.fail()
                    this.formErrors = buyerForgetPasswordErrors
                    isValid = false;
                }
                return isValid;
            },
        }
    }
</script>
