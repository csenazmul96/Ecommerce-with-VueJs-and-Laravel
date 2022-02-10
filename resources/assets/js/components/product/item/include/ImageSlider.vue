<template>
    <div class="product_single_left">
        <Slick v-bind="main_settings" class="carousel">
        </Slick>
        <Slick v-bind="nav_settings" class="carousel">
        </Slick>
    </div>
</template>

<script>

	import Slick from 'vue-slick-carousel'
    import 'vue-slick-carousel/dist/vue-slick-carousel.css'
    import 'vue-slick-carousel/dist/vue-slick-carousel-theme.css'

	export default {
        name: 'image-slider',
        data () {
            return {
                main_settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: true,
                    fade: false,
                    adaptiveHeight: true,
                    infinite: false,
                    useTransform: true,
                    speed: 400,
                    cssEase: 'cubic-bezier(0.77, 0, 0.18, 1)'
                },
                nav_settings: {
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    arrows: true,
                    fade: false,
                    adaptiveHeight: true,
                    infinite: false,
                    useTransform: true,
                    speed: 400,
                    cssEase: 'cubic-bezier(0.77, 0, 0.18, 1)'
                }
            }
        },
        props: {
            itemId: Number,
            colorId: Number,
        },
        components: {
            Slick,
        }
	}
</script>