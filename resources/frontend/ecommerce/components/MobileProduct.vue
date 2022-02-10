<template>
    <div class="home_slider_inner">
        <div class="home_slider_inner_content">
            <router-link :to="{ name: 'single-product', params: { parent: product.slug }}">
                <img :src="product.images && product.images.length > 0 ? ('/' + product.images[0].compressed_image_path) : ('/' + defaultImage.value) | doubleSlashFilter"
                    :alt="'Hologram Product ' + product.style_no" @error="imgErrHndlHasan(this, product.images[0].compressed_image_path)" class="img-fluid">

                <h2>{{product && product.name ? product.name : 'No Specific Name'}}</h2>
                <p>{{product && product.brand && product.brand.name ? product.brand.name : 'Not Specific'}} | USD${{product.price | round(2)}}</p>
            </router-link>
        </div>
    </div>
</template>
<script> 
    import mixins from '../../helpers/mixins'
    export default {
        name:'productComponent',
        mixins: [mixins],
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
    }
</script>