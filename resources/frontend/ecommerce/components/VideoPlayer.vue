<template>
    <div :class="['video-player-box', {'video-player-box-show': show}]">
        <video ref="player" autoplay muted height="100%" width="100%">
            <source :src="options.sources[0].src" type="video/mp4">
        </video>

<!--        <video-player
            v-show="show"
            ref="videoPlayer"
            :options="options"
            :playsinline="true"
            @loadeddata="onPlayerLoadeddata($event)">
        </video-player>-->
    </div>

</template>

<script>
import { videoPlayer } from 'vue-video-player'

export default {
    name: "VideoPlayer",
    components: {
        videoPlayer
    },
    props: {
        options: {
            type: Object,
            default() {
                return {};
            }
        }
    },
    data() {
        return {
            show: false,
        }
    },
    mounted() {
        let self = this;
        this.player = this.$refs.player;

        this.player.src = this.options.sources[0].src;
        this.player.load();
        this.player.onloadeddata = function() {
            self.onPlayerLoadeddata()
        };
    },
    methods: {
        onPlayerLoadeddata() {
            this.show = true;
            this.$emit("videoPlayEvent", true);
        },
    }
}
</script>


