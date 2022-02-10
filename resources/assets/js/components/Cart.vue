<template>
      <span class="cart_component">
            <section class="cart_area" v-if="ShowCartItems.cartitems">
                  <div class="cart_title">
                        <h2>Shopping Cart</h2>
                  </div>
                  <div class="cart_content">
                        <div class="cart_left">
                        <div class="cart_table">
                              <table class="table">
                                    <colgroup>
                                    <col width="12%">
                                    <col width="50%">
                                    <col width="15%">
                                    <col width="15%">
                                    <col width="5%">
                                    </colgroup>
                                    <thead>
                                    <tr>
                                          <th>Product</th>
                                          <th></th>
                                          <th>Price</th>
                                          <th>Quantity</th>
                                          <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="item in ShowCartItems.cartitems" :key="item.id">
                                          <td>
                                                <div class="cart_product">
                                                      <router-link :to="`/single/${item.item.slug}`">
                                                            <img v-if="item.item_images[0]" :src="item.item_images[0].thumbs_image_path" style="max-width: 100px;" class="img-fluid" alt="">
                                                      </router-link> 
                                                </div>
                                          </td>
                                          <td>
                                                <div class="cart_product_name">
                                                <h2><router-link :to="`/single/${item.item.slug}`">{{item.item.name}} <span v-if="item.color"> - {{item.color.name}}</span> </router-link></h2>
                                                <p v-if="item.item.brand">{{item.item.brand.name}}</p>
                                                <ul v-if="item.itemsize">
                                                      <li >size : <span>{{item.itemsize.item_size}}</span></li> 
                                                </ul>
                                                </div>
                                          </td>
                                          <td>${{item.item.price }}</td>
                                          <td>
                                                <div class="num_count">
                                                <input type="text" :value="item.quantity" class="qty" :id="'qty'+item.id" >
                                                <button :id="item.id" v-on:click="UpdateCartItem($event)">update</button>
                                                </div>
                                          </td>
                                          <td><span class="close" :id="item.id" v-on:click="DeleteCartItem($event)"><i class="fas fa-times"></i></span></td>
                                    </tr> 

                                    </tbody>
                              </table>
                              <div class="update_cart">
                                    <!-- <span>Update Cart</span> -->
                              </div>
                        </div>

                        <div class="checkout_table_summery cart_table_mobile">
                              <table class="table mb_0">
                                    <colgroup>
                                    <col style="width: 92px;">
                                    <col style="width: calc(100% - 92px);">
                                    </colgroup>
                                    <tbody>
                                          <tr v-for="item in ShowCartItems.cartitems" :key="item.id">
                                                <td>
                                                      <div class="c_t_img">
                                                            <img v-if="item.item_images[0]" :src="item.item_images[0].thumbs_image_path" style="max-width: 70px;" class="img-fluid" alt="">
                                                      </div>
                                                </td>
                                                <td>
                                                      <div class="c_t_text">
                                                            <h2>{{item.item.name}} <span v-if="item.color"> - {{item.color.name}}</span></h2>
                                                            <p v-if="item.item.brand">{{item.item.brand.name}}</p>
                                                            <ul>
                                                                  <li v-if="item.itemsize">Size: <span>{{item.itemsize.item_size}}</span></li>
                                                                  <li>Price: <span>USD${{item.item.price}}</span></li>
                                                                  <li>Qty: <span><input type="text" :value="item.quantity" class="qty" :id="'qty_m'+item.id" >  </span>
                                                                  <span><button :id="item.id" v-on:click="UpdateCartItem($event)">update</button></span>
                                                                  <span class="close" :id="item.id" v-on:click="DeleteCartItem($event)"><i class="fas fa-times"></i></span>
                                                                  </li>
                                                            </ul>
                                                      </div>
                                                </td>
                                          </tr> 

                                    </tbody>
                              </table>
                              <div class="update_cart">
                                    <!-- <span>Update Cart</span> -->
                              </div>
                        </div>

                        <div class="promo_code_wrap">
                              <!-- <div class="promo_code">
                                    <ul>
                                    <li data-toggle="collapse_slide" data-target="#promo_code">Promotional code / Gift voucher code</li>
                                    </ul>
                                    <div class="promo_code_details" id="promo_code">
                                    <div class="promo_code_details_inner">
                                          <input type="text" class="form-control" placeholder="Enter Promotional code / Gift voucher">
                                          <button class="btn_common">apply</button>
                                    </div>
                                    </div>
                              </div> -->
                        </div>
                        </div>
                        <div class="cart_right">
                        <div class="cart_right_table">
                              <table class="table">
                                    <tr>
                                    <td>SUBTOTAL</td>
                                    <td>USD${{ShowCartItems.total | NumberFormat}}</td>
                                    </tr>
                                    <tr>
                                    <td><b>GRAND TOTAL</b></td>
                                    <td><b>USD${{ShowCartItems.total | NumberFormat}}</b></td>
                                    </tr>
                              </table>
                        </div>
                        <div class="proceed_btn text-center">
                              <button class="btn_common" @click="ContinueCheckout"><i class="lni-lock"></i> <span class="ml_5">Pay Securely Now <i v-if="CartLoader" class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></span> </button>
                              <p>-OR-</p>
                              <!-- <a href="#"><img src="assets/media/images/paypal.svg" alt="" class="img-fluid"></a> -->
                        </div>
                        </div>
                  </div>
                  <div class="shipping_return">
                        <div class="s_r_inner">
                        <h2>SHIPPING</h2>
                        <p>Is free, or just a little fee. View our full shipping rates and information 
                              <router-link to="/shipping-returns">here.</router-link>
                        </p>
                        </div>
                        <div class="s_r_inner">
                        <h2>RETURNS</h2>
                        <p>If you are unhappy with your item for some reason (which we're sure you won't be), you can return or exchange it. View our full returns and exchanges policy <router-link to="/terms-conditions">here.</router-link>
            
                              Please note we don't allow returns or exchanges on sale items, so please keep that in mind before purchasing.</p>
                        </div>
                  </div>
            </section>
            <div class="p_d_mobile">
                  <ul>
                        <!-- <li data-toggle="collapse_noslide" data-target="#Promotional">Promotional code / Gift voucher code</li>
                        <div class="p_d_mobile_inner" id="Promotional">
                        <div class="promo_code_details_inner">
                              <input type="text" class="form-control" placeholder="Enter Promotional code / Gift voucher">
                              <button class="btn_common">apply</button>
                        </div>
                        </div> -->
                        <li data-toggle="collapse_noslide" data-target="#shipping">shipping</li>
                        <div class="p_d_mobile_inner" id="shipping">
                        <p>Is free, or just a little fee. View our full shipping rates and information <a href="#"> here.</a></p>
                        </div>
                        <li data-toggle="collapse_noslide" data-target="#Returns11">returns</li>
                        <div class="p_d_mobile_inner" id="Returns11">
                        <p>If you are unhappy with your item for some reason (which we're sure you won't be), you can return or exchange it. View our full returns and exchanges policy <a href="#"> here.</a>
            
                              Please note we don't allow returns or exchanges on sale items, so please keep that in mind before purchasing.</p>
                        </div>
                  </ul>
            </div>
            <subscriber></subscriber> 
      </span>
</template>
<script>  
 
export default {
      name:'Header',
      data(){
            return{
                  id:'',
                  CartLoader:false,
                  Item: new Form({
                        qty: '',
                        id: '' 
                  }),
                  DeleteCart: new Form({ 
                        id: '' 
                  })
            }
      },
      components:{
            subscriber: () => import(/* webpackChunkName: "js/subscriber" */ '../components/default/Subscription.vue'),
      },
      mounted(){ 
            this.$store.dispatch('GetCartItem');
      },
      computed:{ 
            ShowCartItems(){ 
                return this.$store.getters.ShowCartItems
            },
      },
      methods:{   
           UpdateCartItem(event){ 
                 console.log(event.currentTarget.id)
                 this.Item.reset ();
                  if(window.innerWidth>1024){
                        this.Item.qty = $("#qty"+event.currentTarget.id).val();
                  }else{
                        this.Item.qty = $("#qty_m"+event.currentTarget.id).val();
                  }
                  
                  this.Item.id = event.currentTarget.id; 
                  this.Item.post('/api/v1/update-cart')
                 .then((result) => {
                       toast.fire({
                              icon: 'success',
                              title: 'Cart Item Updated successfully'
                              })
                       this.$store.dispatch('GetCartItem');
                 }).catch((err) => { 
                 });
           },
           DeleteCartItem(event){
                 this.DeleteCart.reset ();
                 this.DeleteCart.id= event.currentTarget.id;
                 this.DeleteCart.post('/api/v1/delete-cart')
                 .then((result) => {
                       toast.fire({
                              icon: 'success',
                              title: 'Cart Item Deleted successfully'
                              })
                       this.$store.dispatch('GetCartItem');
                 }).catch((err) => {
                       
                 });
           },
           ContinueCheckout(){
                 let check = this.$store.getters.ShowCartItems
                  if(check.cartitems.length > 0){
                        this.CartLoader = true;
                        if(this.$store.getters.GetUserDataCheck == 'null'){
                              this.CartLoader = false;  
                        this.$router.push('/login')
                        }else{
                              axios.get('/api/v1/cratecheckout', { headers: { 'Authorization': 'Bearer '+localStorage.getItem('access_token')}})
                              .then((response)=>{   
                                    this.CartLoader = false; 
                                    this.$router.push('/order?id='+response.data.message)
                              })   
                        }
                  }else{
                        toast.fire({
                              icon: 'error',
                              title: 'Your Cart Empty ..!', 
                              })
                  }
            
           }
      },
      watch:{ 
      }
}
</script>