<template>
    <!-- ============================
        START LOGIN SECTION
    ============================== -->
    <section class="login_page">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="login_inner">
                        <h2>Create New Account Here</h2>
                        <div class="header_account_inner">
                            <div class="form_inline">
                                <label class="required">First name</label>
                                <input type="text" class="form-control" placeholder="First name" v-model="buyerRegisterInput.first_name" @keyup="checkForEnter">
                                <small v-for="(formError, errorIndex) in formErrors['first_name']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></small>
                            </div>
                            <div class="form_inline">
                                <label class="required">Last name</label>
                                <input type="text" class="form-control" placeholder="Last name" v-model="buyerRegisterInput.last_name" @keyup="checkForEnter">
                                <small v-for="(formError, errorIndex) in formErrors['last_name']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></small>
                            </div>
                            <div class="form_inline">
                                <label class="required">Email</label>
                                <input type="text" class="form-control" placeholder="Email Address" v-model="buyerRegisterInput.email" @keyup="checkForEnter">
                                <small v-for="(formError, errorIndex) in formErrors['email']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></small>
                            </div>
                            <div class="form_inline">
                                <label for="staticEmail">Password</label>
                                <input type="password" class="form-control" placeholder="Password" v-model="buyerRegisterInput.password" @keyup="checkForEnter">
                                <small v-for="(formError, errorIndex) in formErrors['password']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></small>
                            </div>
                            <div class="form_inline">
                                <label for="staticEmail">Confirm Password</label>
                                <input type="password" class="form-control" placeholder="Confirm Password" v-model="buyerRegisterInput.password_confirmation" @keyup="checkForEnter">
                                <small v-for="(formError, errorIndex) in formErrors['password_confirmation']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></small>
                            </div>
                            <button class="btn_common" @click.prevent="buyerRegister" ref="registerButton">Register</button>
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
        name:'customerLogin',
        mixins: [mixins],
        data() {
            return {
                buyerRegisterInput: {
                    first_name: '',
                    last_name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                },
                registerLoader: null,
            }
        },
        mounted() {
            this.formErrors = {}
        },
        watch: {
            loading(loading) {
                if(this.registerLoader && loading === false) {
                    this.registerLoader.hide();
                    this.registerLoader = null;
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
        },
        methods: {
            checkForEnter(event) {
                if (event.keyCode === 13) {
                    event.preventDefault();
                    this.buyerRegister();
                }
            },
            buyerRegister() {
                this.$Progress.start()
                if(!this.validateRegister()) return;
                this.registerLoader = this.inlineLoader({ container: this.$refs.registerButton })
                let formData = this.buyerRegisterInput
                this.$store.dispatch('customerStore/tryRegister', formData);
            },
            validateRegister() {
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

                const buyerRegisterInputErrors = validate(this.$data.buyerRegisterInput, constraints);
                if (buyerRegisterInputErrors) {
                    this.$Progress.fail()
                    this.formErrors = buyerRegisterInputErrors
                    isValid = false;
                }

                return isValid;
            },
        }
    }
</script>
