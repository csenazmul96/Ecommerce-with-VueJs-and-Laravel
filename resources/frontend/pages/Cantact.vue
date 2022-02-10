<template>
    <div class="contact_area contact_us">
        <div class="title">
            <h1>Contact Us</h1>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="map">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3307.102827031223!2d-118.28047968448048!3d34.01557142710854!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80c2c9190da9f733%3A0x5ad2942c53b8f233!2sHologram%20Nails%20Inc.!5e0!3m2!1sen!2sus!4v1631510235987!5m2!1sen!2sus" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <div class="contact_us-form clearfix">
                        <form>
                            <div class="row">
                                <div class="col-sm-6 ">
                                    <div class="contact_input">
                                        <input type="text" class="form-control" id="Name" placeholder="YOUR NAME" v-model="form.name">
                                        <v-errors :errors="errorFor('name')" ></v-errors>
                                    </div>
                                </div>
                                <div class="col-sm-6 ">
                                    <div class="contact_input">
                                        <input type="email" class="form-control" id="Email" placeholder="EMAIL" v-model="form.email">
                                        <v-errors :errors="errorFor('email')" ></v-errors>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="contact_input">
                                        <input type="text" class="form-control" id="Subject" placeholder="SUBJECT" v-model="form.subject">
                                        <v-errors :errors="errorFor('subject')" ></v-errors>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="contact_input">
                                        <textarea class="form-control" rows="8" id="Message" placeholder="MESSAGE" v-model="form.message"></textarea>
                                        <v-errors :errors="errorFor('message')" ></v-errors>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="contact_input">
                                        <vue-recaptcha
                                            :sitekey="recaptchaKey"
                                            :loadRecaptchaScript="true"
                                            ref="recaptcha"
                                            @verify="onCaptchaVerified"
                                            type="invisible">
                                        </vue-recaptcha>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <button type="button" id="contact_submit" class="btn btn_common" @click="submitForm">SEND YOUR REQUEST</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class=" col-md-6 ">
                    <div class="address-area">
                        <div class="row">
<!--                            <div class="col-md-6">-->
<!--                                <div class="address-details">-->
<!--                                    <span>Phone :</span>-->
<!--                                    <span><i class="fa  fa-phone"></i> +1 714 512 0305</span>-->
<!--                                    <span><i class="fa  fa-fax"></i> +1 714 512 0305</span>-->
<!--                                </div>-->
<!--                            </div>-->
                            <div class="col-md-6">
                                <div class="address-details">
                                    <span>Email :</span>
                                    <span>
                                        <i class="fa fa-envelope"></i>
                                        <a href="mailto:info@shophologram.com">info@shophologram.com</a>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="view-location">
                            <span>Address :</span>
                            <span><i class="fa fa-map-marker"></i> 3761 S Hill St #1, Los Angeles, CA 90007, USA</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
<script>
import validationErrors from "../../frontend/helpers/validationErrors";
import VueRecaptcha from 'vue-recaptcha';
import pageStore from "./pageStore";
export default {
    name:'StaticPage',
    mixins: [validationErrors],
    components: { VueRecaptcha },
    data(){
        return {
            recaptchaKey: process.env.MIX_RECAPTCHA_SITE_KEY,
            form: {
                name: null,
                email: null,
                message: null,
                subject:null,
                recaptcha: null,
            },
        }
    },
    metaInfo(){
        return {
            title: this.staticPage ? this.staticPage.metas.title : 'Home - Hologram',
        }
    },
    beforeCreate() {
        if (!(this.$store && this.$store.state && this.$store.state['pageStore'])) {
            this.$store.registerModule("pageStore", pageStore);
        }
    },
    computed:{
        staticPage:{
            get: function () {
                return this.$store.getters['pageStore/getStaticPage']
            },
            set: function (staticPage = null) {
                this.$store.commit('pageStore/setStaticPage', staticPage);
            }
        },
    },
    mounted() {
        this.GetStaticPage();
    },
    methods: {
        GetStaticPage(){
            this.$set(this, 'staticPage', null);
            this.$store.dispatch('pageStore/staticPage', 3);
        },
        onCaptchaVerified: function (recaptchaToken) {
            this.form.recaptcha = recaptchaToken
        },
        resetRecaptcha() {
            this.$refs.recaptcha.reset() // Direct call reset method
        },
        submitForm(){
            this.errors = null;
            this.message = null;

            axios.post('/api/v1/contact/form', this.form)
                .then((response) => {
                    this.form.name = null;
                    this.form.email = null;
                    this.form. message = null;
                    this.form. subject = null;
                    this.resetRecaptcha();
                    this.$swal({
                        icon: 'success',
                        title: 'Message Send Success.'
                    })
                })
                .catch((err) => {
                    this.errors = err.response.data.errors
                    this.$swal({
                        icon: 'error',
                        title: 'Message Send Fail.'
                    })
                })

        }
    },
}
</script>
