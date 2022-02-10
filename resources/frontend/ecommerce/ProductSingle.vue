<template>
    <!-- ============================
        START PRODUCT SINGLE SECTION
    ============================== -->
    <div>
        <div class="single_page_bredcrumb">
            <ul class="breadcrumb mb_10">
                <li v-for="(b, index) in breadcrumbs" :key="'bc_'+index">
                    <router-link :to="b.url" >{{ b.name }}</router-link>
                </li>
                <li>{{product && product.capitalizeName ? product.capitalizeName : 'No Specific Name'}}</li>
            </ul>
        </div>
        <div :itemtype="hosturl" itemscope>
            <meta itemprop="name" v-if="product && product.capitalizeName" :content="product.capitalizeName" />
            <template v-if="computedProductImages && computedProductImages.length > 0">
                <link  v-for="(image, linkindex) in computedProductImages" :key="'link' + linkindex"  itemprop="image" :href="hosturl+'/'+image.compressed_image_path" />
            </template>
            <meta itemprop="priceCurrency" content="USD" />
            <meta itemprop="price" v-if="product && product.price" :content="product.price" />
            <meta itemprop="description" v-if="product && product.details" :content="product.details"  />
            <meta itemprop="sku" v-if="product && product.style_no" :content="product.style_no" />

            <div itemprop="review" :itemtype="hosturl" itemscope>
                <div itemprop="author" :itemtype="hosturl" itemscope>
                    <meta itemprop="name" content="ShopHologram" />
                </div>
                <div itemprop="reviewRating" :itemtype="hosturl" itemscope>
                    <meta itemprop="ratingValue" v-if="product && product.rate" :content="product ? product.rate : 0 | round(2)" />
                    <meta itemprop="bestRating" content="5" />
                </div>
            </div>
            <div itemprop="aggregateRating" :itemtype="hosturl" itemscope>
                <meta itemprop="reviewCount" v-if="product && product.totalReviews" :content="product.totalReviews" />
                <meta itemprop="ratingValue" content="5" />
            </div>
        </div>
        <section class="product_single_area" :class="{content:product}" style="position:relative;">
            <div class="product_single_left">
                <div class="single_img_thumbnail">
                    <VueSlickCarousel ref="c2" :asNavFor="$refs.c1" v-bind="sliderSettings2" v-if="computedProductImages && computedProductImages.length > 1">
                        <div class="slide"
                             v-for="(image, imageIndex) in computedProductImages"
                             :key="'deskThumbs_' + imageIndex"
                             :style="{ backgroundImage: `url(${image.thumbs_img}), url(${imageForSafari(image.thumbs_img)})` }">
                        </div>
                        <div v-if="product.video">
                            <div class="video_thumbs" :key="'deskThumbs_video'">
                                <video v-if="product.video != null" width="100%" loop muted playsinline autoplay preload="metadata" @play="thumbsVideoPlay">
                                    <source class="product-video" :src="product.video" type="video/mp4">
                                </video>
                                <div class="video_player_spin" v-if="videoThumbsLoad"><i class="fas fa-circle-notch fa-spin"></i></div>
                            </div>
                        </div>
                    </VueSlickCarousel>
                </div>
                <div class="single_img_wrap">
                    <VueSlickCarousel id="zoom-area"
                                      ref="c1"
                                      @reInit="onInitCarousel"
                                      v-bind="sliderSettings"
                                      v-if="computedProductImages && computedProductImages.length > 1">
                        <div :class="['slide', {zoom:screenWidth > 1024}]"
                             :data-src="image.compressed_img_jpg"
                             :ref="'zoom_'+imageIndex"
                             v-for="(image, imageIndex) in computedProductImages"
                             :key="'deskList_' + imageIndex"
                             :style="{ backgroundImage: `url(${image.compressed_img_webp}), url(${imageForSafari(image.compressed_img_jpg)})` }">
                        </div>
                        <div v-if="product.video">
                            <video-player :options="videoOptions"/>
                        </div>
                    </VueSlickCarousel>

                    <div v-else class="slide zoom"
                         v-for="(image, imageIndex) in computedProductImages"
                         :key="'deskList_' + imageIndex"
                         :data-src="image.compressed_img_jpg"
                         :style="{ backgroundImage: `url(${image.compressed_img_webp}), url(${imageForSafari(image.compressed_img_jpg)})` }">
                    </div>
                </div>

            </div>
            <div class="product_single_right">
                <h1 class="product_title">{{product && product.name ? product.name : 'No Specific Name'}}</h1>
                <div class="product_review" v-if="product && product.totalReviews">
                    <div class="rating_star">
                        <span :class="{has_review:product.rate >= 1}"><i class="lni" v-bind:class="product.rate >= 1 ? ' lni-star-filled' : ' lni-star-half'"></i></span>
                        <span :class="{has_review:product.rate >= 2}"><i class="lni" v-bind:class="product.rate >= 2 ? ' lni-star-filled' : ' lni-star-half'"></i></span>
                        <span :class="{has_review:product.rate >= 3}"><i class="lni" v-bind:class="product.rate >= 3 ? ' lni-star-filled' : ' lni-star-half'"></i></span>
                        <span :class="{has_review:product.rate >= 4}"><i class="lni" v-bind:class="product.rate >= 4 ? ' lni-star-filled' : ' lni-star-half'"></i></span>
                        <span :class="{has_review:product.rate >= 5}"><i class="lni" v-bind:class="product.rate >= 5 ? ' lni-star-filled' : ' lni-star-half'"></i></span>
                    </div>
                    <span> {{product ? product.rate : 0 | round(2)}} From {{product ? product.totalReviews : 0}} votes.</span>
                </div>
                <h3 class="product_price">USD${{product ? product.price : 0 | round(2)}}</h3>
                <div class="add_to_bag with_size" v-if="(sizes && sizes.length) > 0 && (colors && colors.length > 0)">
                    <div class="add_size">
                        <div class="size_box_type_2">
                            <div class="size_box" v-if="(sizes && sizes.length) > 0 && (colors && colors.length > 0)">
                                <label>Sizes</label>
                                <select class="form-control" v-model="selectedSize">
                                    <option :value="null">Choose an Option...</option>
                                    <template v-if="computedSizes && computedSizes.length > 0">
                                        <option :value="itemSize" v-for="(itemSize, itemSizeIndex) in computedSizes" :key="'itemSize_' + itemSizeIndex">{{itemSize.name}}</option>
                                    </template>
                                </select>
                                <div class="err_msg" v-for="(formError, errorIndex) in formErrors['size_id']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></div>
                            </div>
                            <div class="size_box color_box">
                                <label>Color</label>
                                <select class="form-control" v-model="selectedColor" @change="imageFilterChange">
                                    <option :value="null">Choose an Option...</option>
                                    <template v-if="computedColors && computedColors.length > 0">
                                        <option :value="itemColor" v-for="(itemColor, itemColorIndex) in computedColors" :key="'itemColor_' + itemColorIndex">{{itemColor.color_name}}</option>
                                    </template>
                                </select>
                                <div class="err_msg" v-for="(formError, errorIndex) in formErrors['color_id']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></div>
                            </div>
                        </div>
                        <div class="p_quantity">
                            <label>Qty: </label>
                            <input type="text" class="form-control" placeholder="0" v-model="selectedQty" @keypress="isNumber($event)">
                            <div class="err_msg" v-for="(formError, errorIndex) in formErrors['quantity']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></div>
                        </div>
                    </div>
                    <div class="add_cart">
                        <div class="add_cart_btn">
                            <button class="btn_common" @click.prevent="addToCart" :disabled="cartAddLoader" ref="cartAddButton"><img src="/images/cart-white.png" alt=""> <span class="ml_5">ADD TO BAG</span> </button>
                        </div>
                        <div class="p_like p_like_toggler" v-if="productWishlist == null" @click="addToWishlist"><i class="far fa-heart"></i></div>
                        <div class="p_like p_like_toggler" v-else @click="removeFromWishlist"><i class="fas fa-heart"></i></div>
                    </div>
                </div>

                <div class="add_to_bag" v-else-if="colors && colors.length > 0">
                    <div class="add_size">
                        <div class="size_box">
                            <label>Color </label>
                            <select class="form-control" v-model="selectedColor" @change="imageFilterChange">
                                <option :value="null">Choose an Option...</option>
                                <template v-if="colors && colors.length > 0">
                                    <option :value="itemColor" v-for="(itemColor, itemColorIndex) in colors" :key="'itemColor_' + itemColorIndex">{{itemColor.color_name}}</option>
                                </template>
                            </select>
                            <div class="err_msg" v-for="(formError, errorIndex) in formErrors['color_id']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></div>
                        </div>
                        <div class="p_quantity">
                            <label>Qty: </label>
                            <input type="text" class="form-control" placeholder="0" v-model="selectedQty" @keypress="isNumber($event)">
                            <div class="err_msg" v-for="(formError, errorIndex) in formErrors['quantity']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></div>
                        </div>
                    </div>
                    <div class="add_cart">
                        <div class="add_cart_btn">
                            <button class="btn_common" @click.prevent="addToCart" :disabled="cartAddLoader" ref="cartAddButton"><img src="/images/cart-white.png" alt=""> <span class="ml_5">ADD TO BAG</span> </button>
                        </div>
                        <div class="p_like p_like_toggler" v-if="productWishlist == null" @click="addToWishlist"><i class="far fa-heart"></i></div>
                        <div class="p_like p_like_toggler" v-else @click="removeFromWishlist"><i class="fas fa-heart"></i></div>
                    </div>
                </div>
                <div class="add_to_bag" v-else-if="sizes && sizes.length > 0">
                    <div class="add_size">
                        <div class="size_box">
                            <label>Sizes </label>
                            <select class="form-control" v-model="selectedSize" @change="imageFilterChange">
                                <option :value="null">Choose an Option...</option>
                                <template v-if="computedSizes && computedSizes.length > 0">
                                    <option :value="itemSize" v-for="(itemSize, itemSizeIndex) in computedSizes" :key="'itemSize_' + itemSizeIndex">{{itemSize.name}}</option>
                                </template>
                            </select>
                            <div class="err_msg" v-for="(formError, errorIndex) in formErrors['size_id']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></div>
                        </div>
                        <div class="p_quantity">
                            <label>Qty: </label>
                            <input type="text" class="form-control" placeholder="0" v-model="selectedQty" @keypress="isNumber($event)">
                            <div class="err_msg" v-for="(formError, errorIndex) in formErrors['quantity']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></div>
                        </div>
                    </div>
                    <div class="add_cart">
                        <div class="add_cart_btn">
                            <button class="btn_common" @click.prevent="addToCart" :disabled="cartAddLoader" ref="cartAddButton"><img src="/images/cart-white.png" alt=""> <span class="ml_5">ADD TO BAG</span> </button>
                        </div>
                        <div class="p_like p_like_toggler" v-if="productWishlist == null" @click="addToWishlist"><i class="far fa-heart"></i></div>
                        <div class="p_like p_like_toggler" v-else @click="removeFromWishlist"><i class="fas fa-heart"></i></div>
                    </div>
                </div>

                <div class="add_to_bag add_to_bag_full" v-else-if="sizes && sizes.length == 0 && colors && colors.length == 0">
                    <div class="add_size">
                        <div class="p_quantity">
                            <label>Qty: </label>
                            <input type="text" class="form-control" placeholder="0" v-model="selectedQty" @keypress="isNumber($event)">
                            <div class="err_msg" v-for="(formError, errorIndex) in formErrors['quantity']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></div>
                        </div>
                    </div>

                    <div class="add_cart">
                        <div class="add_cart_btn">
                            <button class="btn_common" @click.prevent="addToCart" :disabled="cartAddLoader" ref="cartAddButton"><img src="/images/cart-white.png" alt=""> <span class="ml_5">ADD TO BAG</span> </button>
                        </div>
                        <div class="p_like p_like_toggler" v-if="productWishlist == null" @click="addToWishlist"><i class="far fa-heart"></i></div>
                        <div class="p_like p_like_toggler" v-else @click="removeFromWishlist"><i class="fas fa-heart"></i></div>
                    </div>
                </div>
                <div class="product_value" v-if="allValues && allValues.length > 0">
                    <p>{{product && product.brand && product.brand.name ? product.brand.name : 'BRAND '| capitalize}} VALUES: </p>
                    <ul class="product_v_list" >
                        <li class="product_v_l_inner" v-for="(value, valueIndex) in allValues" :key="'allValues_' + (product ? product.style_no : 'product') + '_' + valueIndex" v-bind:class="valueActive(value)">
                            <span><i :class="value.icon"></i> {{value.name}}</span>
                            <span class="pop_up" @click="openValueModal(valueIndex)"><i class="fas fa-info-circle"></i></span>
                        </li>
                    </ul>
                    <valueModalComponent v-if="showValueModal && allValues.length > 0" @closeModal="closeValueModal" :allValues="allValues" :selectIndex="selectValueIndex"></valueModalComponent>
                </div>
                <div class="product_description">
                    <tabsComponent>
                        <tabComponent :active="true" :tabId="'home'" :title="'Description'">
                            <div v-if="product && product.details" v-html="product.details"></div>
                        </tabComponent>
                        <tabComponent :active="false" :tabId="'SizeChart'" :title="'Instructions'">
                            <div v-html="sizechart"></div>
                        </tabComponent>
                        <tabComponent :active="false" :tabId="'Returns'" :title="'Returns'">
                            <div v-html="returns"></div>
                        </tabComponent>
                    </tabsComponent>
                </div>
            </div>
        </section>

        <div class="p_d_mobile">
            <ul>
                <li data-toggle="collapse_noslide" data-target="#MobDescription">Description</li>
                <div class="p_d_mobile_inner" id="MobDescription">
                    <div v-if="product && product.details" v-html="product.details"></div>
                </div>
                <li data-toggle="collapse_noslide" data-target="#MobSize" v-if="sizechart && sizechart.content" v-html="sizechart.content">Instructions</li>
                <div class="p_d_mobile_inner" id="MobSize">
                    <div v-if="sizechart && sizechart.content" v-html="sizechart.content"></div>
                </div>
                <li data-toggle="collapse_noslide" data-target="#MobReturns" v-if="returns && returns.content" v-html="returns.content">Returns</li>
                <div class="p_d_mobile_inner" id="MobReturns">
                    <div v-if="returns && returns.content" v-html="returns.content"></div>
                </div>
            </ul>
        </div>
        <!-- ============================
            END PRODUCT SINGLE SECTION
        ============================== -->

        <productReview v-if="product"  :product="product" :defaultImage="defaultImage"></productReview>


        <!-- =================================
            START RELATED PRODUCT SECTION
        =================================== -->
        <section class="related_product_area" v-if="relatedProducts && relatedProducts.length">
            <div class="related_product_title">
                <h2>You Might Like</h2>
            </div>
            <div class="related_product_content">
                <div class="title">You Might Like</div>
                <div class="product_content_wrap">
                    <productComponent v-for="(product, productKey) in relatedProducts" :key="'product_' + productKey"  :product="product" :defaultImage="defaultImage"></productComponent>
                </div>
            </div>

            <div class="r_p_c_mobile">
                <div v-if="relatedProducts.length > 0">
                    <carousel :responsive="{0:{items:2},400:{items:2},768:{items:3},1200:{items:4}}" :dots="false" :nav="true">
                        <div class="home_slider_inner" v-for="(product, productKey) in relatedProducts" :key="'product_' + productKey">
                            <div class="home_slider_inner_content">
                                <router-link :to="{ name: 'single-product', params: { parent: product.slug }}">
                                    <img :src="product.images && product.images.length > 0 ? (product.images[0].compressed_img_webp) : ('/' + defaultImage.value)"
                                         :alt="product.name + ' gel polish'" data-counter="0" @error="imgErrHndlHasan($event, product.images[0].compressed_img_jpg)" class="img-fluid">

                                    <h2>{{product && product.name ? product.name : 'No Specific Name'}}</h2>
                                    <p>{{product.style_no}} | USD${{product.price | round(2)}}</p>
                                </router-link>
                            </div>
                        </div>
                    </carousel>
                </div>
            </div>
        </section>
    </div>
    <!-- =================================
        END RELATED PRODUCT SECTION
    =================================== -->
</template>
<script>
    import ecommerceStore from './ecommerceStore'
    import defaultStore from '../layouts/defaultStore'
    import validate from 'validate.js'
    import mixins from '../helpers/mixins'
    import carousel from 'vue-owl-carousel/src/Carousel'

    import VueSlickCarousel from 'vue-slick-carousel'

    import productComponent from './components/Product'
    import mobileProductComponent from './components/MobileProduct'
    import productReview from './components/ProductReview'
    import tabsComponent from './components/Tabs'
    import tabComponent from './components/Tab'
    import VideoPlayer from "./components/VideoPlayer";
    import valueModalComponent from "./components/ValueModal";

    export default {
        name:'productSingle',
        mixins: [mixins],
        components: {
            productComponent: productComponent,
            mobileProductComponent: mobileProductComponent,
            productReview: productReview,
            tabsComponent: tabsComponent,
            tabComponent: tabComponent,
            valueModalComponent:valueModalComponent,
            VueSlickCarousel,
            carousel,
            VideoPlayer
        },
        data() {
            return {
                screenWidth:window.innerWidth,
                productloading: false,
                showValueModal: false,
                videoThumbsLoad: true,
                selectValueIndex: 0,
                selectedSize: null,
                selectedColor: null,
                selectedQty: 1,
                cartAddLoader: null,
                computedColors: [],
                computedSizes: [],
                computedProductImages: null,
                sliderSettings: {
                    "arrows":true,
                    "slidesToShow": 1,
                    "autoplay": false,
                    "autoplaySpeed": 9000000000,
                    "lazyLoad": "progressive",
                    "swipeToSlide": true,
                    "focusOnSelect": true,
                    "initialSlide": 0
                },
                sliderSettings2: {
                    "arrows":true,
                    "slidesToShow": 4,
                    "autoplay": false,
                    "vertical" : true,
                    "autoplaySpeed": 9000000000,
                    "lazyLoad": "progressive",
                    "focusOnSelect": true,
                    "initialSlide": 0,
                },
                hosturl: window.location.hostname,
                videoOptions: {
                    autoplay: true,
                    controls: false,
                    loop: true,
                    muted: true,
                    sources: [
                        {
                            src: null,
                            type: "video/mp4"
                        }
                    ]
                }
            }
        },
        metaInfo(){
            return {
                title: this.product && this.product.capitalizeName ? (this.product.capitalizeName + ' Gel Polish | Hologram') : null,
                meta: [
                    { name: 'description', content: 'dsf' },
                ]
            }
        },
        beforeCreate() {
            if (!(this.$store && this.$store.state && this.$store.state['ecommerceStore'])) {
                this.$store.registerModule("ecommerceStore", ecommerceStore);
            }
            if (!(this.$store && this.$store.state && this.$store.state['defaultStore'])) {
                this.$store.registerModule("defaultStore", defaultStore);
            }
        },
        mounted() {
            this.productPreloads();
        },
        filters: {
            capitalize: function (value) {
                if (!value) return ''
                value = value.toString()
                //return value.charAt(0).toUpperCase() + value.slice(1)

                return value.replace(/(?:^|\s)\S/g, function(a) { return a.toUpperCase(); });
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
            },
            doubleSlashFilter: function (value) {
                return value.replace("//", "/");
            },
        },
        watch: {
            successMessage(successMessage) {
                if (successMessage) {
                    this.$swal({
                        icon: 'success',
                        title: successMessage,
                        timer: 1000,
                    })
                    this.$store.commit('ecommerceStore/setSuccessMessage', '');
                }
            },
            errorMessage(errorMessage) {
                if (errorMessage) {
                    this.$swal({
                        icon: 'error',
                        title: errorMessage
                    })
                    this.$store.commit('ecommerceStore/setErrorMessage', '');
                }
            },
            $route(to,from){
                this.productPreloads();
            },
            productImages(productImages){
                this.$set(this, 'computedProductImages', null);
                this.$set(this, 'computedProductImages', productImages);
            },
            computedProductImages() {
                if (this.computedProductImages.length === 1) {
                    setTimeout(() => {
                        this.onInitCarousel()
                    }, 500)
                }


                this.sliderSettings.slidesToShow = 2;
                this.sliderSettings2.slidesToShow = 2;
                this.$nextTick(() => {
                    this.sliderSettings.slidesToShow = 1;
                    this.sliderSettings2.slidesToShow = 4;
                })
            },
            sizes(sizes){
                this.computedSizes = sizes.filter((obj, pos, arr) => {
                    return arr.map(mapObj => mapObj['id']).indexOf(obj['id']) === pos;
                });
                if(this.computedSizes.length)
                    this.selectedSize = this.computedSizes[0]
            },
            colors(colors){
                this.computedColors = colors;
                if(this.computedColors.length)
                    this.selectedColor = this.computedColors[0]
            },
            loading(loading) {
                if(this.cartAddLoader && loading === false) {
                    this.cartAddLoader.hide();
                    this.cartAddLoader = null;
                }
            },
        },
        computed:{
            allValues() {
                return this.$store.getters['ecommerceStore/getAllValues'];
            },
            loading() {
                return this.$store.getters['ecommerceStore/getLoading'];
            },
            successMessage() {
                return this.$store.getters['ecommerceStore/getSuccessMessage'];
            },
            errorMessage() {
                return this.$store.getters['ecommerceStore/getErrorMessage'];
            },
            formErrors:{
                get: function () {
                    return this.$store.getters['ecommerceStore/getFormErrors']
                },
                set: function (formErrors = {}) {
                    this.$store.commit('ecommerceStore/setFormErrors', formErrors);
                }
            },
            defaultImage(){
                return this.$store.getters['ecommerceStore/getDefaultImage']
            },
            colors() {
                return this.$store.getters['ecommerceStore/getColors'];
            },
            sizes() {
                return this.$store.getters['ecommerceStore/getSizes'];
            },
            product() {
                return this.$store.getters['ecommerceStore/getProduct'];
            },
            breadcrumbs() {
                return this.$store.getters['ecommerceStore/getProductBreadcrumbs'];
            },
            sizechart() {
                return this.$store.getters['ecommerceStore/getSizechart'];
            },
            returns() {
                return this.$store.getters['ecommerceStore/getReturns'];
            },
            productImages() {
                return this.$store.getters['ecommerceStore/getProductImages'];
            },
            productWishlist() {
                return this.$store.getters['ecommerceStore/getProductWishlist'];
            },
            relatedProducts() {
                return this.$store.getters['ecommerceStore/getRelatedProducts'];
            },
        },
        methods:{
            thumbsVideoPlay(){
                this.videoThumbsLoad = false;
            },
            imageFilterChange() {
                this.$set(this, 'computedProductImages', null);
                let that = this;
                 if (that.selectedColor != null) {
                    let hasSizes = that.sizes.filter(size => size.color_id == that.selectedColor.id);
                    that.computedSizes = hasSizes.filter((obj, pos, arr) => {
                        return arr.map(mapObj => mapObj['id']).indexOf(obj['id']) === pos;
                    });
                    let filteredImages = that.productImages.filter(value => value.color_id == that.selectedColor.id);
                    if(filteredImages.length){
                        that.$set(that, 'computedProductImages', filteredImages);
                    }else{
                        that.$set(that, 'computedProductImages', that.productImages);
                    }

                }
            },
            async addToWishlist() {
                let that = this;
                let formData = {
                    item_id : this.product.id,
                }
                await this.$store.dispatch('ecommerceStore/addToWishlist',  formData).then(()=>{
                    // $("#wishlist").css('display','block')
                });
                await this.$store.dispatch('defaultStore/wishlistItems',  formData).then(()=>{
                    if(this.userInformation)
                        $("#wishlist").css('display','block')
                });
            },
            async removeFromWishlist() {
                this.$Progress.start()
                let that = this;
                let formData = {
                    item_id : this.product.id,
                }
                await this.$store.dispatch('ecommerceStore/removeFromWishlist',  formData);
                await this.$store.dispatch('defaultStore/wishlistItems',  formData)
                this.$Progress.finish()
            },
            async addToCart() {
                if(!this.validateBeforeCart()) return;
                this.cartAddLoader = this.inlineLoader({ container: this.$refs.cartAddButton })
                let that = this;
                let formData = {
                    item_id : this.product.id,
                    specification : this.product.specification,
                    size_id : this.selectedSize ? this.selectedSize.id : null,
                    color_id : this.selectedColor ? this.selectedColor.id : null,
                    quantity : this.selectedQty,
                }
                await this.$store.dispatch('ecommerceStore/addToCart',  formData)
                    .then((response)=>{
                        that.$store.dispatch('defaultStore/cartItems',  formData).then((res)=>{
                            that.selectedQty = 1;
                            this.cartAddLoader.hide();
                            this.cartAddLoader = null;
                            $("#cart").css('display','block')
                            $("#mcart").addClass('open_h_menu')
                        })
                    })
                    .catch((error) => {
                        this.cartAddLoader.hide();
                        this.cartAddLoader = null;
                    }).finally(()=>{
                    })
            },
            validateBeforeCart() {
                let isValid = true;
                this.formErrors = {}

                let errors = {};

                let product = this.product;

                if(!product) {
                    isValid = false;
                }
                const qtyConstraints = {
                    selectedQty: {
                        presence: {
                            allowEmpty: false,
                            message:'^ Field is required.'
                        },
                    },
                }
                const colorConstraints = {
                    id: {
                        presence: {
                            allowEmpty: false,
                            message:'^ Field is required.'
                        },
                    },
                }
                const sizeConstraints = {
                    id: {
                        presence: {
                            allowEmpty: false,
                            message:'^ Field is required.'
                        },
                    },
                }
                const qtyErrors = validate(this.$data, qtyConstraints);
                if (qtyErrors) {
                    errors['quantity'] = qtyErrors['selectedQty']
                    this.$Progress.fail()
                    isValid = false;
                }
                let sizeErrors = null;
                let colorErrors = null;
                switch (product.specification) {
                    case '1':
                        sizeErrors = validate(this.$data.selectedSize, sizeConstraints);
                        if (sizeErrors) {
                            errors['size_id'] = sizeErrors['id']
                            this.$Progress.fail()
                            isValid = false;
                        }
                        colorErrors = validate(this.$data.selectedColor, colorConstraints);
                        if (colorErrors) {
                            errors['color_id'] = colorErrors['id']
                            this.$Progress.fail()
                            isValid = false;
                        }
                        break;
                    case '2':
                        colorErrors = validate(this.$data.selectedColor, colorConstraints);
                        if (colorErrors) {
                            errors['color_id'] = colorErrors['id']
                            this.$Progress.fail()
                            isValid = false;
                        }
                        break;
                    case '3':
                        sizeErrors = validate(this.$data.selectedSize, sizeConstraints);
                        if (sizeErrors) {
                            errors['size_id'] = sizeErrors['id']
                            this.$Progress.fail()
                            isValid = false;
                        }
                        break;
                    case '4':

                        break;

                    default:
                        sizeErrors = validate(this.$data.selectedSize, sizeConstraints);
                        if (sizeErrors) {
                            errors['size_id'] = sizeErrors['id']
                            this.$Progress.fail()
                            isValid = false;
                        }
                        colorErrors = validate(this.$data.selectedColor, colorConstraints);
                        if (colorErrors) {
                            errors['color_id'] = colorErrors['id']
                            this.$Progress.fail()
                            isValid = false;
                        }
                        break;
                }
                this.formErrors = errors
                return isValid;
            },
            valueActive(value) {
                if (!this.product) return '';
                if (this.product.values.filter(productValue => productValue.id === value.id).length > 0) {
                    return ' active';
                }
            },
            onInitCarousel() {
                $('.zoom').each(function () {
                    var src = $(this).data('src')
                    $(this).zoom({
                        target: '#zoom-area',
                        on: 'mouseover',
                        touch: false,
                        url: src,
                    });
                });
            },
            async productPreloads(){
                // if(!this.product) {
                    this.formErrors = {}
                    var that = this;
                    this.$Progress.start()
                    let slug = this.$route.params.parent
                    this.$store.dispatch('ecommerceStore/allValues');

                    //$("#singlePagePreload").fadeIn();
                    // $('#preloader').delay(0).fadeIn('fast');
                    await this.$store.dispatch('ecommerceStore/singleProduct', slug)
                        .then((response) => {
                            setTimeout(() => {
                                this.$store.dispatch('ecommerceStore/productView', slug)
                                this.videoOptions.sources[0].src = this.product.video
                                this.$nextTick()
                                setTimeout(() => {
                                    this.productloading = true
                                }, 1500)
                            }, 1500);
                        })
                        .catch((error) => {
                        })
                // }else{
                //     this.formErrors = {}
                //     var that = this;
                //     this.$Progress.start()
                //     this.$store.dispatch('ecommerceStore/productView', this.product.slug)
                //     this.videoOptions.sources[0].src = this.product.video
                //     setTimeout(() => {
                //         this.$nextTick()
                //         setTimeout(() => {
                //             this.productloading = true
                //         }, 500)
                //     }, 500);
                // }
            },
            isNumber: function(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57))) {
                    evt.preventDefault();;
                } else {
                    return true;
                }
            },
            openValueModal(index) {
              this.selectValueIndex = index;
              this.showValueModal = true;
            },
            closeValueModal() {
                this.showValueModal = false
            },
            imageForSafari(source) {
                var sourceArray = source.split("/");
                var safariSource = '';
                for (let index = 0; index < sourceArray.length; index++) {
                    safariSource += sourceArray[index];
                    if (index < sourceArray.length - 1) {
                        safariSource += '/'
                    }
                    if (index == sourceArray.length - 2) {
                        safariSource += 'sa/'
                    }

                }
                return safariSource;
            }
        },
    }
</script>

