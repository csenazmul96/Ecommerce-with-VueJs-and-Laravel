<template>
    <!-- ============================
        START USER SECTION
    ============================== -->
    <div class="show_from_right" id="muser">
        <div class="header_menu_inner">
            <div class="header_account">
                <div class="header_account_inner" v-if="!isLoggedIn && showModalType == 'login'">
                    <div class="form_inline">
                        <label class="required">Email</label>
                        <input type="text" class="form-control" placeholder="Email Address" v-model="buyerInput.email" @keyup="checkForEnter">
                        <small v-for="(formError, errorIndex) in formErrors['email']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></small>
                    </div>
                    <div class="form_inline">
                        <label for="staticEmail">Password</label>
                        <input type="password" class="form-control" placeholder="Password" v-model="buyerInput.password" @keyup="checkForEnter">
                        <small v-for="(formError, errorIndex) in formErrors['password']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></small>
                    </div>
                    <button class="btn_common" @click.prevent="buyerLogin" :disabled="loginLoader" ref="loginButton">login</button>
                    <div class="d_flex_center mt_10 mb_10">
                        <a href="javascript:void(0)" class="text-center td_underline tt_u" @click="changeModal('forgetPassword')">Forgot Password</a>
                    </div>
                    <p  class="text-center tt_u mb_10">or</p>
                    <button class="btn_google mb_5" id="btn_google_mbl"><span><i class="fab fa-google"></i></span> <span>sign in with Google</span></button>
                    <button class="btn_facebook" @click="logInWithFacebook"><span><i class="fab fa-facebook-f"></i></span> <span>sign in with facebook</span></button>
                    <div class="create_acc_btn">
                        <button class="btn_transparent" @click="changeModal('register')">create an account</button>
                    </div>
                </div>
                <div class="header_account_inner" v-else-if="!isLoggedIn && showModalType == 'register'">
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
                    <button class="btn_common" @click.prevent="buyerRegister" :disabled="registerLoader" ref="registerButton">Register</button>
                    <div class="create_acc_btn">
                        <button class="btn_transparent" @click="changeModal('login')">Login</button>
                    </div>
                </div>
                <div class="header_account_inner" v-else-if="!isLoggedIn && showModalType == 'forgetPassword'">
                    <div class="form_inline">
                        <label class="required">Email</label>
                        <input type="text" class="form-control" placeholder="Email Address" v-model="buyerForgetPassword.email" @keyup="checkForEnter">
                        <small v-for="(formError, errorIndex) in formErrors['email']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></small>
                    </div>
                    <p v-if="resetPasswordMessage" class="text-success">{{resetPasswordMessage}}</p>
                    <button class="btn_common" @click.prevent="buyerPasswordReset" :disabled="resetLoader" ref="resetButton">Reset Password</button>
                    <div class="create_acc_btn">
                        <button class="btn_transparent" @click="changeModal('login')">Login</button>
                    </div>
                </div>
                <div class="header_account_inner" v-else>
                    <router-link :to="{ name: 'customer-dashboard'}" class="btn btn_common" >
                        Dashboard
                    </router-link>
                    <hr>
                    <button class="btn_common" @click.prevent="buyerLogout">Log out</button>
                </div>
            </div>
        </div>
        <div class="close_h_menu" @click.prevent="mobCloseMenu">
            <span>Close</span>
        </div>
    </div>
    <!-- ============================
        END USER SECTION
    ============================== -->
</template>
<script>
    import validate from 'validate.js'
    import customerStore from '../../../customer/customerStore'
    import mixins from '../../../helpers/mixins'
    export default {
        name:'headerMobileUserComponent',
        mixins: [mixins],
        data() {
            return {
                buyerForgetPassword: {
                    email: '',
                },
                buyerInput: {
                    email: '',
                    password: '',
                },
                buyerRegisterInput: {
                    first_name: '',
                    last_name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                },
                showModalType: 'login',
                loginLoader: null,
                registerLoader: null,
                resetLoader: null,
                googleAuth: null,
            }
        },
        async beforeCreate() {
            if (!(this.$store && this.$store.state && this.$store.state['customerStore'])) {
                this.$store.registerModule("customerStore", customerStore);
            }

            window.fbAsyncInit = function() {
                FB.init({
                    appId      : process.env.MIX_FACEBOOK_ID,
                    cookie     : true,
                    xfbml      : true,
                    version    : 'v11.0'
                });

                FB.AppEvents.logPageView();
            };

            (function(d, s, id){
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) {return;}
                js = d.createElement(s); js.id = id;
                js.src = "https://connect.facebook.net/en_US/sdk.js";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        },
        mounted(){
            this.headerPreloads();

            let tempThis = this;

            gapi.load('auth2', function(){
                // Retrieve the singleton for the GoogleAuth library and set up the client.
                tempThis.googleAuth = gapi.auth2.init({
                    client_id: process.env.MIX_GOOGLE_CLIENT_KEY,
                    cookiepolicy: 'single_host_origin',
                });
                tempThis.attachGoogleSignIn(document.getElementById('btn_google_mbl'));
            });
        },
        watch: {
            loading(loading) {
                if(this.loginLoader && loading === false) {
                    this.loginLoader.hide();
                    this.loginLoader = null;
                }
                if(this.registerLoader && loading === false) {
                    this.registerLoader.hide();
                    this.registerLoader = null;
                }
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
            isLoggedIn() {
                return this.$store.getters['customerStore/getIsLoggedIn'];
            },
            resetPasswordMessage() {
                return this.$store.getters['customerStore/getResetPasswordMessage'];
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
                    switch (this.showModalType) {
                        case 'login':
                            this.buyerLogin();
                            break;
                        case 'register':
                            this.buyerRegister();
                            break;
                        case 'forgetPassword':
                            this.buyerPasswordReset();
                            break;
                    }
                }
            },
            headerPreloads(){
                // this.$store.dispatch('customerStore/checkCustomer');
            },
            changeModal(modalType) {
                this.showModalType = modalType;
                this.formErrors = {}
            },
            buyerRegister() {
                this.$Progress.start()
                if(!this.validateRegister()) return;
                this.registerLoader = this.inlineLoader({ container: this.$refs.registerButton })
                let formData = this.buyerRegisterInput
                this.$store.dispatch('customerStore/tryRegister', formData);
            },
            buyerLogin() {
                this.$Progress.start()
                if(!this.validateLogin()) return;
                this.loginLoader = this.inlineLoader({ container: this.$refs.loginButton })
                let formData = {
                    email: this.buyerInput.email,
                    password: this.buyerInput.password,
                }
                this.$store.dispatch('customerStore/tryLogin', formData);
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
            buyerLogout() {
                this.$Progress.start()
                this.$store.dispatch('customerStore/tryLogout');
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
            validateLogin() {
                let isValid = true;

                const constraints = {
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
                        }
                    },
                }

                const buyerInputErrors = validate(this.$data.buyerInput, constraints);
                if (buyerInputErrors) {
                    this.$Progress.fail()
                    this.formErrors = buyerInputErrors
                    isValid = false;
                }

                return isValid;
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
            logInWithFacebook() {
                let formData = {
                    provider: 'facebook',
                    providerId: '566121214536217',
                    firstName: 'Mark',
                    lastName: 'Nam',
                    email: 'mkn102@gmail.com',
                }

                let that = this;

                window.FB.getLoginStatus(function(response) {
                    if (response.authResponse) {
                        that.fetchFacebookUser();
                    } else {
                        window.FB.login(function(response) {
                            if (response.authResponse) {
                                that.fetchFacebookUser();
                            }
                        }, {scope: 'email'});
                    }
                });
            },
            fetchFacebookUser() {
                let that = this;

                window.FB.api('/me?fields=id,name,first_name,last_name,email', function(response) {
                    let formData = {
                        provider: 'facebook',
                        providerId: response.id,
                        firstName: response.first_name,
                        lastName: response.last_name,
                        email: response.email,
                    }

                    that.$store.dispatch('customerStore/trySocialLogin', formData);
                });
            },
            attachGoogleSignIn(element) {
                let googleThis = this;
                this.googleAuth.attachClickHandler(element, {},
                    function(googleUser) {
                        let profile = googleThis.googleAuth.currentUser.get().getBasicProfile();

                        let formData = {
                            provider: 'google',
                            providerId: profile.getId(),
                            firstName: profile.getGivenName(),
                            lastName: profile.getFamilyName(),
                            email: profile.getEmail(),
                        }

                        googleThis.$store.dispatch('customerStore/trySocialLogin', formData);
                    }, function(error) {
                        //alert(JSON.stringify(error, undefined, 2));
                    });
            },
            mobCloseMenu() {
                $('.show_from_left , .show_from_right').removeClass('open_h_menu');
                $('.menu').removeClass('open');
            },
        },
    }
</script>
