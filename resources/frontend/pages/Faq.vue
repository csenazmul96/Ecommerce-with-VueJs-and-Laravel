<template>
    <div class="faq_area">
        <div class="container">
            <div class="row">
                <div class="col-md-12" style="min-height: 60vh">
                    <div class="title">
                       <h1>Freequently Asked Question</h1>
                    </div>
                    <div class="faq_inner_wrap" v-if="showContent && faqs.length">
                        <div class="faq_inner" v-for="faq in faqs">
                            <div class="heading">
                                <h2>{{ faq.question }}</h2>
                            </div>
                            <div class="content">
                                <p>{{ faq.answer }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="faq_inner_wrap" v-if="showContent && !faqs.length">
                        <p>No content yet!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import pageStore from "./pageStore";
export default {
    name:'StaticPage',
    data(){
        return {
            faqs: [],
            showContent: false
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
    created() {
        this.GetStaticPage();
    },
    methods: {
        GetStaticPage(){
            axios.get('/api/v1/faq/content')
                .then((response) => {
                    this.faqs = response.data;
                }).finally(()=>this.showContent = true)
        },

    },
}
</script>
