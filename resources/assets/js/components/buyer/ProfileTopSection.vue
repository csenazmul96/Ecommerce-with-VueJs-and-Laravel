<template>
        <div class="account_bredcrumbs"  v-if="Auth">
-            <div class="a_c_bredcrumbs_left">
-                  <a href="#">← back </a>
-            </div>
-            <div class="a_c_bredcrumbs_right" @click.prevent="LogOut()"> 
-                  <router-link  to="">Log Out</router-link>
-            </div>
-      </div>
</template>
<script>
 
export default {
      name:'profile-top-section', 
      data(){
            return{
                  Auth: false,   
            }
      }, 
      methods:{ 
            LogOut(){    
                  this.checkroute();
                  this.$store.dispatch('LogoutUser');
                  this.GetCartItemHeader() ;
                  this.$store.getters.CheckTokenIsvalid  
                  this.$router.push('/');  
                  location.reload();
            },
            ACL(){  
                  if(this.$store.getters.CheckTokenIsvalid == 'null'){
                        this.$router.push('/login');
                  } 
                return this.$store.getters.CheckTokenIsvalid; 
            },
             
            UserAccessControl(){
                  this.$store.dispatch('GetUserData');
            },
            GetCartItemHeader(){  
                return this.$store.getters.ShowCartItems
            },
            checkroute(){ 
                  let route = this.$route.path
                   route = route.split('/')
                  if ($.inArray('buyer', route) >= 0) { 
                        this.Auth= true
                  }else {
                        this.Auth= false 
                  }
            },
            
      },
      mounted(){  
            this.checkroute();
            this.$store.dispatch('GetUserData');  
            this.ACL();
      },
       
      computed:{
            
      },
      watch:{ 
        $route(to,from){  
            this.UserAccessControl(); 
            this.GetCartItemHeader();
        }
      }
}
</script>