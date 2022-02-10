<template>
    <div class="product_content_inner">
        <div class="product_inner_wrap">
            <div class="product_img_wrap">
                <router-link :to="{ name: 'single-product', params: { parent: product.slug }}">
                    <img
                        v-lazy="{
                            error: product.images.length ? imagePathJpg(product.images[0]) : ('/' + defaultImage.value),
                            src: product.images.length ? imagePathWeb(product.images[0]) : ('/' + defaultImage.value)
                        }"
                        :alt="productAltTag(product.name)"
                        class="img-fluid p_img"
                        @mouseenter="videoPlayer(product.video)">


                    <transition name="fade">
                        <div class="product-list-video"
                             v-if="showVideo && product.video"
                             @mouseleave="showVideo=false">
                            <video-player width="100%"
                                          height="100%"
                                          @videoPlayEvent="videoPlayEvent"
                                          :options="videoOptions" />
                            <div class="video_player_spin" v-if="showLoader"><i class="fas fa-circle-notch fa-spin"></i></div>
                        </div>
                    </transition>
                </router-link>
            </div>
            <div class="product_details_text">
                <h2 class="product_title">
                    <router-link :to="{ name: 'single-product', params: { parent: product.slug }}">
                        {{product && product.name ? product.name : 'No Specific Name'}}
                    </router-link>
                </h2>
                <p class="product_price">
                    <span>{{product.style_no}} </span> | <span>USD${{product.price | round(2)}}</span>
                </p>
            </div>
        </div>
    </div>
</template>
<script>
import mixins from '../../helpers/mixins'
import VideoPlayer from "./../components/VideoPlayer";

export default {
    name:'productComponent',
    mixins: [mixins],
    components: {VideoPlayer},
    data() {
        return {
            showVideo: false,
            showLoader: true,
            videoOptions: {
                autoplay: true,
                controls: false,
                muted: true,
                loop: true,
                aspectRatio: '1:1',
                sources: [
                    {
                        src: null,
                        type: "video/mp4"
                    }
                ]
            },
        }
    },
    filters: {
        capitalize: function (value) {
            if (!value) return ''
            value = value.toString()
            return value.charAt(0).toUpperCase() + value.slice(1)
        },
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
    props: {
        product: {
            type: Object,
            required: true
        },
        defaultImage: {
            type: Object,
            required: true
        }
    },
    methods:{
        imagePathWeb(path){
                return path.compressed_img_webp;
        },
        imagePathJpg(path){
                return path.compressed_img_jpg;
        },
        productAltTag(name){
            if(this.$route.params.parent === 'sets'){
                return name + ' set'
            }else{
                return name + ' gel polish';
            }
        },
        videoPlayer(video){
            this.videoOptions.sources[0].src = video
            this.showVideo = true
        },
        videoPlayEvent(contact) {
            this.showLoader = false
        },
        productSingle(dd){
            this.$store.dispatch('ecommerceStore/singleProduct', dd.slug).then((responce)=>{
                this.$router.push({name:'single-product', params:{ parent:dd.slug }})
            });
        }
    }
}
</script>

