<template>
    <!-- ============================
        START LOGIN SECTION
    ============================== -->
    <section class="login_page">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="login_inner">
                        <h2>New Password</h2>
                        <div class="header_account_inner">
                            <div class="form_inline">
                                <label for="staticEmail">Password</label>
                                <input type="password" class="form-control" placeholder="Password" v-model="newPasswordInput.password">
                                <small v-for="(formError, errorIndex) in formErrors['password']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></small>
                            </div>
                            <div class="form_inline">
                                <label for="staticEmail">Confirm Password</label>
                                <input type="password" class="form-control" placeholder="Confirm Password" v-model="newPasswordInput.password_confirmation">
                                <small v-for="(formError, errorIndex) in formErrors['password_confirmation']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></small>
                            </div>
                            <p v-if="newPasswordMessage" class="text-success">{{newPasswordMessage}}</p>
                            <button class="btn_common" @click.prevent="buyerNewPassword"  ref="newPasswordButton">Reset Password</button>
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
                newPasswordInput: {
                    token: '',
                    password: '',
                    password_confirmation: '',
                },
                newPasswordLoader: null,
            }
        },
        mounted() {
            this.formErrors = {}
            this.newPasswordInput.token = this.$route.query.token
        },
        watch: {
            newPasswordMessage(message) {
                let that = this;
                if(message) {
                    this.$swal({
                        icon: 'success',
                        title: message
                    })
                    setTimeout(() => {
                        that.newPasswordMessage = '';
                    }, 3000);
                }
            },
            loading(loading) {
                if(this.newPasswordLoader && loading === false) {
                    this.newPasswordLoader.hide();
                    this.newPasswordLoader = null;
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
            newPasswordMessage: {
                get: function () {
                    return this.$store.getters['customerStore/getResetPasswordMessage'];
                },
                set: function (message = '') {
                    this.$store.commit('customerStore/setResetPasswordMessage', message);
                }
            },
        },
        methods: {
            buyerNewPassword() {
                this.$Progress.start()
                if(!this.validateNewPassword()) return;
                this.newPasswordLoader = this.inlineLoader({ container: this.$refs.newPasswordButton })
                let formData = this.newPasswordInput
                this.$store.dispatch('customerStore/sendNewPassword', formData).then((response)=>{
                    // if(response)
                    //     this.$router.push({name:'customer-login'})
                });
            },
            validateNewPassword() {
                let isValid = true;

                const constraints = {
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

                const newPasswordInputErrors = validate(this.$data.newPasswordInput, constraints);
                if (newPasswordInputErrors) {
                    this.$Progress.fail()
                    this.formErrors = newPasswordInputErrors
                    isValid = false;
                }
                return isValid;
            },
        }
    }
</script>
