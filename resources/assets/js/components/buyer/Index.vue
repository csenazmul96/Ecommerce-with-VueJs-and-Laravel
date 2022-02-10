<template>
      <span class="masterview">    
            <div class="account_bredcrumbs">
                 <div class="a_c_bredcrumbs_left">
                       <a href="#">← back </a>
                 </div>
                <div class="a_c_bredcrumbs_right" @click.prevent="LogOut()"> 
                      <router-link  to="">Log Out</router-link>
                </div>
           </div>
            <div class="my_account_dashboard ">
                  <div class="m_a_dashboard_title my_acc_container">
                        <h2>My Account Dashboard</h2>
                        <p>From your My Account Dashboard you have the ability to view a snapshot of your recent account activity and update your account information. <br> Select a link below to view or edit information.</p>
                  </div> 
                  <div class="wishlist_area my_acc_container">
                        <h2 class="my_acc_subtitle">MY WISHLIST <i v-if="UserWishlistData && UserWishlistData.length>0">({{UserWishlistData.length}})</i> </h2>
                        <div class="wishlist_item" v-if="UserWishlistData && UserWishlistData.length>0"> 
                              <div class="product_content_inner" v-for="(item,index) in UserWishlistData" :key="index">
                                    <div class="product_inner_wrap"> 
                                          <router-link :to="`/single/${item.slug}`">
                                                <img :src="'/' + item.images[0].compressed_image_path" alt="" class="img-fluid">
                                          </router-link> 
                                          <div class="product_details_text">
                                          <h2 class="product_title"> <router-link :to="`/single/${item.slug}`">{{item.name}}</router-link></h2>
                                          <p class="product_price"><span v-if="item.brand">{{item.brand.name}} </span> | <span>USD${{item.price}}</span></p>
                                          <div class="remove_wishlist">
                                                <span @click="RemoveToWishlist(item.id)">Remove</span>
                                                <span><i class="far fa-check-circle"></i> In stock</span>
                                          </div>
                                          </div>
                                    </div>
                              </div> 
                        </div>        
                        <h4 v-else class="text-center" >Wishlist Empty !!</h4> 
                    <router-link v-if="UserWishlistData && UserWishlistData.length>0" to="/wishlist" ><button class="btn_grey">View All Wishlist Items</button></router-link>
                    </div>
                  </div>
                  <div class="my_acc_container">
                        <div class="account_info buyerprofile">
                              <div class="account_info_left col-md-2 col-12"> 
                                    <ul  id="buyerprofileselectmenu" @click="ShowMobileMenu()">
                                          <li><span>Dashboard</span></li>
                                    </ul>
                                    <ul id="ProfileMenus"> 
                                          <li @click.prevent="SelectMenu('dashboard')" data-name="'sfsdfsdf'" v-bind:class="{ active: Menus.active == 'dashboard' }"><span>Dashboard</span></li> 
                                          <li @click.prevent="SelectMenu('settings')" data-name="'sfsdfsdf'" v-bind:class="{ active: Menus.active == 'settings' }"><span>Settings</span></li> 
                                          <!-- <li @click.prevent="SelectMenu('wishlist')" v-bind:class="{ active: Menus.active == 'wishlist' }"><span>Wishlist</span></li> -->
                                          <li @click.prevent="SelectMenu('address')"  v-bind:class="{ active: Menus.active == 'address' }"><span>Address</span></li>
                                          <li @click.prevent="SelectMenu('orders')"   v-bind:class="{ active: Menus.active == 'orders' }"><span>Orders</span></li>
                                          <li @click.prevent="SelectMenu('password')" v-bind:class="{ active: Menus.active == 'password' }"><span>Change Password</span></li> 
                                    </ul>
                              </div>
                              <Dashboard        v-show="Menus.active == 'dashboard'"></Dashboard>
                              <BuyerAddress     v-show="Menus.active == 'address'"></BuyerAddress>
                              <BuyerOrders      v-show="Menus.active == 'orders'"></BuyerOrders>
                              <BuyerSettings    v-show="Menus.active == 'settings'"></BuyerSettings>
                              <Password         v-show="Menus.active == 'password'"></Password>
                              <OrderSingle      v-show="Menus.active == 'orderdetails'" v-if="rebuield" :orderid="OrderId" ></OrderSingle> 
                        </div> 
                  </div>
                  <div class="my_acc_container">
                        <h2 class="my_acc_subtitle">MY SAVED CREDIT CARDS</h2>
                        <p>You do not have any saved credit cards.</p>
                  </div> 
                  <div class="my_acc_container">
                        <h2 class="my_acc_subtitle">SUBSCRIPTION</h2>
                        <div class="custom_radio">
                        <input type="radio" id="Checkme1" name="radio">
                        <label for="Checkme1">Subscribed to the well made clothes subscription.</label>
                        </div>
                  </div>
                  <div class="my_acc_container">
                        <div class="my_acc_back">
                        <!-- <button class="btn_transparent width_240p"></button> -->
                         <router-link  to="/" ><button class="btn_transparent width_240p">Continue Shopping</button></router-link>
                        <button class="btn_transparent width_240p" @click.prevent="LogOut()">Log Out</button>
                        </div>
                  </div>
            </div> 
            
            <section class="footer_newsletter_area">
                  <div class="footer_newsletter_title">
                        <h2>Get $10 Off Your First Purchase </h2>
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
import buyerprofile from './ProfileTopSection.vue'; 
import BuyerAddress from './Address.vue';
import BuyerOrders from './Orders.vue';
import BuyerSettings from './Settings.vue';
import OrderSingle from './OrdersSingle.vue';
import Password from './PasswordSetting.vue';
import Dashboard from './Dashboard.vue';
export default {
    name:'buyer-index',
    data(){
            return{
                id:'', 
                  wishlist: new Form({
                        id: ''
                  }),
                  rebuield: true,
                  ResetPassword: new Form({ 
                        password: '', 
                        password_confirmation: '', 
                  }),
                  Menus: new Form({  
                        'active': 'dashboard',
                  }),
                  OrderId: null,
            }
    }, 
    components:{
            'Dashboard'       : Dashboard, 
            'buyerprofile'    : buyerprofile, 
            'BuyerAddress'    : BuyerAddress,
            'BuyerOrders'     : BuyerOrders,
            'BuyerSettings'   : BuyerSettings,
            'OrderSingle'     : OrderSingle,
            'Password'        : Password, 
      },
    computed:{
          UserProfileData(){  
            return this.UserProfile();
          } ,
          UserWishlistData(){ 
              return this.$store.getters.Wishlist 
          } 
    },
    mounted(){  
            this.ACL(); 
            if(this.$options.filters.CheckAuth() == false){ 
                  this.$router.push('/login');
                  this.GetCartItemHeader() ; 
                  this.$store.dispatch('LogoutUser');
            }else{
                  this.$store.dispatch('GetProfileInformation');
            }
            this.$store.dispatch('GetProfileWishlist');
    }, 
    methods:{ 
            SelectMenu(name){  
                this.Menus.active = name; 
                 $('#buyerprofileselectmenu li span').html(name) 
                 $('#ProfileMenus').toggleClass('d_block') 
                return this.Menus
            },
            UserProfile(){   
                  return this.$store.getters.ProfileInfo
            },
            GetCartItemHeader(){  
                return this.$store.getters.ShowCartItems
            },
            UserWishlist(){
                  return this.$store.getters.Wishlist
            },
            RemoveToWishlist(id){
                  let user = localStorage.getItem('UserData')  
                    if(user != 'null'){ 
                        this.wishlist.user = JSON.parse(user).id;
                    } 
                this.wishlist.id = id   
                this.wishlist.post('/api/v1/remove_wishlist') 
                .then((result) => {   
                    if(result.data.status == 'success'){ 
                        toast.fire({
                            icon: 'success',
                            title: result.data.message
                        }) 
                    }else{ 
                        toast.fire({
                            icon: 'error',
                            title: result.data.message
                        }) 
                    }
                    this.$store.dispatch('GetProfileWishlist'); 
                }).catch((err) => {
                    
                });
            }, 
            ChangePassword(){
                  $(".reset_password_wrap").toggleClass('d_none');
            },
            ReserPasswordSubmit(){ 
                  this.ResetPassword.post('/api/v1/change-password',{ headers: { 'Authorization': 'Bearer '+localStorage.getItem('access_token')}})
                  .then((result) => { 
                        if(result.data.status == 'oldpassword') { 
                              toast.fire({
                              icon: 'worning',
                              title: 'Old Password Incorrect.'
                              })
                        }else{
                              toast.fire({
                              icon: 'success',
                              title: 'Password Changed Successfully.'
                              })
                              this.ResetPassword.reset();
                              $(".reset_password_wrap").addClass('d_none');
                        }
                  }).catch((err) => {
                        
                  });
            }, 
            someMethod(order){ 
                  this.Menus.reset();
                  this.Menus.active = 'orderdetails';
                  this.OrderId = order.id
                  this.rebuield = false
                  this.$nextTick(() => { 
                        this.rebuield = true
                  }) 
            }, 
            ParentMenuTriger(menu){
                  this.Menus.reset();
                  if(menu == 'ResetPassword'){
                        return this.Menus.active = 'password';
                  }else if(menu == 'ManageAddress'){
                        return this.Menus.active = 'address';
                  }else if(menu == 'UpdateDetails'){
                        return this.Menus.active = 'settings';
                  }
            },
            LogOut(){     
                  this.$parent.ParentAuthMethod(0);  //User LogOut Notification Send to Master component
                  this.$store.dispatch('LogoutUser');
                  this.GetCartItemHeader() ;
                  this.$store.getters.CheckTokenIsvalid   
                  this.$parent.Rebuildheader(0);  //User LogOut Notification Send to Master component
                  this.$router.push('/');    
            },
            ACL(){  
                  if(this.$store.getters.CheckTokenIsvalid == 'null'){
                        this.$router.push('/login');
                  } 
                return this.$store.getters.CheckTokenIsvalid; 
            },
            ShowMobileMenu(value){
                  $('#ProfileMenus').toggleClass('d_block') 
                  $("#buyerprofileselectmenu").toggleClass('active');
            }
    },
    watch:{  
      $route(to,from){ 
            this.UserProfile(); 
            this.UserWishlist(); 
      } 
    }
}
 
 
</script>