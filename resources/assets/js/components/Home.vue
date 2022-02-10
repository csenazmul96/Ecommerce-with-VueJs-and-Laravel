<template>
    <span  id="homepage_content">
        <section v-if="MainContent.mainslider" class="banner_area">
            <div v-for="(slider, index) in MainContent.mainslider" :key="index" class="banner_inner">
                <router-link :to="`/${slider.url}`"><img :src="slider.image_path" alt="" class="img-fluid"></router-link>
                <div class="banner_text">
                    <h2><router-link :to="`/${slider.url}`">{{slider.details}}</router-link> </h2>
                </div>
            </div>
        </section>
        <section v-if="MainContent.ourpicks" class="home_our_picks">
            <div class="h_our_picks_wrap">
                <div v-for="(ourpick,index) in MainContent.ourpicks" :key="index" class="h_our_picks_inner">
                    <router-link :to="`/${ourpick.url}`">
                        <img :src="ourpick.image_path" alt="" class="img-fluid">
                        <h2>{{ourpick.title}}</h2>
                        <p>Shop All</p>
                    </router-link>
                </div>
            </div>
        </section>
        <section class="home_slide_area" v-if="renderComponent">
            <div class="home_slide_wrap">
                <!-- {{NewItemSlider}} -->
                <div id="home_slide" class="owl-carousel owl-theme">
                    <div class="home_slider_inner" v-for="(item,index) in NewItemSlider" :key="index">
                        <div class="home_slider_inner_content">
                            <router-link :to="`single/${item.slug}`">
                                <img :src="item.images[0].compressed_image_path" class="img-fluid" alt="">
                                <h2 v-if="item.name">{{item.name}}</h2>
                                <p v-if="item.brand">{{item.brand.name}} | USD ${{item.price}}</p>
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
        </section>
         <section class="our_values_area" v-if="MainContent.aboutus">
            <div class="our_values_heading">
                <div class="n_margin">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="our_values_title">
                                    <h2>We sell clothing with values</h2>
                                    <p>SHOP CLOTHES WITH THE VALUES THAT ARE IMPORTANT TO YOU, VIA OUR 8 WELL MADE CLOTHES VALUES</p>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="o_v_h_content" v-html="MainContent.aboutus.content"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <subscriber></subscriber>
        <div class="footer_hero_area" v-if="MainContent.custom_section" v-html="MainContent.custom_section.content"></div>
    </span>
</template>

<script>
import { Carousel, Slide } from 'vue-carousel';

export default {
    name:'Header',
    components: {
        subscriber: () => import(/* webpackChunkName: "js/subscriber" */ '../components/default/Subscription.vue'),
        Carousel,
        Slide,
    },
    metaInfo() {
        return {
            title: this.metaData.pageTitle,
            meta: [
                { charset: this.metaData.charset },
                { name: 'title', content: this.metaData.title },
                { name: 'viewport', content: this.metaData.viewport },
                { name: 'description', content: this.metaData.description }
            ]
        }
    },
    data(){
            return{
                id:'',
                newinslider:[],
                NewItemSlider: [],
                renderComponent: true,
            }
    },
    mounted(){
        this.NewArrivalDispatch();
        this.$store.dispatch('HomePageDefaultContent');
        this.slider();
    },
    computed:{
        metaData() {
            return {
                pageTitle: 'Hologram - $1.50 Store',
                charset: 'utf-8',
                viewport: 'width=device-width, initial-scale=1, shrink-to-fit=no',
                title: 'Hologram retail cosmetics web store',
                description: 'Hologram is a retail cosmetics web store where you can purchase any cosmetics product you need within $1.50 expense.'
            }
        },
        MainContent(){
            return this.$store.getters.HomePageContent
        },
    },
    methods:{
        NewArrivalDispatch(context){
                axios.get('/api/v1/new-in')
                .then((response)=>{
                    this.NewItemSlider = response.data.newin
                })
        },
        newcall(){
            var home_slide = jQuery("#home_slide");
            home_slide.owlCarousel({
                  items: 4,
                  loop: true,
                  margin: 10,
                  autoplay: false,
                  autoplayTimeout: 1500,
                  autoplayHoverPause: true,
                  responsiveClass: true,
                  navigation : false,
                  navText: ['<i class="lni lni-chevron-left"></i>', '<i class="lni lni-chevron-right"></i>'],
                  dots:false,
                  responsive: {
                        0: {
                        items: 1,
                        nav: true
                        },
                        600: {
                        items: 2,
                        nav: false
                        },
                        1000: {
                        items: 4,
                        loop: true,
                        margin: 20
                        }
                  }
                });
            },
            slider(){
                  var vm = this;
                  axios.get('/api/v1/new-in')
                    .then((response)=>{
                        this.NewItemSlider = response.data.newin
                        // Vue.nextTick(function(){
                        //     vm.newcall();
                        // }.bind(vm));
                  })
            },
    },
    watch:{
        $route(to,from){
           this.slider();
            this.NewArrivalDispatch();
        }
    },
    created: function () {
      this.slider();
  }
}



</script>
