<template>

    <!-- =================================
            START REVIEW SECTION
            =================================== -->
    <section class="review_area">
            <div class="related_product_title">
                <h2>Customer Reviews</h2>
            </div>
            <div class="review_content">
                <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                    <div class="review_title">
                                        <div class="review_title_left">
                                                <span>{{rate}}</span>
                                                <div class="rating_star">
                                                    <span><i class="lni " v-bind:class="rate_0"></i></span>
                                                    <span><i class="lni " v-bind:class="rate_1"></i></span>
                                                    <span><i class="lni " v-bind:class="rate_2"></i></span>
                                                    <span><i class="lni " v-bind:class="rate_3"></i></span>
                                                    <span><i class="lni " v-bind:class="rate_4"></i></span>
                                                </div>
                                                <p>Based on {{totalReviews}} Rating</p>
                                        </div>
                                        <div class="review_title_right">
                                                <ul>
                                                    <li v-bind:class="active_4">5 <span><i class="lni lni-star-filled"></i></span> <span class="review_bar"></span></li>
                                                    <li v-bind:class="active_3">4 <span><i class="lni lni-star-filled"></i></span> <span class="review_bar"></span></li>
                                                    <li v-bind:class="active_2">3 <span><i class="lni lni-star-filled"></i></span> <span class="review_bar"></span></li>
                                                    <li v-bind:class="active_1">2 <span><i class="lni lni-star-filled"></i></span> <span class="review_bar"></span></li>
                                                    <li v-bind:class="active_0">1 <span><i class="lni lni-star-filled"></i></span> <span class="review_bar"></span></li>
                                                </ul>
                                        </div>
                                    </div>
                                    <template v-if="reviews">
                                        <div class="review_content_inner" v-for="(review, reviewKey) in reviews.data" :key="'reviewKey' + reviewKey">
                                            <div class="r_c_i_left">
                                                    <img src="/themes/front/media/images/user.png" alt="">
                                                    <p>{{review.user ? (review.user.first_name + ' ' + review.user.last_name) : ''}}</p>
                                                    <p>{{review.created_at | DateFormat}}</p>
                                            </div>
                                            <div class="rating_star">
                                                    <span v-if="review.rate > 0"><i class="lni lni-star-filled"></i></span>
                                                    <span v-if="review.rate > 1"><i class="lni lni-star-filled"></i></span>
                                                    <span v-if="review.rate > 2"><i class="lni lni-star-filled"></i></span>
                                                    <span v-if="review.rate > 3"><i class="lni lni-star-filled"></i></span>
                                                    <span v-if="review.rate > 4"><i class="lni lni-star-filled"></i></span>
                                            </div>
                                            <h2>{{review.review}}
                                            </h2>
                                            <div class="review_img">
                                                <template v-if="review.images.length > 0">
                                                    <img v-for="(image, imageKey) in review.images" :key="'ReviewImage' + imageKey" :src="image.thumbs_image_path" alt="">
                                                </template>
                                                <img v-else src="/themes/front/media/images/no-image.jpg" alt="">
                                            </div>
                                        </div>
                                    </template>
                                    <div class="review_bottom" v-bind:class="noPaginationButtonMiddle">
                                        <ReviewPagination v-if="reviews" :paginateData="reviews" @paginate="getReviews" ></ReviewPagination>
                                        <div class="write_review">
                                                <router-link  class="btn btn_common" to="/login"  v-if="userCheck == 'null'"> Please login to Review </router-link>
                                                <button class="btn_common" v-else
                                                    @click="openReviewModal()" 
                                                >Write A Review</button>
                                        </div>
                                    </div>
                            </div>
                        </div>
                </div>
            </div>
            <ReviewModal v-if="showReviewModal && product" @closeModal="closeReviewModal" :product="product"></ReviewModal>
    </section>
    <!-- =================================
            END REVIEW SECTION
            =================================== -->
</template> 

<script> 
export default {
    name:'ProductReview',
    components: {
        ReviewModal: () => import(/* webpackChunkName: "js/ReviewModal" */ './ReviewModal.vue'),
        ReviewPagination: () => import(/* webpackChunkName: "js/ReviewPagination" */ './ReviewPagination.vue'),
    },
    props: {
        product: {
            type: Object,
            required: true
        }
    },
    data(){
        return {
            showReviewModal: false,
            reviews: null,
            totalReviews: 0,
            rate: 0,
        }
    },
    mounted() {
        this.getReviews();
    },
    computed: {
        rate_0() {
            return Math.floor(this.rate) > 0 ? ' lni-star-filled' : ' lni-star'
        },
        rate_1() {
            return Math.floor(this.rate) > 1 ? ' lni-star-filled' : ' lni-star'
        },
        rate_2() {
            return Math.floor(this.rate) > 2 ? ' lni-star-filled' : ' lni-star'
        },
        rate_3() {
            return Math.floor(this.rate) > 3 ? ' lni-star-filled' : ' lni-star'
        },
        rate_4() {
            return Math.floor(this.rate) > 4 ? ' lni-star-filled' : ' lni-star'
        },
        
        active_0() {
            return Math.floor(this.rate) > 0 ? ' active' : ''
        },
        active_1() {
            return Math.floor(this.rate) > 1 ? ' active' : ''
        },
        active_2() {
            return Math.floor(this.rate) > 2 ? ' active' : ''
        },
        active_3() {
            return Math.floor(this.rate) > 3 ? ' active' : ''
        },
        active_4() {
            return Math.floor(this.rate) > 4 ? ' active' : ''
        },
        userCheck() {
            return this.$store.getters.GetUserDataCheck;
        },
        noPaginationButtonMiddle() {
            if (!this.reviews) {
                return ' r_b_without_login';
            } else if(this.reviews && this.reviews.data.length == 0) {
                return ' r_b_without_login';
            } else {
                return '';
            }
        }
    },
    methods: { 
        openReviewModal() {
            this.showReviewModal = true
        },
        closeReviewModal(submitted = false) {
            if (submitted) {
                toast.fire({
                        icon: 'success',
                        title: 'Thank you for your review',
                })
            }
            this.showReviewModal = false
            this.getReviews();
        },
        getReviews(page = 1) {
            let that = this;
            if(!this.product || !this.product.product || !this.product.product.id) {
                return;
            }
            let formData = {
                item_id: this.product.product.id,
                page: page
            }
            axios.post('/api/v1/getReviews', formData)
                .then((response) => {
                    that.$set(that, 'reviews', response.data.reviews);
                    that.$set(that, 'totalReviews', response.data.totalReviews);
                    that.$set(that, 'rate', response.data.rate);
                })
                .catch((error) => {
                    // console.log(error)
                })  
                .finally(() => {
                    // console.log('done')
                });
        },
    }
}
</script>