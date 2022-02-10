<template>
      <div class="my_account_dashboard">
        <div class="m_a_dashboard_title my_acc_container">
            <h2>Forgot Your Password?</h2> 
        </div>
        <div class="my_acc_container">
            <div class="account_info">
                  <form role="form" class="registration_form_wrap"  >
                        <div class="account_info_left registration_form">
                              <h2 class="my_acc_subtitle">Retrieve your password here</h2> 
                                <p>Please enter your email address below. You will receive a link to reset your password.</p>
                                <span class="has-error" id="error_message" v-if="FormError">{{FormError}}</span>
                                <br>
                                <div class="form_inline_border">
                                    <label class="required">Email</label>
                                    <input type="text" class="form-control" placeholder="Email Address" v-model="ForgotPass.email"> 
                                    <has-error :form="ForgotPass" field="email"></has-error>
                                </div> 
                                <button class="btn_grey width_200p " @click.prevent="SubmitReset()">Submit</button>  
                                <br>
                                <br>
                                 <router-link to="/login">Back To Login </router-link>  
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
                  ForgotPass: new Form({
                        email: '',  
                  }),
                  FormError: '',
            }
      },
      methods:{
            SubmitReset(){ 
                this.ForgotPass.post('/api/v1/resetpassword')
                .then((response)=>{    
                  if(response.data.status == 'success'){
                        this.FormError= null,
                        toast.fire({
                              icon: 'success',
                              title: response.data.message
                        }) 
                  }else{
                        this.FormError = response.data.message
                  }
                }) 
            },
      },
}
</script>