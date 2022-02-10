<template>
     <div>
        <div class="news_letter_top" v-if="privacyNotices.length && this.$route.name === 'home'">
            <div class="inner_wrap">

                <div class="inner" v-for="(notice, i) in privacyNotices">
                    <h2>{{ notice.name }}</h2>
                    <p>{{ notice.description}}</p>
                </div>

            </div>
        </div>
        <section class="footer_newsletter_area" v-if="newsletters">
            <div class="footer_newsletter_title">
                <h2>{{ newsletters.heading }}</h2>
                <p>{{ newsletters.description }}</p>
            </div>
            <div class="footer_newsletter_form">
                <input type="text" class="form-control" placeholder="Please enter your email address" v-model="newsletterEmail">
                <button class="btn_common" @click.prevent="addNewsletter">SUBMIT</button>
            </div>
            <small style="color:red" v-if="newsletterError">{{newsletterError}}</small>
        </section>
     </div>
</template>
<script>
    import pageStore from "../pages/pageStore";
    export default {
        name:'newsletter',
        data() {
            return {
                newsletterEmail: '',
                newsletterError: '',
                privacyNotices:[],
                newsletters:[],
            }
        },
        beforeCreate() {
        },
        created() {
            axios.get('/api/v1/get/privacy-notice').then((response) => {this.privacyNotices = response.data;});
            axios.get('/api/v1/get/news-letter').then((response) => {this.newsletters = response.data;});
        },
        beforeDestroy() {
            if (!(this.$store && this.$store.state && this.$store.state['pageStore'])) {
                this.$store.registerModule("pageStore", pageStore);
            }
        },
        watch: {
            loading(loading) {
                if(loading === false) this.$Progress.finish();
            },
        },
        computed:{
            loading() {
                return this.$store.getters['defaultStore/getLoading'];
            },

        },
        methods:{
            async addNewsletter() {
                let that = this;
                that.newsletterError = '';
                that.$set(that, 'newsletterError', '')
                if(!that.validateEmail(that.newsletterEmail)){
                    that.newsletterError = 'Please insert a valid email.';
                    this.$swal({
                        icon: 'error',
                        title: that.newsletterError
                    })
                    return;
                }
                let formData = {
                    email : that.newsletterEmail,
                }
                await that.$store.dispatch('defaultStore/addNewsletter',  formData)
                .then((response)=>{
                    that.newsletterError = '';
                    that.newsletterEmail = '';
                })
            },
            validateEmail(email) {
                const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(String(email).toLowerCase());
            },
        },
    }
</script>
