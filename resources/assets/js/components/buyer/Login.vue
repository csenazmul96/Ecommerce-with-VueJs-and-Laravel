<template>
      <div class="my_account_dashboard">
        <div class="m_a_dashboard_title my_acc_container">
            <h2>Login</h2> 
        </div>
        <div class="my_acc_container">
            <div class="account_info">
                  <form role="form" class="registration_form_wrap"  >
                        <div class="account_info_left registration_form">
                              <h2 class="my_acc_subtitle">USER LOGIN INFORMATION</h2>
                              
                              <div class="form_inline_border">
                                    <label class="required">Email</label>
                                    <input type="text" class="form-control" placeholder="Email Address" v-model="LoginForm.email">
                                    <span v-if="FormError.email" class="has-error">{{FormError.email[0]}}</span>
                              </div>
                              <div class="form_inline_border">
                                    <label class="required">Password</label>
                                    <input type="password" class="form-control" placeholder="Password" v-model="LoginForm.password">
                                    <span v-if="FormError.password" class="has-error">{{FormError.password[0]}}</span>
                              </div>  
                              <span v-if="FormError.notfound" class="has-error">{{FormError.notfound[0]}}</span>
                              <br>
                              <router-link to="/forgor-password">Forget Password ?</router-link> 
                              <br>
                              <br>
                              <button class="btn_grey width_200p " @click.prevent="LoginSubmit()">Login</button>
                              <br>
                              <br>
                              <router-link to="/registration">Are you new here ?</router-link> 
                        </div> 
                  </form>
            </div>
        </div>
    </div>
</template>
<script>
export default {
      name:'profile-top-section',
      data(){
            return{
                  LoginForm: new Form({
                        email: '',
                        password: '' 
                  }),
                  FormError: [],
            }
      },
      methods:{
            LoginSubmit(){  
               this.LoginForm.post('/api/v1/login')
                .then((result) => {  
                      if(result.data.error){ 
                        this.FormError= []; 
                        this.CartLoader = false; 
                        return this.FormError= result.data.error;
                    }else{  
                          this.$parent.ParentAuthMethod(1);
                          this.$parent.Rebuildheader();
                          
                        toast.fire({
                              icon: 'success',
                              title: 'Sign In successfully'
                              })
                        this.$store.dispatch('UserLogin',result.data);  
                        this.$router.push('/buyer/profile') 
                        this.$store.dispatch('GetCartItem');
                        this.ShowCartItems()
                        this.LoginCheck = true;
                    }
                }).catch((err) => {
                    console.log(err.data)
                }); 
            }, 
            ShowCartItems(){ 
                return this.$store.getters.ShowCartItems
            },
      },
}
</script>