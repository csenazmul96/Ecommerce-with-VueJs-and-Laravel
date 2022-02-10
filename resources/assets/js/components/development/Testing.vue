<template>
    <span class="masterview">
        <section class="product_single_area">
            <div style="width: 500px; height: 750px;" class="align_center">
                <VueSlickCarousel :arrows="true" :dots="true">
                    <div><img :src="'/dev_testing/images/image01.jpg'" height="750" width="500" alt="Image 1"></div>
                    <div><img :src="'/dev_testing/images/image03.jpg'" height="750" width="500" alt="Image 2"></div>
                    <div><img :src="'/dev_testing/images/image03.jpg'" height="750" width="500" alt="Image 3"></div>
                    <div><img :src="'/dev_testing/images/image04.jpg'" height="750" width="500" alt="Image 4"></div>
                </VueSlickCarousel>
            </div>
        </section>
    </span>
</template>

<script>
    //import { Carousel, Slide } from 'vue-carousel';
    import VueSlickCarousel from 'vue-slick-carousel'
    import 'vue-slick-carousel/dist/vue-slick-carousel.css'
    // optional style for arrows & dots
    import 'vue-slick-carousel/dist/vue-slick-carousel-theme.css'

    export default {
        data(){
            return {
                settings: {
                    "dots": true,
                    "dotsClass": "slick-dots custom-dot-class",
                    "edgeFriction": 0.35,
                    "infinite": false,
                    "speed": 500,
                    "slidesToShow": 1,
                    "slidesToScroll": 1
                },
            }
        },
        components:{
            VueSlickCarousel,
		    //Carousel,
		    //Slide
	  	},
        methods:{
            
        },
    }
</script>