(window.webpackJsonp=window.webpackJsonp||[]).push([[6],{yWtG:function(t,e,o){"use strict";o.r(e);var s=o("a1gY"),i=o.n(s),n={name:"customerLogin",mixins:[o("ZNft").a],data:function(){return{buyerInput:{email:"",password:""},loginLoader:null}},mounted:function(){this.formErrors={};var t,e,o,s,i,n=this;gapi.load("auth2",(function(){n.googleAuth=gapi.auth2.init({client_id:"459333801333-43g3uo2mapue1tjcdku8rdslvjut2nbe.apps.googleusercontent.com",cookiepolicy:"single_host_origin"}),n.attachGoogleSignIn(document.getElementById("login_page_google"))})),window.fbAsyncInit=function(){FB.init({appId:"566121214536217",cookie:!0,xfbml:!0,version:"v11.0"}),FB.AppEvents.logPageView()},t=document,e="script",o="facebook-jssdk",i=t.getElementsByTagName(e)[0],t.getElementById(o)||((s=t.createElement(e)).id=o,s.src="https://connect.facebook.net/en_US/sdk.js",i.parentNode.insertBefore(s,i))},watch:{loading:function(t){this.loginLoader&&!1===t&&(this.loginLoader.hide(),this.loginLoader=null)}},computed:{loading:function(){return this.$store.getters["customerStore/getLoading"]},formErrors:{get:function(){return this.$store.getters["customerStore/getFormErrors"]},set:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{};this.$store.commit("customerStore/setFormErrors",t)}}},methods:{checkForEnter:function(t){13===t.keyCode&&(t.preventDefault(),this.buyerLogin())},buyerLogin:function(){if(this.$Progress.start(),this.validateLogin()){this.loginLoader=this.inlineLoader({container:this.$refs.loginButton});var t={email:this.buyerInput.email,password:this.buyerInput.password};this.$store.dispatch("customerStore/tryLogin",t)}},validateLogin:function(){var t=!0,e=i()(this.$data.buyerInput,{email:{presence:{allowEmpty:!1,message:"^ Field is required."},email:!0},password:{presence:{allowEmpty:!1,message:"^ Field is required."}}});return e&&(this.$Progress.fail(),this.formErrors=e,t=!1),t},logInWithFacebook:function(){var t=this;window.FB.getLoginStatus((function(e){e.authResponse?t.fetchFacebookUser():window.FB.login((function(e){e.authResponse&&t.fetchFacebookUser()}),{scope:"email"})}))},fetchFacebookUser:function(){var t=this;window.FB.api("/me?fields=id,name,first_name,last_name,email",(function(e){var o={provider:"facebook",providerId:e.id,firstName:e.first_name,lastName:e.last_name,email:e.email};t.$store.dispatch("customerStore/trySocialLogin",o)}))},attachGoogleSignIn:function(t){var e=this;this.googleAuth.attachClickHandler(t,{},(function(t){var o=e.googleAuth.currentUser.get().getBasicProfile(),s={provider:"google",providerId:o.getId(),firstName:o.getGivenName(),lastName:o.getFamilyName(),email:o.getEmail()};e.$store.dispatch("customerStore/trySocialLogin",s)}),(function(t){}))}}},a=o("KHd+"),r=Object(a.a)(n,(function(){var t=this,e=t.$createElement,o=t._self._c||e;return o("section",{staticClass:"login_page"},[o("div",{staticClass:"container"},[o("div",{staticClass:"row"},[o("div",{staticClass:"col-md-12"},[o("div",{staticClass:"login_inner"},[o("h2",[t._v("Login Here")]),t._v(" "),o("div",{staticClass:"header_account_inner"},[o("div",{staticClass:"form_inline"},[o("label",{staticClass:"required"},[t._v("Email")]),t._v(" "),o("input",{directives:[{name:"model",rawName:"v-model",value:t.buyerInput.email,expression:"buyerInput.email"}],staticClass:"form-control",attrs:{type:"text",placeholder:"Email Address"},domProps:{value:t.buyerInput.email},on:{keyup:t.checkForEnter,input:function(e){e.target.composing||t.$set(t.buyerInput,"email",e.target.value)}}}),t._v(" "),t._l(t.formErrors.email,(function(e,s){return o("small",{key:"error_name_"+s},[0==s?o("span",[t._v(t._s(e))]):t._e()])}))],2),t._v(" "),o("div",{staticClass:"form_inline"},[o("label",{attrs:{for:"staticEmail"}},[t._v("Password")]),t._v(" "),o("input",{directives:[{name:"model",rawName:"v-model",value:t.buyerInput.password,expression:"buyerInput.password"}],staticClass:"form-control",attrs:{type:"password",placeholder:"Password"},domProps:{value:t.buyerInput.password},on:{keyup:t.checkForEnter,input:function(e){e.target.composing||t.$set(t.buyerInput,"password",e.target.value)}}}),t._v(" "),t._l(t.formErrors.password,(function(e,s){return o("small",{key:"error_name_"+s},[0==s?o("span",[t._v(t._s(e))]):t._e()])}))],2),t._v(" "),o("button",{ref:"loginButton",staticClass:"btn_common",attrs:{disabled:t.loginLoader},on:{click:function(e){return e.preventDefault(),t.buyerLogin(e)}}},[t._v("login")]),t._v(" "),o("p",{staticClass:"text-center tt_u mb-0"},[t._v("or")]),t._v(" "),t._m(0),t._v(" "),o("button",{staticClass:"btn_facebook",on:{click:t.logInWithFacebook}},[t._m(1),t._v(" "),o("span",[t._v("sign in with facebook")])]),t._v(" "),o("div",{staticClass:"create_acc_btn"},[o("router-link",{staticClass:"btn btn_transparent",attrs:{to:{name:"customer-register"}}},[t._v(" create an account ")])],1),t._v(" "),o("div",{staticClass:"d_flex_center mt_10 mb_10"},[o("router-link",{staticClass:"text-center td_underline tt_u",attrs:{to:{name:"customer-passwordReset"}}},[t._v(" Forgot Password ")])],1)])])])])])])}),[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{attrs:{id:"gSignInWrapper"}},[e("button",{staticClass:"btn_google mb_5",attrs:{id:"login_page_google"}},[e("span",[e("i",{staticClass:"fab fa-google"})]),this._v(" "),e("span",[this._v("sign in with Google")])])])},function(){var t=this.$createElement,e=this._self._c||t;return e("span",[e("i",{staticClass:"fab fa-facebook-f"})])}],!1,null,null,null);e.default=r.exports}}]);