<template>
    <div>
        <div class="full_screen_overlay overlay_open" @click="closeModal()"></div>
        <div class="review_rating_right_menu review_rating_right_menu_open">
            <h2>WRITE YOUR REVIEW</h2>
            <span class="close_ic" @click="closeModal()"><i class="lni lni-close"></i></span>
            <div class="write_review_title clearfix">
                <img :src="'/' + product.default_img.thumbs_image_path" alt="">
                <p>{{product.product.name}}</p>
                <h2>${{product.product.price}}</h2>
            </div>
            <div class="clearfix"></div>
            <div class="review_description clearfix">
                <h2>
                    Review Rating *
                    <span>
                        <div class="rating_star">
                            <span @click="changeRate(5)" @mouseover="temporaryRate(5)" @mouseleave="leaveMouse()"><i class="lni " v-bind:class="rate_4"></i></span>
                            <span @click="changeRate(4)" @mouseover="temporaryRate(4)" @mouseleave="leaveMouse()"><i class="lni " v-bind:class="rate_3"></i></span>
                            <span @click="changeRate(3)" @mouseover="temporaryRate(3)" @mouseleave="leaveMouse()"><i class="lni " v-bind:class="rate_2"></i></span>
                            <span @click="changeRate(2)" @mouseover="temporaryRate(2)" @mouseleave="leaveMouse()"><i class="lni " v-bind:class="rate_1"></i></span>
                            <span @click="changeRate(1)" @mouseover="temporaryRate(1)" @mouseleave="leaveMouse()"><i class="lni " v-bind:class="rate_0"></i></span>
                        </div>
                    </span>
                </h2>
            </div>
            <div class="custom-file mb_10">
                <input type="file" class="custom-file-input" id="validatedCustomFile"  @change="onImageChange" multiple>
                <label class="custom-file-label" for="validatedCustomFile">Choose images...</label>
            </div>
            <div class="form-group common_form">
                    <label>WRITE REVIEW</label>
                    <textarea cols="30" rows="10" class="form-control" v-model="review.review"></textarea>
                    <small>not more than 350 characters</small>
            </div>
            <p>Please avoid using any inappropriate language, personal information, HTML, references to other retailers or
                    copyrighted comments.
            </p>
            <div class="form-group common_form">
                    <button class="btn_common" @click="reviewSubmit()">SUBMIT</button>
            </div>
        </div>
    </div>
</template> 

<script> 
import * as image from '../../../helpers/imageHelper'
export default {
    name:'ReviewModal',
    props: {
        product: {
            type: Object,
            required: true
        }
    },
    data(){
        return{
            review: {
                rate: 0,
                review: '',
                fixedRate: 0,
                item_id: 0,
                reviewImages: [],
            }
        }
    },
    computed: {
        rate_0() {
            return this.review.rate > 0 ? ' lni-star-filled' : ' lni-star'
        },
        rate_1() {
            return this.review.rate > 1 ? ' lni-star-filled' : ' lni-star'
        },
        rate_2() {
            return this.review.rate > 2 ? ' lni-star-filled' : ' lni-star'
        },
        rate_3() {
            return this.review.rate > 3 ? ' lni-star-filled' : ' lni-star'
        },
        rate_4() {
            return this.review.rate > 4 ? ' lni-star-filled' : ' lni-star'
        },
    },
    methods: {
        onImageChange(e) {
            var that = this;
            image.temporaryImageUpload(e.target.files)
                .then((response) => {
                    var reviewImages = that.review.reviewImages;
                    response.forEach(function (element) {
                        reviewImages.push(element);
                    })
                    that.$set(that.review, 'reviewImages', reviewImages);
                })                              
        },
        changeRate(val) {
            if (val == this.review.fixedRate) {
                this.review.fixedRate = 0;
                this.review.rate = 0;
            } else {
                this.review.fixedRate = val;
                this.review.rate = val;
            }
        },   
        temporaryRate(val) {
            let oldRate = this.review.rate
            this.review.rate = val;
        },   
        leaveMouse() {
            this.review.rate = this.review.fixedRate;
        },   
        closeModal() {
            this.$emit('closeModal');
        },
        reviewSubmit() {
            if(!this.validated()) return;
            this.review.item_id = this.product.product.id
            axios.post('/api/v1/makeReview', this.review)
                .then((response) => {
                    this.$emit('closeModal', true);
                    // console.log(response)
                })
                .catch((error) => {
                    // console.log(error)
                })  
                .finally(() => {
                    // console.log('done')
                });
        },
        validated() {
            let notValidated = true;
            if(this.review.fixedRate < 1 || this.review.fixedRate > 5) {
                notValidated = false;
                toast.fire({
                    icon: 'warning',
                    title: 'Rate between 1 to 5',
                })      
            }
            if(this.review.review.length > 350) {
                notValidated = false;
                toast.fire({
                    icon: 'warning',
                    title: 'Write a smaller review',
                })      
            }
            return notValidated;
        }
    }
}
</script>