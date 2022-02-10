<template>
    <div class="news_letter_top" v-if="privacyNotices.length">
        <div class="inner_wrap">

            <div class="inner" v-for="(notice, i) in privacyNotices">
                <h2>{{ notice.name }}</h2>
                <p>{{ notice.description}}</p>
            </div>

        </div>
    </div>
</template>
<script>
export default {
    name:'newsletter',
    data() {
        return {
            privacyNotices:[],
        }
    },

    created() {
        axios.get('/api/v1/get/privacy-notice').then((response) => {this.privacyNotices = response.data;});
    },

}
</script>
