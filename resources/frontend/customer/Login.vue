<template>
    <!-- ============================
        START LOGIN SECTION
    ============================== -->
    <section class="login_page">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="login_inner">
                        <h2>Login Here</h2>
                        <div class="header_account_inner">
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

                            <p class="text-center tt_u mb-0">or</p>
                            <div id="gSignInWrapper">
                                <button id="login_page_google" class="btn_google mb_5"><span><i class="fab fa-google"></i></span> <span>sign in with Google</span></button>
                            </div>
                            <button class="btn_facebook" @click="logInWithFacebook"><span><i class="fab fa-facebook-f"></i></span> <span>sign in with facebook</span></button>
                            <div class="create_acc_btn">
                                <router-link :to="{ name: 'customer-register'}" class="btn btn_transparent"> create an account </router-link>
                            </div>
                            <div class="d_flex_center mt_10 mb_10">
                                <router-link :to="{ name: 'customer-passwordReset'}" class="text-center td_underline tt_u"> Forgot Password </router-link>
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
    import mixins from '../helpers/mixins'
    export default {
        name:'customerLogin',
        mixins: [mixins],
        data() {
            return {
                buyerInput: {
                    email: '',
                    password: '',
                },
                loginLoader: null,
            }
        },
        mounted() {
            this.formErrors = {}
            let tempThis = this;
            gapi.load('auth2', function(){
                // Retrieve the singleton for the GoogleAuth library and set up the client.
                tempThis.googleAuth = gapi.auth2.init({
                    client_id: process.env.MIX_GOOGLE_CLIENT_KEY,
                    cookiepolicy: 'single_host_origin',
                });
                tempThis.attachGoogleSignIn(document.getElementById('login_page_google'));
            });
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
        watch: {
            loading(loading) {
                if(this.loginLoader && loading === false) {
                    this.loginLoader.hide();
                    this.loginLoader = null;
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
                    this.buyerLogin();
                }
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
        }
    }
</script>
