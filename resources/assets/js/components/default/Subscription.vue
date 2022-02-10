<template>
    <section class="footer_newsletter_area">
        <div class="footer_newsletter_title">
            <h2 v-if="MainContent.topnotification" v-html="MainContent.topnotification.value"></h2>
            <p>By signing up to our Well Made Weekly, which will keep your fashion industry cocktail chatter (not to mention your wardrobe) strong.</p>
        </div>
        <div class="footer_newsletter_form">
            <input type="text" class="form-control" placeholder="Please enter your email address">
            <button class="btn_common">Join</button>
        </div>
    </section>
</template>
<script>
export default {
name:'subscription', 
    data(){
        return{
            Subscription: new Form({
                email: '',
                password: '' 
            }), 
        }
    },
    mounted(){   
        this.$store.dispatch('HomePageDefaultContent');   
    },
    computed:{   
        MainContent(){   
            return this.$store.getters.HomePageContent
        }, 
    },
    methods:{

    },
}
</script>