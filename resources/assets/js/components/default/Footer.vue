<template>
    
          <footer class="footer_area">
                  <div class="footer_inner">
                        <div class="footer_logo">
                              <router-link to="/"> <img :src="footercontent.logo" alt=""></router-link>
                        </div>
                        <div class="footer_menu">
                              <div class="footer_menu_inner"> 
                              <h2 @click="FooterMenuMobile('profilemenu')">ACCOUNT</h2>
                              <ul class="profilemenu">
                                    <li v-show="ProfileMenu"><router-link to="/buyer/profile">MY ACCOUNT</router-link></li>
                                    <li v-show="!ProfileMenu"><router-link to="/login">MY ACCOUNT</router-link></li>
                                    <li><a href="#">ORDER STATUS</a></li>
                                    <li><router-link to="/wishlist">WISHLIST</router-link></li>
                              </ul>
                              </div>
                              <div class="footer_menu_inner">
                              <h2 @click="FooterMenuMobile('pagemenu')">MISS MORE CLOTHES</h2>
                              <ul class="pagemenu">
                                    <li><router-link to="/about-us">ABOUT US</router-link> </li>
                                    <li><a href="#">BECOME A MISS MORE VIP</a></li>
                                    <li><router-link to="/shipping-returns">SHIPPING & RETURNS</router-link></li>
                                    <li><router-link to="/terms-conditions">TERMS & CONDITIONS</router-link></li>
                                    <li><router-link to="/privacy-policy">PRIVACY POLICY</router-link></li>
                              </ul>
                              </div>
                              <div class="footer_menu_inner">
                              <h2 @click="FooterMenuMobile('helpermenu')">SERVICE</h2>
                              <ul class="helpermenu">
                                    <li><router-link to="/contact-us">CONTACT US</router-link></li>
                                    <li><router-link to="/faq">FAQ</router-link></li>
                                    <li><router-link to="/size-chart">INTERNATIONAL SIZE CHART</router-link></li>
                              </ul>
                              </div>
                        </div>
                        <div class="footer_social">
                              <h2>GET SOCIAL</h2>
                              <ul>
                              <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                              <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                              <li><a href="#"><i class="fab fa-pinterest-p"></i></a></li>
                              </ul>
                        </div>
                  </div>
                  <div class="footer_bottom">
                        <span>© 2020 WELL MADE CLOTHES PTY LTD.</span>
                        <p class="text-center">SITE BY Lynkto</p>
                  </div>
                  </footer>
    
</template>


<script>  
export default {
      name:'Header',
      props:['FooterContent'], //Props get from master component for User login status check.
      data(){
            return{
                  id:'',
                  ProfileMenu:false, //User login/logout profile menu switch
            }
      },
      components:{
             
      },
      mounted(){
            this.ProfileMenu = this.FooterContent; //Profile menu switch status condition generate (Condition get from master component).
            this.$store.dispatch('DefaultFooterContent');  
      },
      computed:{ 
            footercontent(){
                return this.$store.getters.FooterContents
            } 
      },
      methods:{ 
            FooterMenuMobile(menuname){ 
                  $('.footer_menu ul').removeClass('m_d_block');
                  $('.'+menuname).toggleClass('m_d_block');
            }
      },
      watch:{ 
      }
}
</script>
