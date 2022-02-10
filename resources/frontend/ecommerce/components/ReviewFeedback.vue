<template>
    <div class="review_like_count">
        <p>
            <span class="r_l_text">Was This Review Helpful?</span>
            <span :class="{'active': review.isLiked}" @click="reviewFeedback(review, 1)"><i class="fas fa-thumbs-up"></i> {{ review.like }}</span>
            <span :class="{'active': review.isDisliked}" @click="reviewFeedback(review, 0)"><i class="fas fa-thumbs-down"></i> {{ review.dislike }}</span>
        </p>
    </div>
</template>

<script>
export default {
    props: {
        review: {
            type: Object,
            required: true
        },
    },
    computed: {
        userCheck() {
            return this.$store.getters['customerStore/getIsLoggedIn'];
        }
    },
    data() {
        return {
            loading: false
        }
    },
    methods: {
        async reviewFeedback(review, like) {
            if (this.userCheck && !this.loading) {
                this.loading = true;

                await axios.post('/api/v1/review-feedback', {
                    review_id: review.id,
                    like: like
                }).then((response) => {
                    if (like == 1) {
                        review.isLiked = !review.isLiked;

                        if (response.data.increment)
                            review.like++;
                        else
                            review.like--;
                    } else {
                        review.isDisliked = !review.isDisliked;

                        if (response.data.increment)
                            review.dislike++;
                        else
                            review.dislike--;
                    }
                }).catch((error) => {
                    this.$swal({
                        icon: 'error',
                        title: error.response.data.error,
                    });
                }).finally(() => {
                    this.loading = false;
                });

                /*review.isLiked = true;
                review.like++;*/
            } else {
                this.$swal({
                    icon: 'error',
                    title: 'Please login to give feedback',
                });
            }
        }
    }
}
</script>
