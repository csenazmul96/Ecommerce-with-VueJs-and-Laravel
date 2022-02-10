<template>  
      <header class="header_area Common_header">
        <div class="main_header cm_header fixed-top show_desktop">
            <div class="logo"> 
               <router-link to="/"><img :src="HeaderContent.logo" alt=""></router-link> 
            </div>
            <div class="header_nav"> 
                <ul> 
                    <template v-for="(category, index) in HeaderCats" v-if="category.subCategories && category.subCategories.length"  > 
                        <li  data-toggle="collapse_slide" :data-target="'#'+category.name" ><a href="#">{{category.name | capitalize}} </a></li>
                              <div class="submenu" v-bind:id="[category.name]">
                                    <div class="container-fluid">
                                    <div class="row"> 
                                          <div class="col-3" v-for="(subcat,subindex) in category.subCategories" :key="subindex">
                                                <div class="submenu_list">
                                                      <h2><router-link :to="`/${category.slug}/${subcat.slug}`" v-model="subcat.id">{{subcat.name}}</router-link></h2>
                                                      <ul>
                                                            <li v-for="(thirdcat, thirdindex) in subcat.thirdcategory" :key="thirdindex"> 
                                                                  <router-link :to="`/${category.slug}/${subcat.slug}/${thirdcat.slug}`" v-model="thirdcat.id">{{thirdcat.name}}</router-link>
                                                            </li> 
                                                      </ul>
                                                </div>
                                          </div> 
                                    </div>
                              </div>
                        </div>   
                        </template>
                        <template v-else>
                            <li><router-link :to="`/${category.slug}`" v-model="category.id">{{category.name | capitalize}}</router-link></li>
                        </template>
                </ul>
            </div>
            <div class="header_others">
                <ul>
                    <li data-toggle="collapse_noslide" data-target="#search"><i class="fas fa-search"></i></li>
                    <div class="header_search h_o_dropdown" id="search">
                        <div class="header_search_form">
                            <form role="form" >
                                <div class="header_search_form_inner">
                                    <input type="text" class="form-control"  @keyup="SearchPost" v-model="keyword">
                                    <button class="btn" @click.prevent="SearchPost" ><i class="fas fa-search"></i></button>
                                    <span>close</span>
                                </div>
                            </form>
                            
                        </div>
                        <p class="search_text">PLEASE TYPE AT LEAST 4 CHARACTERS FOR SEARCH SUGGESTIONS.</p>
                        <!-- <div class="header_search_details">
                            <div class="h_s_details_inner_wrap">
                                <div class="h_s_details_inner_left">
                                    <p>Suggested links</p>
                                    <ul>
                                        <li><a href="#">seljak brand</a></li>
                                        <li><a href="#">Seiko</a></li>
                                        <li><a href="#">services</a></li>
                                        <li><a href="#">SEEKER X RETRIEVER Two-Way Light</a></li>
                                        <li><a href="#">Sexy</a></li>
                                        <li><a href="#">seeker</a></li>
                                        <li><a href="#">SELECT pg_sleep(25)--</a></li>
                                    </ul>
                                </div>
                                <div class="h_s_details_inner_right">
                                    <p>Suggested links</p>
                                    <ul>
                                        <li>
                                            <a href="#"><img src="assets/media/images/product/product-1.jpg" alt=""></a>
                                            <div class="a_r_text">
                                                <h3><a href="#">Idalika Top - Nude</a></h3>
                                                <p><span>KINGS OF INDIGO </span> | <span>USD$104.00</span> </p>
                                            </div>
                                        </li>
                                        <li>
                                            <a href="#"><img src="assets/media/images/product/product-2.jpg" alt=""></a>
                                            <div class="a_r_text">
                                                <h3><a href="#">Idalika Top - Nude</a></h3>
                                                <p><span>KINGS OF INDIGO </span> | <span>USD$104.00</span> </p>
                                            </div>
                                        </li>
                                        <li>
                                            <a href="#"><img src="assets/media/images/product/product-3.jpg" alt=""></a>
                                            <div class="a_r_text">
                                                <h3><a href="#">Idalika Top - Nude</a></h3>
                                                <p><span>KINGS OF INDIGO </span> | <span>USD$104.00</span> </p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div> -->
                    </div>   
                    <li v-show="LoginCheck" data-toggle="collapse_noslide" data-target="#profile"><i class="far fa-user"></i></li>
                    <li v-show="! LoginCheck"  data-toggle="collapse_noslide" data-target="#user"><i class="far fa-user"></i></li> 
                    <div class="header_account h_o_dropdown profile_dropdown" id="profile" v-bind:style='{"display": ( ! LoginCheck?  "none !important" : "" )}'>
                        <div class="header_account_inner">
                            <ul>
                                <li><router-link to="/buyer/profile"> My Account  </router-link></li>
                                <li @click.prevent="LogOut()"><router-link to="#"> Log Out </router-link></li>
                            </ul>       
                        </div>
                    </div>
                    <div class="header_account h_o_dropdown" id="user" v-bind:style='{"display": (LoginCheck?  "none !important" : "" )}'>
                        <div class="header_account_inner">
                            <div id="errorMessage" class="text-danger"></div>
                            <form role="form" >
                                <div class="form_inline">
                                    <label class="required">Email</label>
                                    <input type="text" class="form-control"  name="email" placeholder="Email Address 2" v-model="LoginForm.email">
                                    <span v-if="FormError.email" class="has-error">{{FormError.email[0]}} </span>
                                </div>
                                <div class="form_inline">
                                    <label for="staticEmail">Password</label>
                                    <input type="password" class="form-control" name="password" placeholder="Password" v-model="LoginForm.password">
                                    <span v-if="FormError.password" class="has-error">{{FormError.password[0]}}</span>
                                </div>
                                 <span v-if="FormError.notfound" class="has-error">{{FormError.notfound[0]}}</span>
                                 <br>
                                <button class="btn_common" @click.prevent="LoginSubmit()">login <i v-if="CartLoader" class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></button>
                            </form>
                            <div class="d_flex_center mt_10 mb_10" @click="ForgetPassword()"> 
                                <router-link to="/forgot-password" class="text-center td_underline tt_u">Forgot Password</router-link>
                            </div>
                            <p  class="text-center tt_u mb_10">or</p>
                             
                            <div class="create_acc_btn">
                                <button class="btn_transparent" @click="CreateNewAccount()"> <router-link to="/registration">create an account</router-link> </button>
                            </div>
                        </div>
                    </div>
                     
                    <li> <router-link to="/wishlist"> <i class="fas fa-heart"></i></router-link></li>
                    <li data-toggle="collapse_noslide" data-target="#cart" class="h_cart" ><span class="count_cart_item"><i class="fas fa-shopping-cart"></i> <b v-if="ShowCartItems.cartitems">{{ShowCartItems.cartitems.length}}</b></span></li>
                    <div class="header_cart h_o_dropdown" id="cart" v-bind:style='{"display": (CartToggle? "none !important" : "" )}' >
                        <div class="header_cart_inner">
                            <ul v-if="ShowCartItems.cartitems" v-bind:class="{addscroll: ShowCartItems.cartitems.length > 4}"> 
                                <li v-for="item in ShowCartItems.cartitems" :key="item.id">
                                    <div class="h_c_inner_left">
                                        <img v-if="item.item_images[0]" :src="'/' + item.item_images[0].thumbs_image_path" alt="">
                                    </div>
                                    <div class="h_c_inner_right">
                                        <h3 v-if="item.item.name"><router-link :to="`/single/${item.item.slug}`">{{item.item.name}}</router-link></h3>
                                        <p v-if="item.item.brand">{{item.item.brand.name}}</p>
                                        <div class="h_c_inner_info">
                                            <ul>
                                                <li v-if="item.itemsize"><span>Size</span> {{item.itemsize.item_size}}</li>
                                                <li v-if="item.color"><span>Color</span> {{item.color.name}}</li>
                                                <li v-if="item.item.price"><span>Price</span> USD${{item.item.price}}</li>
                                                <li><span>Quantity</span> {{item.quantity}}</li>
                                            </ul>
                                        </div>
                                    </div> 
                                </li>  
                            </ul>
                            <p class="subototal">
                                <span>CART SUBTOTAL:</span>
                                <span>USD${{ShowCartItems.total}} </span>
                            </p>
                            <div class="h_c_button">
                                <button  v-on:click="Checkout" @click="CartToggle = !CartToggle"  class="btn_common"><i class="fas fa-shopping-cart"></i> <span class="ml_5">Checkout</span> </button>
                            </div>
                        </div>
                    </div>
                </ul>
            </div>
        </div>
        <div class="main_header_mobile show_mobile  fixed-top">
            <div class="h_m_left">
                <ul>
                    <li data-toggle="collapse_l_r" data-target="#menu">
                        <div class="menu btn1">
                            <div class="icon-left"></div>
                            <div class="icon-right"></div>
                        </div>
                    </li>
                    <li data-toggle="collapse_l_r" data-target="#msearch">
                        <div class="h_m_search">
                            <span><i class="fas fa-search"></i></span>
                        </div>
                    </li> 
                </ul>
            </div>
            <div class="h_m_logo">
               <router-link to="/"><img :src="HeaderContent.logo" alt=""></router-link>
            </div>
            <div class="h_m_cart">
                <ul> 
                    <li v-show="LoginCheck" data-toggle="collapse_l_r" data-target="#mprofile"><span><i class="far fa-user"></i></span></li>
                    <li v-show="! LoginCheck" data-toggle="collapse_l_r" data-target="#muser"><span><i class="far fa-user"></i></span></li>
                    <li data-toggle="collapse_l_r" data-target="#mcart"><span><i class="fas fa-shopping-cart"></i></span></li>
                </ul>
            </div>
        </div> 
        <div class="ct_margin"></div>

        <div class="show_from_left" id="menu">
            <div class="header_menu_inner mobile_nav">
                <ul> 
                    <template v-for="(category, index) in HeaderCats" v-if="category.subCategories.length" > 
                        <li class="has_child" data-toggle="collapse_m_nav" :data-target="'#mob'+category.name">
                            {{category.name}} 
                        </li>
                        <div  class="show_from_left mobile_sub_menu" v-bind:id="'mob'+[category.name]">
                            <ul class="mobile_submenu"> 
                                <template v-for="(subcat,subindex) in category.subCategories">
                                        <li  v-for="(thirdcat, thirdindex) in subcat.thirdcategory" :key="thirdcat.name">  
                                            <router-link :to="`/${category.slug}/${subcat.slug}/${thirdcat.slug}/`" v-model="thirdcat.id">{{thirdcat.name}} <span v-if="subindex==1"  class="back" @click="close_mob_submenu($event)">Back</span> </router-link>
                                        </li> 
                                </template> 
                            </ul>
                        </div> 
                    </template>
                    <template v-else>
                         <li><router-link :to="`/${category.slug}`" v-model="category.id">{{category.name}}</router-link></li>
                    </template>
                    

                </ul>
            </div>
            <div class="close_h_menu">
                <span>Close</span>
            </div>
        </div>
        <div class="show_from_left" id="msearch">
            <div class="header_menu_inner">
                <div class="header_search">
                    <div class="header_search_form">
                        <div class="header_search_form_inner">
                            <input type="text" class="form-control"  @keyup="SearchPost" v-model="keyword">
                            <button @click.prevent="SearchPost" class="btn"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                    <p class="search_text">PLEASE TYPE AT LEAST 4 CHARACTERS FOR SEARCH SUGGESTIONS.</p>
                </div>
            </div>
            <div class="close_h_menu">
                <span>Close</span>
            </div>
        </div> 
        <div class="show_from_right" id="muser"  >
            <div class="header_menu_inner">
                <div class="header_account">
                    <div class="header_account_inner">
                        <div class="form_inline">
                            <label class="required">Email</label>
                            <input type="text" class="form-control"  name="email" placeholder="Email Address" v-model="LoginForm.email">
                            <span v-if="FormError.email" class="has-error">{{FormError.email[0]}}</span>
                        </div>
                        <div class="form_inline">
                            <label for="staticEmail">Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Password" v-model="LoginForm.password">
                            <span v-if="FormError.password" class="has-error">{{FormError.password[0]}}</span>
                        </div>
                        <span v-if="FormError.notfound" class="has-error">{{FormError.notfound[0]}}</span>
                        <br>
                        <button class="btn_common" @click.prevent="LoginSubmit()">login <i v-if="CartLoader" class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></button>
                        <div class="d_flex_center mt_10 mb_10"> 
                            <router-link to="/forgot-password" class="text-center td_underline tt_u">Forgot Password</router-link>
                        </div>
                        <p  class="text-center tt_u mb_10">or</p> 
                        <div class="create_acc_btn">
                            <button class="btn_transparent" @click="CreateNewAccount()"><router-link to="/registration">create an account</router-link></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="close_h_menu">
                <span>Close</span>
            </div>
        </div>

        <div class="show_from_right" id="mprofile"  v-bind:style='{"display": ( ! LoginCheck?  "none !important" : "" )}'>
            <div class="header_menu_inner">
                <div class="header_account">
                    <div class="header_account_inner">
                        <ul>
                            <li><router-link to="/buyer/profile"> My Account  </router-link></li>
                            <li @click.prevent="LogOut()"><router-link to="#"> Log Out </router-link></li>
                        </ul> 
                    </div>
                </div>
            </div>
            <div class="close_h_menu">
                <span>Close</span>
            </div>
        </div>

        <div class="show_from_right" id="mcart">
            <div class="header_menu_inner">
                <div class="header_cart">
                    <div class="header_cart_inner">
                        <ul> 
                            <li v-for="item in ShowCartItems.cartitems" :key="item.id">
                                <div class="h_c_inner_left">
                                    <img v-if="item.item_images[0]" :src="'/' + item.item_images[0].thumbs_image_path" alt="">
                                </div>
                                <div class="h_c_inner_right">
                                    <h3><router-link :to="`/single/${item.item.slug}`">{{item.item.name}}</router-link></h3>
                                    <p>VEJA</p>
                                    <div class="h_c_inner_info">
                                        <ul> 
                                            <li v-if="item.size_id"><span>Size</span> {{item.size_id}}</li>
                                            <li v-if="item.item.price"><span>Price</span> USD${{item.item.price}}</li>
                                            <li><span>Quantity</span> {{item.quantity}}</li>
                                        </ul>
                                    </div>
                                </div>
                            </li> 

                        </ul>
                        <p class="subototal">
                            <span>CART SUBTOTAL:</span>
                            <span>USD${{ShowCartItems.total}}</span>
                        </p>
                        <div class="h_c_button">
                            <button  v-on:click="Checkout" @click="CartToggle = !CartToggle" class="btn_common"><i class="fas fa-shopping-cart"></i> <span class="ml_5">Checkout</span> </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="close_h_menu" >
                <span>Close</span>
            </div>
        </div>  
    </header>   
    
</template>
<script>  
import Router from 'vue-router';
import _ from 'lodash'; 
export default {
    name:'Header',
    props:['HeaderRelation'],
    data(){
        return{ 
            id:'', 
            LoginForm: new Form({
                email: '',
                password: '' 
            }),
            FormError:  { }, 
            keyword:'', 
            CartToggle: false,
            LoginCheck: false, 
            CartLoader: false,  
        }
    },
    components:{ 
    },
    mounted(){   
        let that = this;
        this.$store.dispatch('DefaultCategory'); 
        this.$store.dispatch('HeaderDefaultContentAcion'); 
        this.$store.dispatch('GetCartItem');  
        this.$store.dispatch('GetUserData');  
        this.CheckAuthentication();
        if(this.HeaderRelation != null){  
            return this.LoginCheck = this.HeaderRelation; //Profile menu switch status condition generate (Condition get from master component).
        }
        setTimeout(() => {
            var common_margin = $('.cm_header').outerHeight();
            $('.ct_margin').css({'height' : `${common_margin}px`});

            $(window).on('scroll',function() {
                var scrollTop = $(window).scrollTop();
                if(scrollTop > 200) {
                    $('.main_header , .checkout_others li img').css({'height' : '50px'});
                    $('.logo img').attr("src", `/themes/front/media/images/logo-small.png`);
                } else {
                    $('.main_header , .checkout_others li img').css({'height' : '61px'});
                    $('.logo img ').attr("src", that.HeaderContent.logo);
                }
            });
        }, 200);
    },
    computed:{ 
        HeaderCats(){ 
            return this.$store.getters.HeaderCategory
        },
        HeaderContent(){
            return this.$store.getters.HeaderDefaultContentGetters;
        },
        ShowCartItems(){  
            return this.GetCartItemHeader();
        },

    },
    methods:{   
        Checkout(){ 
            this.$router.push('/cart')
            this.close_mob_submenu();
        },
        CheckAuthentication(){
            if(this.$store.getters.CheckTokenIsvalid == 'null'){
                this.$parent.ParentAuthMethod(0);  
                return this.LoginCheck = false;
            }else{
                this.$parent.ParentAuthMethod(1);  
                return this.LoginCheck = true; 
            } 
        },
        close_mob_submenu(elem){  
            $('.mobile_sub_menu').removeClass('open_h_menu');
            $('.show_from_right').removeClass('open_h_menu');
            $('.show_from_right').removeClass('open_h_menu'); 
            $('.show_from_left').removeClass('open_h_menu'); 
        },
        ForgetPassword(){
            $("#user").css('display','none')
        },
        CreateNewAccount(){ 
            $('.header_account.h_o_dropdown').css('display','none')
        },
        LoginSubmit(){ 
            this.CartLoader = true; 
            this.LoginForm.post('/api/v1/login')
            .then((result) => {  
            if(result.data.error){ 
                this.FormError= []; 
                this.CartLoader = false; 
                return this.FormError= result.data.error;
            }else{   
                toast.fire({
                    icon: 'success',
                    title: 'Sign In successfully'
                    })
                this.$parent.ParentAuthMethod(1); //User Login Notification Send to Master component
                this.$store.dispatch('UserLogin',result.data);  
                this.$store.dispatch('GetCartItem');
                this.$router.push('/buyer/profile')  
                this.close_mob_submenu();
                this.CartLoader = false;  
                this.GetCartItemHeader();
                return this.LoginCheck = true;
            }  
            }).catch((err) => {
                this.FormError= []; 
                this.CartLoader = false;
                return this.FormError= result.data.error; 
            }); 
        }, 
        LogOut(){   
            this.$store.dispatch('LogoutUser'); 
            this.$parent.ParentAuthMethod(0);  //User LogOut Notification Send to Master component
            this.$store.dispatch('GetCartItem'); 
            this.GetCartItemHeader() ;
            this.$router.push('/');  
            return this.LoginCheck = false;
        },
        UserAccessControl(){
            this.$store.dispatch('GetUserData');
        },
        GetCartItemHeader(){  
            return this.$store.getters.ShowCartItems
        }, 

        SearchPost:_.debounce(function(){   
            this.$router.push('/search?s='+this.keyword)
            this.close_mob_submenu();
        },1000 ),


    },
    watch:{ 
        $route(to,from){ 
            window.scrollTo(0, 0);
            this.UserAccessControl(); 
            this.GetCartItemHeader();
            //console.log(document.querySelectorAll(".submenu"));
            document.querySelectorAll(".submenu").forEach(function(elem) {
                elem.style.display = 'none';
            });
        }
    }, 
}
</script>
