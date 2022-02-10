<template>
    <span class="masterview" v-if="renderComponent">
      <div class="product_bredcrumbs">
        <a href="#"><span>← back </span> What's New</a>
      </div>
      <div class="preloader" >

      </div>
      <div class="outslider_loading" v-if="Preloader">
            <div class="la-ball-scale-ripple-multiple la-dark la-2x">
                  <i  class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
            </div>
      </div>

      <section class="product_single_area">
            <div class="product_single_left" v-if="NewItemSlider && NewItemSlider.length>0">
                  <div class="single_img">
                        <div class="slide" v-for="video in NewItemSlider" :key="video.id">
                              <img v-if="video.compressed_image_path" :src="'/' + video.compressed_image_path" class="img-fluid" alt="">
                              <img v-else :src="'/' + default_img" class="img-fluid" alt="">
                        </div>
                  </div>
                  <div class="single_img_thumbnail">
                        <div class="slide" v-for="video in NewItemSlider" :key="video.id">
                              <img v-if="video.compressed_image_path" :src="video.compressed_image_path" class="img-fluid" alt="">
                              <img v-else :src="'/' + default_img" class="img-fluid" alt="">
                        </div>
                  </div>
            </div>
            <div class="product_single_left" v-else>
                  <div class="single_img">
                        <img :src="'/' + default_img" alt="" class="img-fluid" >
                  </div>
            </div>
            <div class="product_single_right">
                  <p class="p_cat" v-if="SingleProduct.brand">{{SingleProduct.brand.name}}</p>
                  <h2 class="product_title" v-if="SingleProduct.product">{{SingleProduct.product.name}}  <span v-if="SingleProduct.firstcolor"> - {{SingleProduct.firstcolor.name}}</span></h2>
                  <h3 class="product_price" v-if="SingleProduct.product" >USD ${{SingleProduct.product.price}}</h3>
                  <h3 class="product_price"  v-if="SingleProduct.itemsize && SingleProduct.itemsize.length>0">Size</h3>
                  <ul class="single_item_sizes" v-if="SingleProduct.itemsize && SingleProduct.itemsize.length>0">
                        <template v-for="size in SingleProduct.itemsize"  >
                              <li @click="SelectSize(size.id)"  v-bind:key="{ active: form.size == size.id }" ><span>{{size.name}} / {{size.item_size}}</span></li>
                        </template>
                  </ul>
                  <div class="add_to_bag">
                  <div class="add_size" >
                        <!-- <div class="size_box" v-if="SingleProduct.itemsize && SingleProduct.itemsize.length>0">
                              <label>Size</label>
                              <select class="form-control size_selector" v-model="form.size" name="size" >
                                    <option>Choose an Option...</option>
                                    <option v-for="size in SingleProduct.itemsize" :value="size.id" :key="size.id" v-if="size.color_id == colorform.color"> {{size.name}} / {{size.item_size}}</option>
                              </select>
                        </div> -->
                        <div class="size_box color_box"  v-if="SingleProduct.colors && SingleProduct.colors.length>0" >
                              <label>Color </label>
                              <select class="form-control size_selector" v-model="form.colors" name="colors" @change="onChange(form.colors)">
                                    <option value="0">AlLL</option>
                                    <option v-for="color in SingleProduct.colors" :value="color.color_id" :key="color.id"> {{color.name}}</option>
                              </select>
                        </div>
                        <div class="p_quantity">
                              <label>Qty: </label>
                              <input type="text" class="form-control" placeholder="0"   name="qty" v-model="form.qty" >
                              <has-error :form="form" field="qty"></has-error>
                        </div>
                  </div>
                  <div class="add_cart">
                        <div class="add_cart_btn">
                              <button class="btn_common" v-if="SingleProduct.product" :id="SingleProduct.product.id" v-on:click="AddToCart($event)" ><i class="fas fa-shopping-cart"></i> <span class="ml_5"  >ADD TO BAG <i v-if="AddCartLoader" class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></span> </button>
                        </div>
                        <div class="p_like" v-bind:class="{ p_like_toggler: isActive }" @click="AddToWishlist(SingleProduct.product.id)"><i class="fas fa-heart"></i></div>
                  </div>
                  </div>

                  <div class="product_description">
                  <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                              <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Description</a>
                        </li>
                        <li class="nav-item">
                              <a class="nav-link" id="profile-tab" data-toggle="tab" href="#SizeChart" role="tab" aria-controls="profile" aria-selected="false">Size Chart</a>
                        </li>
                        <li class="nav-item">
                              <a class="nav-link" id="contact-tab" data-toggle="tab" href="#Returns" role="tab" aria-controls="contact" aria-selected="false">Returns</a>
                        </li>
                  </ul>
                  <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="home-tab" v-if="SingleProduct.product" v-html="SingleProduct.product.details"></div>
                        <div class="tab-pane fade" id="SizeChart" role="tabpanel" aria-labelledby="profile-tab" v-if="SingleProduct.sizechart" v-html="SingleProduct.sizechart.content"></div>
                        <div class="tab-pane fade" id="Returns" role="tabpanel" aria-labelledby="contact-tab" v-if="SingleProduct.returns" v-html="SingleProduct.returns.content"></div>
                  </div>
                  </div>
            </div>
      </section>
      <section class="related_product_area" v-if="SingleProduct.relatedItem && SingleProduct.relatedItem.length>0">
            <div class="related_product_title">
                  <h2>SOME OTHER THINGS YOU MIGHT LIKE</h2>
            </div>
            <div class="related_product_content">
                  <div class="product_content_wrap">
                        <div class="product_content_inner" v-for="item in SingleProduct.relatedItem" :key="item.id">

                              <div class="product_inner_wrap" @click="RelatedItem()" >
                                    <router-link :to="{ path: `/single/${item.slug}` }">
                                          <img v-if="item.images[0]" :src="'/' + item.images[0].compressed_image_path" alt="" class="img-fluid">
                                          <img v-else :src="'/' + default_img" class="img-fluid" alt="">
                                    </router-link>
                                    <div class="product_details_text">
                                    <h2 class="product_title"> <router-link :to="{ path: `/single/${item.slug}` }"> {{item.name}} </router-link> </h2>
                                    <p class="product_price"><span v-if="item.brand">{{item.brand.name}} | </span> <span>USD ${{item.price}}</span></p>


                                    <div class="product_on_hover">
                                          <ul class="p_list" v-if="item.sizes && item.sizes.length > 0">
                                                <li v-for="(size,index) in item.sizes" :key="index">{{size.item_size}}</li>
                                          </ul>
                                    </div>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>

            <div class="r_p_c_mobile">
                  <div id="related_mobile" class="owl-carousel owl-theme" >
                        <div class="home_slider_inner" v-for="(item,index) in SingleProduct.relatedItem" :key="index">
                              <div class="home_slider_inner_content">
                                    <router-link :to="{ path: `/single/${item.slug}` }">
                                          <img v-if="item.images[0]" :src="'/' + item.images[0].compressed_image_path" alt="" class="img-fluid">
                                          <img v-else :src="'/' + default_img" class="img-fluid" alt="">
                                    <h2>{{item.name}}</h2>
                                    <p>{{item.brand.name}} | USD ${{item.price}}</p>
                                    </router-link>
                              </div>
                        </div>
                  </div>
            </div>
      </section>
      <section class="footer_newsletter_area">
        <div class="footer_newsletter_title">
            <h2 v-if="SingleProduct.topnotification" v-html="SingleProduct.topnotification.value"></h2>
            <p>By signing up to our Well Made Weekly, which will keep your fashion industry cocktail chatter (not to mention your wardrobe) strong.</p>
        </div>
        <div class="footer_newsletter_form">
            <input type="text" class="form-control" placeholder="Please enter your email address">
            <button class="btn_common">Join</button>
        </div>
    </section>

    </span>
</template>

<script>

import VueSlickCarousel from 'vue-slick-carousel'
import 'vue-slick-carousel/dist/vue-slick-carousel.css'
// optional style for arrows & dots
import 'vue-slick-carousel/dist/vue-slick-carousel-theme.css'

export default {
    name:'Header',
    metaInfo() {
        return {
            title: this.metaData.pageTitle,
             meta: [
                { charset: this.metaData.charset },
                { name: 'viewport', content: this.metaData.viewport },
                { name: 'title', content: this.metaData.title },
                { name: 'description', content: this.metaData.description }
             ]
        }
     },
    data(){
            return{

                id:'',
                Preloader:true,
                renderComponent: true,
                Item:'',
                NewItemSlider: [],
                default_img:[],
                isActive: '',
                  form: new Form({
                        userid: '',
                        qty: '',
                        itemId: '',
                        size: '',
                        colors: '',
                  }),
                  colorform: new Form({
                        itemId: '',
                        color: '',
                  }),

                  wishlist: new Form({
                        id: '',
                        user: '',
                  }),
                  AddCartLoader:false,
            }
    },
    components:{
            VueSlickCarousel
    },
    mounted(){
          this.CallRelatedItem();
          this.forceRerender();

    },
    computed:{
            metaData() {
                  return {
                        pageTitle: this.$store.getters.SingleItem.product_page_title,
                        charset: 'utf-8',
                        viewport: 'width=device-width, initial-scale=1, shrink-to-fit=no',
                        title: this.$store.getters.SingleItem.product_meta_title,
                        description: this.$store.getters.SingleItem.product_meta_description
                  }
            },
            SingleProduct(){
                  var self = this;
                  setTimeout(function(){ self.Preloader = false; }, 2000);
                        return this.singleItemData()
                  }
    },
      methods:{

            singleItemData(){
                this.Item = this.$store.getters.SingleItem
                if(this.Item.colors && this.Item.colors.length >0){
                      $(".product_single_right .add_cart").removeClass('width_55');
                      $(".product_single_right .add_size").removeClass('width_45');
                      $(".product_single_right .add_size .p_quantity").removeClass('width_100');
                }else{
                      $(".product_single_right .add_cart").addClass('width_55');
                      $(".product_single_right .add_size").addClass('width_45');
                      $(".product_single_right .add_size .p_quantity").addClass('width_100');
                }

                this.NewItemSlider = this.Item.images
                  if(this.Item.default_img){
                        this.default_img = this.Item.default_img.image_path;
                  }
                  if(this.Item.wishlist != null){
                        this.isActive= true;
                  }else{
                        this.isActive= false;
                  }
                  return this.Item;
            },
            AddToCart(event){
                  if(this.$store.getters.GetLoginUserData){
                        this.form.userid = this.$store.getters.GetLoginUserData.id;
                  }
                  this.AddCartLoader = true;
                  this.form.itemId = event.currentTarget.id
                  this.form.post('/api/v1/add-to-cart')
                        .then((result) => {
                              this.$store.dispatch('GetCartItem');
                              $('.count_cart_item b').html(result.data.qty);
                              this.AddCartLoader = false;
                        }).catch((err) => {
                              this.AddCartLoader = false;
                        });
                  this.form.reset ();

                  var elem = document.querySelector("#cart");
                  elem.style.display = 'block';

            },
            ShowCartItems(){
                return this.$store.getters.ShowCartItems
            },
            CallRelatedItem(){
                  this.$store.dispatch('GetSingleProductInfo', this.$route.params.slug);
            },
            AddToWishlist(id){
                  let user = localStorage.getItem('UserData')
                    if(user != 'null'){
                        this.wishlist.user = JSON.parse(user).id;
                    }
                  this.wishlist.id = id
                  this.wishlist.post('/api/v1/add-to-wishlist')
                  .then((result) => {
                        if(result.data.status == 'add'){
                              this.isActive= true
                        }else{
                              this.isActive= false
                        }
                  }).catch((err) => {

                  });
            },
            sliderCall(){
                  $('.single_img').slick({
                  accessibility: false,
                  slidesToShow: 1,
                  slidesToScroll: 1,
                  dots: false,
                  arrows: true,
                  fade: false,
                  adaptiveHeight: true,
                  infinite: false,
                  useTransform: true,
                  speed: 400,
                  cssEase: 'cubic-bezier(0.77, 0, 0.18, 1)',
                  });

                  $("#related_mobile").owlCarousel({
                  loop: false,
                  margin: 0,
                  lazyLoad: true,
                  smartSpeed: 1500,
                  autoplay: false,
                  nav: true,
                  dots: false,
                  navText: ["<i class='lni lni-chevron-left'></i>", "<i class='lni lni-chevron-right'></i>"],
                  responsive: {
                        0: {
                              items: 2
                        },
                        400: {
                              items: 2
                        },
                        768: {
                              items: 3
                        },
                        1200: {
                              items: 4
                        }
                  }
                  });
            },
            slider(){
                  var vm = this;
                  axios.get('')
                  .then((res)=>{
                        Vue.nextTick(function(){
                              vm.sliderCall();
                        }.bind(vm));
                  })
            },
            onChange(value){
                  this.renderComponent = false;
                  this.Preloader=true;
                  var newthis = this;
                  this.colorform.color=value
                  this.colorform.itemId=this.Item.product.id
                  this.colorform.post('/api/v1/product_image')
                  .then((result)=>{
                        this.NewItemSlider = [];
                        this.NewItemSlider = result.data.images
                        this.forceRerender();
                        this.Preloader=false;
                        this.renderComponent = true;
                  })
            },
            forceRerender() {
                  var newthis = this;
                  this.renderComponent = false;
                  Vue.nextTick(function(){
                        this.slider();
                        this.renderComponent = true;
                  }.bind(newthis));

            },
            RelatedItem(){
                  //this.forceRerender();
            },
            SelectSize(id){
                  return this.form.size = id
            },

    },
    watch:{
          $route(to,from){
            this.CallRelatedItem();
            this.forceRerender();
        }

    }
}




</script>
