<template>
    <div class="product_content_inner">
        <div class="product_inner_wrap">
            <router-link :to="{ name: 'single-product', params: { parent: product.slug }}">
                <img :src="product.images && product.images.length > 0 ? ('/' + product.images[0].compressed_image_path) : ('/' + defaultImage.value) | doubleSlashFilter"
                     :alt="'Hologram Product ' + product.style_no"
                     class="img-fluid" @error="imgErrHndlHasan($event, product.images[0].compressed_image_path)">
            </router-link>
            <div class="product_details_text">
                <h2 class="product_title">
                    <router-link :to="{ name: 'single-product', params: { parent: product.slug }}">
                        {{product && product.name ? product.name : 'No Specific Name'}}
                    </router-link>
                </h2>
                <p class="product_price">
                    <span>{{product && product.brand && product.brand.name ? product.brand.name : 'Not Specific'}} </span> | <span>USD${{product.price | round(2)}}</span>
                </p>
                <slot></slot>
                <div class="product_on_hover">
                    <ul class="p_list">
                        <li v-if="product.sizes.length == 0">Not specific</li>
                        <li v-for="(size, sizeIndex) in product.sizes" :key="'productSize_' + product.style_no + '_' + sizeIndex">{{size.item_size}}</li>
                    </ul>
                    <ul class="p_icon" v-if="product.values && product.values.length > 0">
                        <li v-for="(value, valueIndex) in product.values" :key="'productValues_' + product.style_no + '_' + valueIndex"><i :class="value.icon"></i></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import mixins from "../../helpers/mixins";

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
