<template v-if="newin">
    <span class="masterview">
        <section class="product_single_area">
            <div class="align_center" style="width:100%;">
                <div class="owl-carousel owl-theme">
                        <div class="item" v-for="(video,index) in NewItemSlider" :key="index"> 
                              <img :src="video.images[0].compressed_image_path" class="img-fluid" alt="">
                        </div>
                  </div>
            </div>
        </section>
    </span>
</template>

<script> 
var vm = this;
    export default {
      data(){
            return {
                sliderdata:[],
                NewItemSlider: []
            }
      }, 
      methods:{ 
            newcall(){
                  $('.owl-carousel').owlCarousel({
                  items: 4,
                  loop: true,
                  margin: 10,
                  autoplay: true,
                  autoplayTimeout: 900,
                  autoplayHoverPause: true,
                  responsiveClass: true,
                  responsive: {
                        0: {
                        items: 1,
                        nav: true
                        },
                        600: {
                        items: 2,
                        nav: false
                        },
                        1000: {
                        items: 2,
                        nav: true,
                        loop: false,
                        margin: 20
                        }
                  }
            });
            },
            slider(){
                  var vm = this; 
                  axios.get('')
                  .then((res)=>{ 
                        vm.NewItemSlider = this.$store.getters.NewIn 
                        Vue.nextTick(function(){
                              vm.newcall();
                        }.bind(vm));
                  }) 
            },
      }, 
      mounted(){  
            this.$store.dispatch('NewArrivalDispatch'); 
            this.slider(); 
      },
      computed:{  
        newin(){  
            return this.$store.getters.NewIn 
        },  
    },
      
    }
</script>