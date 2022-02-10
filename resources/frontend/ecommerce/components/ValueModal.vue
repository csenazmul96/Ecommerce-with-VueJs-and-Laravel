<template>
    <div class="modal open_modal" data-modal="ls" ref="valueModal">
        <div class="modal_overlay" data-modal-close="ls" @click="closeModal()"></div>
        <div class="modal_wrapper">
            <span class="close_modal" data-modal-close="ls" @click="closeModal()"></span>
            <div class="size_modal_wrap" v-if="allValues">
                <div class="size_modal_left text-center">
                    <img v-if="imageLoaded" :src="allValues[currentIndex].image_path ? allValues[currentIndex].image_path  : '/images/no-image.png'" :alt="'Hologram Product Value' + allValues[currentIndex].name" class="img-fluid">
                    <img v-else src="/images/image-load.jpg" class="img-fluid">
                </div>
                <div class="size_modal_right">
                <h2>{{allValues[currentIndex].name}} <i class="fas fa-hand-spock"></i></h2>
                <p>{{allValues[currentIndex].description}} </p>
                </div>
                <div class="size_nav">
                <span class="prev" @click="changePosition(-1)" v-if="currentIndex > 0"> <i class="fas fa-long-arrow-alt-left"></i> {{allValues[currentIndex - 1].name}} </span>
                <span class="next" @click="changePosition(1)" v-if="currentIndex < (allValues.length - 1)">{{allValues[currentIndex + 1].name}} <i class="fas fa-long-arrow-alt-right"></i> </span>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        name:'valueModal',
        data() {
            return {
                currentIndex: 0,
                imageLoaded: true,
            }
        },
        filters: {
            capitalize: function (value) {
                if (!value) return ''
                value = value.toString()
                return value.charAt(0).toUpperCase() + value.slice(1)
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
            allValues: {
                type: Array,
            },
            selectIndex: {
                type: Number,
                required: true
            }
        },
        mounted() {
            this.currentIndex = this.selectIndex;
            console.log('sdf')
        },
        watch: {
            selectIndex(index) {
                this.currentIndex = index;
            }
        },
        methods: {
            changePosition(change) {
                    this.imageLoaded = false;
                   let index = this.currentIndex;
                   index = index + Number(change)
                   if (index == -1) {
                       index = this.allValues.length - 1;
                   }
                   if (index == this.allValues.length) {
                       index = 0;
                   }
                   this.currentIndex = index;
                this.imageLoaded = true;
            },
            closeModal() {
                this.$emit('closeModal');
            },
        }
    }
</script>

