<template>
    <!-- =================================
    START REVIEW SECTION
    =================================== -->
    <section class="review_area">
        <div class="related_product_title" v-if="reviews && reviews.data.length">
            <h2>Customer Reviews</h2>
        </div>
        <div v-if="reviews && reviews.data.length">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12"  >
                        <div class="review_head" v-if="totalReviews">
                            <div class="review_head_list r_rating">
                                <div class="rating_star">
                                    <span><i class="lni " v-bind:class="rate_0"></i></span>
                                    <span><i class="lni " v-bind:class="rate_1"></i></span>
                                    <span><i class="lni " v-bind:class="rate_2"></i></span>
                                    <span><i class="lni " v-bind:class="rate_3"></i></span>
                                    <span><i class="lni " v-bind:class="rate_4"></i></span>
                                </div>
                                <h2>{{totalReviews}} reviews</h2>
                            </div>
                            <div class="r_h_main_rating">
                                <ul>
                                    <li :class="{has_review:rateCount.five > 0}">
                                        <div class="rating_list">
                                            <span><i class="lni lni-star-filled"></i></span>
                                            <span><i class="lni lni-star-filled"></i></span>
                                            <span><i class="lni lni-star-filled"></i></span>
                                            <span><i class="lni lni-star-filled"></i></span>
                                            <span><i class="lni lni-star-filled"></i></span>
                                        </div>
                                        <span class="count_review">({{ rateCount.five }})</span>
                                        <div class="progressbar">
                                            <div class="progress_cont">
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" :style="{width: (rateCount.five * 100) / totalRate +'%'}"> <span class="sr-only">100%</span> </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li :class="{has_review:rateCount.four > 0}">
                                        <div class="rating_list">
                                            <span><i class="lni lni-star-filled"></i></span>
                                            <span><i class="lni lni-star-filled"></i></span>
                                            <span><i class="lni lni-star-filled"></i></span>
                                            <span><i class="lni lni-star-filled"></i></span>
                                            <span><i class="lni lni-star"></i></span>
                                        </div>
                                        <span class="count_review">({{ rateCount.four }})</span>
                                        <div class="progressbar">
                                            <div class="progress_cont">
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" :style="{width: (rateCount.four * 100) / totalRate +'%'}"> <span class="sr-only">30%</span> </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li :class="{has_review:rateCount.three > 0}">
                                        <div class="rating_list">
                                            <span><i class="lni lni-star-filled"></i></span>
                                            <span><i class="lni lni-star-filled"></i></span>
                                            <span><i class="lni lni-star-filled"></i></span>
                                            <span><i class="lni lni-star"></i></span>
                                            <span><i class="lni lni-star"></i></span>
                                        </div>
                                        <span class="count_review">({{ rateCount.three }})</span>
                                        <div class="progressbar">
                                            <div class="progress_cont">
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" :style="{width: (rateCount.three * 100) / totalRate +'%'}"> <span class="sr-only">20%</span> </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li :class="{has_review:rateCount.two > 0}">
                                        <div class="rating_list">
                                            <span><i class="lni lni-star-filled"></i></span>
                                            <span><i class="lni lni-star-filled"></i></span>
                                            <span><i class="lni lni-star"></i></span>
                                            <span><i class="lni lni-star"></i></span>
                                            <span><i class="lni lni-star"></i></span>
                                        </div>
                                        <span class="count_review">({{ rateCount.two }})</span>
                                        <div class="progressbar">
                                            <div class="progress_cont">
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" :style="{width: (rateCount.two * 100) / totalRate +'%'}"> <span class="sr-only">10%</span> </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li :class="{has_review:rateCount.one > 0}">
                                        <div class="rating_list">
                                            <span><i class="lni lni-star-filled"></i></span>
                                            <span><i class="lni lni-star"></i></span>
                                            <span><i class="lni lni-star"></i></span>
                                            <span><i class="lni lni-star"></i></span>
                                            <span><i class="lni lni-star"></i></span>
                                        </div>
                                        <span class="count_review">({{ rateCount.one }})</span>
                                        <div class="progressbar">
                                            <div class="progress_cont">
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" :style="{width: (rateCount.one * 100) / totalRate +'%'}"> <span class="sr-only">0%</span> </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="review_content">
                            <div class="review_content_title">
                                <h2 v-if="totalReviews">Review ({{totalReviews}})</h2>
                                <h2 v-else></h2>
                                <div class="write_review">
                                    <router-link  class="btn btn_common" to="/login"  v-if="userCheck == false"> Please login to Write Review </router-link>
                                    <button class="btn_common" v-else @click="openReviewModal()" >Write A Review</button>
                                </div>
                            </div>

                            <div v-if="reviews">
                                <div class="review_content_inner" v-for="(review, reviewKey) in reviews.data" :key="'reviewKey' + reviewKey">
                                    <div class="review_author">
                                        <p><span>{{review.user ? (review.user.first_name + ' ' + review.user.last_name) : ''}}</span></p>
                                        <div class="review_author_rating">
                                            <div class="rating_star">
                                                <span v-if="review.rate > 0"><i class="lni lni-star-filled"></i></span>
                                                <span v-if="review.rate > 1"><i class="lni lni-star-filled"></i></span>
                                                <span v-if="review.rate > 2"><i class="lni lni-star-filled"></i></span>
                                                <span v-if="review.rate > 3"><i class="lni lni-star-filled"></i></span>
                                                <span v-if="review.rate > 4"><i class="lni lni-star-filled"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="review_text">
                                        <h3>{{ review.title }}</h3>
                                        <p>{{ review.review }}</p>
                                        <div class="review_img" v-if="review.images.length > 0">
                                            <a href="#" v-for="(image, imageKey) in review.images" :key="'ReviewImage' + imageKey">
                                                <img :src="image.compressed_image_path" alt="">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="review_bottom">
                                        <div class="review_share">

                                        </div>

                                        <review-feedback :review="review"></review-feedback>
                                    </div>
                                    <div class="review_date">
                                        <span>{{review.created_at | dateFormate("MM/DD/YY")}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="review_pagination" v-if="reviews && reviews.data.length">
                            <ReviewPagination :paginateData="reviews" @paginate="getReviews" ></ReviewPagination>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="review_without_login" v-else >
            <div class="related_product_title">
                <h2>There is no Review right now</h2>
            </div>
            <ul>
                <li><i class="lni lni-star-filled"></i></li>
                <li><i class="lni lni-star-filled"></i></li>
                <li><i class="lni lni-star-filled"></i></li>
                <li><i class="lni lni-star-filled"></i></li>
                <li><i class="lni lni-star-filled"></i></li>
            </ul>
            <router-link  class="btn btn_common" to="/login"  v-if="userCheck == false"> Please login to Write Review </router-link>
            <button class="btn_common" v-else @click="openReviewModal()" >Write A Review</button>
        </div>
        <ReviewModal v-if="showReviewModal && product" @closeModal="closeReviewModal" :product="product" :defaultImage="defaultImage"></ReviewModal>
    </section>
    <!-- =================================
    END REVIEW SECTION
    =================================== -->
</template>

<script>
    import * as dayjs from 'dayjs'
    import customerStore from '../../customer/customerStore'
    import ReviewFeedback from './ReviewFeedback';

    import ReviewModal from './ReviewModal'
    import ReviewPagination from './ReviewPagination'

    export default {
        name:'ProductReview',
        components: {
            ReviewModal: ReviewModal,
            ReviewPagination: ReviewPagination,
            ReviewFeedback
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
        filters: {
            dateFormate: function (date, format) {
                return dayjs(date).format(format);
            },
        },
        beforeCreate() {
            if (!(this.$store && this.$store.state && this.$store.state['customerStore'])) {
                this.$store.registerModule("customerStore", customerStore);
            }
        },
        data(){
            return {
                showReviewModal: false,
                reviews: null,
                totalReviews: 0,
                rate: 0,
                rateCount: {
                    one: 0,
                    two: 0,
                    three: 0,
                    four: 0,
                    five: 0,
                },
            }
        },
        mounted() {
            this.getReviews();
        },
        watch: {
            product() {
                this.getReviews();
            },
            userCheck(value) {
                //console.log(value)
            }
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
                return this.$store.getters['customerStore/getIsLoggedIn'];
            },
            noPaginationButtonMiddle() {
                if (!this.reviews) {
                    return ' r_b_without_login';
                } else if(this.reviews && this.reviews.data.length == 0) {
                    return ' r_b_without_login';
                } else {
                    return '';
                }
            },
            totalRate() {
                return this.rateCount.one + this.rateCount.two + this.rateCount.three + this.rateCount.four + this.rateCount.five;
            }
        },
        methods: {
            openReviewModal() {
                this.showReviewModal = true
            },
            closeReviewModal(submitted = false) {
                this.showReviewModal = false
                this.getReviews();
            },
            getReviews(page = 1) {
                let that = this;
                if(!this.product || !this.product.id) {
                    return;
                }
                let formData = {
                    item_id: this.product.id,
                    page: page
                }
                axios.post('/api/v1/getReviews', formData)
                    .then((response) => {
                        that.$set(that, 'reviews', response.data.reviews);
                        that.$set(that, 'totalReviews', response.data.totalReviews);
                        that.$set(that, 'rate', response.data.rate);
                        that.$set(that, 'rateCount', response.data.rateCount);
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
