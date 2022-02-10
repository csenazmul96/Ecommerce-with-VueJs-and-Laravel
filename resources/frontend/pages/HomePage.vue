<template>
    <div>
        <div class="wcmodel modal open_modal" data-modal="wcm" v-if="modalMsg">
            <div class="modal_overlay" data-modal-close="wcm"></div>
            <div class="welcome_modal_wrapper">
                <div class="modal_inner modal_600p">
                    <span class="close_modal" data-modal-close="wcm" @click="welcomeModalClose"></span>
                    <div v-html="modalMsg"></div>
                </div>
            </div>
        </div>

        <section class="show_mobile banner_wrap" v-if="mobileSider">
            <carousel :autoplay="mobileSider.length > 1 ? true : false" :loop="true" :dots="false" :nav="false" :margin="5" :items="1" v-if="mobileSider.length > 0" >
                <div class="banner_inner full_width" v-for="(value, i) in mobileSider" :key="'mobile'+i">
                    <a :href="value.link"><img alt="banner | Hologram" class="img-fluid" :src="value.image"></a>
                </div>
            </carousel>
            <div class="ocean_wrap">
                <div class="ocean">
                    <div class="wave"></div>
                    <div class="wave"></div>
                </div>
            </div>
        </section>

        <section class="show_desktop banner_wrap" v-if="desktopSider">
            <carousel :autoplay="desktopSider.length > 1 ? true : false" :loop="true" :dots="false" :nav="false" :margin="5" :items="1" v-if="desktopSider.length > 0" >
                <div class="banner_inner full_width" v-for="(value, i) in desktopSider" :key="'des'+i">
                    <a :href="value.link"><img alt="banner | Hologram" class="img-fluid" :src="value.image"></a>
                </div>
            </carousel>
            <div class="ocean_wrap">
                <div class="ocean">
                    <div class="wave"></div>
                    <div class="wave"></div>
                </div>
            </div>
        </section>

        <section class="home_polish" v-if="featureWidgets && featureWidgets.length">
            <div class="inner">
                <h1 v-if="homePage && homePage.sectionHeadings">{{ sectionName('home_first_custom_section').heading }}</h1>
                <div class="home_polish_wrap show_desktop">
                    <div class="home_polish_row">
                        <div class="home_polish_inner" v-for="(banner, i) in featureWidgets" :key="'banner_'+i">
                            <a :href="banner.link">
                                <img alt="Widgets | Hologram" class="img-fluid" :src="banner.image" />
                            </a>
                            <h2>{{ banner.name }}</h2>
                            <p>{{ banner.description }}</p>
                        </div>
                    </div>
                </div>
                <div class="home_polish_mobile show_mobile">
                    <carousel :autoplay="false" :loop="true" :stagePadding="80" :dots="false" :margin="10" v-if="desktopSider.length > 0" :responsive="{0:{items:1,nav:false},768:{items:2,nav:false}}">
                        <div class="inner_mobile" v-for="(banner, i) in featureWidgets" :key="'banner_'+i">
                            <a :href="banner.link">
                                <img alt="Widgets | Hologram" class="img-fluid" :src="banner.image" />
                            </a>
                            <h2>{{ banner.name }}</h2>
                            <p>{{ banner.description }}</p>
                        </div>
                    </carousel>
                </div>
            </div>
        </section>

        <section class="home_our_picks" v-if="homePage && homePage.latestItems">
            <h1 v-if="homePage && homePage.sectionHeadings">{{ sectionName('new_arrival_section').heading }}</h1>
            <div class="h_our_picks_wrap">
                <div class="h_our_picks_row">
                    <productComponent v-for="(product, productKey) in homePage.latestItems" :key="'product_' + productKey"  :product="product" :defaultImage="defaultImage"></productComponent>
                </div>
            </div>
        </section>

        <section class="home_polish fabulous_five" v-if="WidgetsBottoms && WidgetsBottoms.length">
            <div class="inner">
                <h1 v-if="homePage && homePage.sectionHeadings">{{ sectionName('home_second_custom_section').heading }}</h1>
                <div class="home_polish_wrap show_desktop">
                    <div class="home_polish_row">
                        <div class="home_polish_inner" v-for="(banner, i) in WidgetsBottoms">
                            <a :href="banner.link">
                                <img alt="banner | Hologram" class="img-fluid" :src="banner.image" />
                            </a>
                            <h2>{{ banner.name }}</h2>
                            <p>{{ banner.description }}</p>
                        </div>
                    </div>
                </div>
                <div class="home_polish_mobile show_mobile">
                    <carousel :autoplay="false" :loop="true" :stagePadding="80" :dots="false"  :margin="10"  v-if="desktopSider.length > 0" :responsive="{0:{items:1,nav:false},768:{items:2,nav:false}}">
                        <div class="inner_mobile" v-for="(banner, i) in WidgetsBottoms">
                            <a :href="banner.link">
                                <img alt="banner | Hologram"  class="img-fluid" :src="banner.image" />
                            </a>
                            <h2>{{ banner.name }}</h2>
                            <p>{{ banner.description }}</p>
                        </div>
                    </carousel>
                </div>
            </div>
        </section>

        <div v-if="homePage" v-html="homePage.sectionOne && homePage.sectionOne.content ? homePage.sectionOne.content : ''"></div>

        <section class="our_values_area" v-if="homePage">
            <div v-html="homePage.sectionThree && homePage.sectionThree.content ? homePage.sectionThree.content : ''"></div>
        </section>
        <section class="instagram_area show_desktop"  v-if="instagrams.length">
            <h1>Instagram</h1>
            <div class="instagram_inner_wrap ">
                <div class="col" v-for="(insta, i) in instagrams" :key="'insta_desktop_'+i">
                    <div class="instagram_inner" >
                        <a :href="insta.permalink" target="_blank">
                            <template v-if="!insta.children">
                                <img v-if="insta.media_type === 'IMAGE'" :src="insta.media_url" :alt="insta.caption" class="img-fluid">
                                <video v-else id='video' loop muted preload="metadata" width="100%" height="100%" class="embed-responsive-item" autoplay playsinline>
                                    <source :src="insta.media_url">
                                </video>
                            </template>
                            <template v-else>
                                <img v-if="insta.children && insta.children.data.length && insta.children.data[0].media_type === 'IMAGE'" :src="insta.children.data[0].media_url"  class="img-fluid">
                                <video v-else id='video' loop muted preload="metadata" width="100%" height="100%" class="embed-responsive-item" autoplay playsinline>
                                    <source :src="insta.children.data[0].media_url">
                                </video>
                            </template>
                            <div class="inner">
                                <span><i class="lni lni-instagram"></i></span>
                            </div>
                        </a>
                    </div>
                </div>

            </div>
        </section>

        <section class="instagram_area show_mobile" v-if="instagrams.length">
            <h1 v-if="homePage && homePage.sectionHeadings">{{ sectionName('instagram_section').heading }}</h1>
            <div class="insta_slider" style="width: 100%;">
                <carousel :autoplay="false" :loop="true" :dots="false" :responsive="{0:{items:2,nav:false , margin : 10},768:{items:3,nav:false, margin : 10}, 1025:{items:5,nav:false, margin : 40}}">
                    <div class="instagram_inner" v-for="(insta, i) in instagrams" :key="'insta_'+i">
                        <a :href="insta.permalink" target="_blank">
                            <template v-if="!insta.children">
                                <img v-if="insta.media_type === 'IMAGE'" :src="insta.media_url" :alt="insta.caption" class="img-fluid">
                                <video v-else id='video' loop muted preload="metadata" width="100%" height="100%" class="embed-responsive-item" autoplay playsinline>
                                    <source :src="insta.media_url">
                                </video>
                            </template>
                            <template v-else>
                                <img v-if="insta.children && insta.children.data.length && insta.children.data[0].media_type === 'IMAGE'" :src="insta.children.data[0].media_url"  class="img-fluid">
                                <video v-else id='video' loop muted preload="metadata" width="100%" height="100%" class="embed-responsive-item" autoplay playsinline>
                                    <source :src="insta.children.data[0].media_url">
                                </video>
                            </template>
                            <div class="inner">
                                <span><i class="lni lni-instagram"></i></span>
                            </div>
                        </a>
                    </div>
                </carousel>
            </div>
        </section>

        <div class="modal fade" id="modalWelcome">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modalWelcome">&times;</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import pageStore from './pageStore'
import VueSlickCarousel from 'vue-slick-carousel'
// import 'vue-slick-carousel/dist/vue-slick-carousel.css'
import privacyNotice from '../sections/PrivacyNotice';
// optional style for arrows & dots
// import 'vue-slick-carousel/dist/vue-slick-carousel-theme.css'
import carousel from 'vue-owl-carousel/src/Carousel'
import mixins from "../helpers/mixins";
import productComponent from '../ecommerce/components/Product'
export default {
    name:'homePage',
    mixins: [mixins],
    filters: {
        doubleSlashFilter: function (value) {
            return value.replace("//", "/");
        },
        round: function (value, decimals) {
            if(!value) {
                value = 0;
            }

            if(!decimals) {
                decimals = 0;
            }
            var value = Number(value);
            value = value.toFixed(decimals);

            return value;
        }
    },
    data(){
        return {
            sliderSettings: {
                "dots": false,
                "infinite": false,
                "arrows":true,
                "lazyLoad": "progressive",
                "focusOnSelect": true,
                "speed": 600,
                "slidesToShow": 9,
                "slidesToScroll": 9,
                "initialSlide": 0,
                "responsive": [
                    {
                        "breakpoint": 1024,
                        "settings": {
                            "slidesToShow": 9,
                            "slidesToScroll": 9,
                        }
                    },
                    {
                        "breakpoint": 600,
                        "settings": {
                            "slidesToShow": 9,
                            "slidesToScroll": 9,
                        }
                    },
                    {
                        "breakpoint": 480,
                        "settings": {
                            "slidesToShow": 9,
                            "slidesToScroll": 9
                        }
                    }
                ]
            },
            instasliderSettings: {
                "dots": false,
                "infinite": false,
                "arrows":true,
                "lazyLoad": "progressive",
                "focusOnSelect": true,
                "speed": 600,
                "slidesToShow": 5,
                "slidesToScroll": 5,
                "mouseDrag": false,
                "initialSlide": 0,
                "responsive": [
                    {
                        "breakpoint": 1024,
                        "settings": {
                            "slidesToShow": 5,
                            "slidesToScroll": 5,
                        }
                    },
                    {
                        "breakpoint": 600,
                        "settings": {
                            "slidesToShow": 3,
                            "slidesToScroll": 3,
                        }
                    },
                    {
                        "breakpoint": 480,
                        "settings": {
                            "slidesToShow":2,
                            "slidesToScroll": 2
                        }
                    }
                ]
            },
            desktopSider:[],
            mobileSider:[],
            featureWidgets:[],
            WidgetsBottoms:[],
            instagrams:[],
            sliderLoad: false,
        }
    },
    components: {
        VueSlickCarousel:VueSlickCarousel,
        carousel:carousel,
        privacyNotice:privacyNotice,
        productComponent: productComponent,
    },
    metaInfo(){
        return {
            title: "Hologram Gel Nail Polish, Nail Care & Nail Art | Hologram",
        }
    },
    beforeCreate() {
        if (!(this.$store && this.$store.state && this.$store.state['pageStore'])) {
            this.$store.registerModule("pageStore", pageStore);
        }
    },
    beforeDestroy() {
        // if ((this.$store && this.$store.state && this.$store.state['pageStore'])) {
        //       this.$store.unregisterModule('pageStore');
        // }
    },
    created(){
        axios.get('/api/v1/get/sliders').then((response) => {
            this.desktopSider = response.data.desktop
            this.mobileSider = response.data.mobile

        }).finally(()=>{
            this.getHomePage();
            this.getInstagramContent();
        });
    },
    watch: {
        successMessage(successMessage) {
            if (successMessage) {
                this.$swal({
                    icon: 'success',
                    title: successMessage
                })
                this.$store.commit('pageStore/setSuccessMessage', '');
            }
        },
        errorMessage(errorMessage) {
            if (errorMessage) {
                this.$swal({
                    icon: 'error',
                    title: errorMessage
                })
                this.$store.commit('pageStore/setErrorMessage', '');
            }
        },
        $route(to,from){
            // this.getInstagramContent();
            // this.getHomePage();
        },
        sliderItems() {
            this.sliderSettings.slidesToShow = 1;
            this.$nextTick(() => {
                this.sliderSettings.slidesToShow = 4;
            })
            this.instasliderSettings.slidesToShow = 5;
            this.$nextTick(() => {
                this.instasliderSettings.slidesToShow = 5;
            })
        },
        modalMsg(msg) {
            if(msg != null) {
                /*console.log(msg);
                console.log($('#modalWelcome'))
                if($('#modalWelcome')) {
                    $('#modalWelcome').modal('show');
                }*/
            }
        }
    },
    computed:{
        defaultImage:{
            get: function () {
                return this.$store.getters['pageStore/getDefaultImage']
            },
            set: function (defaultImage = null) {
                this.$store.commit('pageStore/setDefaultImage', defaultImage);
            }
        },
        successMessage() {
            return this.$store.getters['pageStore/getSuccessMessage'];
        },
        errorMessage() {
            return this.$store.getters['pageStore/getErrorMessage'];
        },
        homePage:{
            get: function () {
                return this.$store.getters['pageStore/getHomePage']
            },
            set: function (homePage = null) {
                this.$store.commit('pageStore/setHomePage');
            }
        },
        modalMsg:{
            get: function () {
                return this.$store.getters['pageStore/getWelcomeModal']
            },
            set: function (modalMsg = null) {
                this.$store.commit('pageStore/setWelcomeModal', modalMsg);
            }
        },
        sliderItems(){
            return this.homePage ? this.homePage.latestItems : [];
        }
    },
    methods:{
        getHomePage(){
            this.$set(this, 'homePage', null);
            this.$store.dispatch('pageStore/homePage')
            axios.get('/api/v1/get/feature/widget').then((response) => {
                this.featureWidgets = response.data.top
                this.WidgetsBottoms = response.data.bottom
            });
        },
        getInstagramContent(){
            axios.get('/api/v1/get/instagram').then((response) => {
                this.instagrams = response.data.data;

                if(this.instagrams.length)
                    this.instagrams = this.instagrams.slice(0, 5)
            });
        },
        welcomeModalClose() {
            $('body').css('padding-right', '0px');
            $('.header_area').css('margin-right', '0px');
            $('body').removeClass('model_open');
            $('.wcmodel').removeClass('open_modal');
        },
        sectionName(section_name){
            let sections = this.homePage ? this.homePage.sectionHeadings : [];
            return sections.find( (data) => data.section_name === `${section_name}`) ;
        }
    },
}
</script>

