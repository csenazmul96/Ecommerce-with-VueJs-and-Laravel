<template>
      <div class="my_account_dashboard">
        <div class="m_a_dashboard_title my_acc_container">
            <h2>Update Password</h2> 
        </div>
        <div class="my_acc_container">
            <div class="account_info">
                  <form role="form" class="registration_form_wrap"  >
                        <div class="account_info_left registration_form">
                              <h2 class="my_acc_subtitle">Update your profile password</h2> 
                                <p>Don't share your password with anyone, keep secret your password.</p>
                                <span class="has-error" id="error_message" v-if="FormError">{{FormError}}</span>
                                <br>
                                <div class="form_inline_border">
                                    <label class="required">New Password</label>
                                    <input type="password" class="form-control" placeholder="New Password" v-model="NewPass.password"> 
                                    <has-error :form="NewPass" field="password"></has-error>
                                </div> 
                                <div class="form_inline_border">
                                    <label class="required">Confirm New Password</label>
                                    <input type="password" class="form-control" placeholder="Confirm New Password" v-model="NewPass.password_confirmation"> 
                                    <has-error :form="NewPass" field="password_confirmation"></has-error>
                                </div> 
                                <button class="btn_grey width_200p " @click.prevent="SubmitNewPass()">Submit</button> 
  
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
                NewPass: new Form({
                    password: '', 
                    password_confirmation: '',
                    token:null,
                }), 
                FormError: '',
            }
      },
      methods:{
            SubmitNewPass(){ 
                this.NewPass.token = this.$route.query.token;
                this.NewPass.post('/api/v1/newpassword')
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