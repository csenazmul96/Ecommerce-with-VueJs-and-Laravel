<template>
<div class="account_info_right col-md-10 col-12">
            <h4 class="text-center">Account Setting</h4>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <form role="form" class="resetpassword_form_wrap"   >  
                            <div class="form_inline_border">
                                <label class="required">Old Password</label>
                                <input type="password" class="form-control" placeholder="Old Password" v-model="ResetPassword.oldpassword">
                                <has-error :form="ResetPassword" field="oldpassword" class="has-error"></has-error>
                            </div> 
                            <div class="form_inline_border">
                                <label class="required">New Password</label>
                                <input type="password" class="form-control" placeholder="New Password" v-model="ResetPassword.password">
                                <has-error :form="ResetPassword" field="password" class="has-error"></has-error>
                            </div>   
                            <div class="form_inline_border">
                                <label class="required">Confirmed Password</label>
                                <input type="password" class="form-control" placeholder="Confirmed Password" v-model="ResetPassword.password_confirmation">
                                <has-error :form="ResetPassword" field="password_confirmation"  class="has-error"></has-error>
                            </div>   
                            <button class="btn_grey width_200p " @click.prevent="ReserPasswordSubmit()">ReSet Password</button>  
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
                  ResetPassword: new Form({ 
                        password: '',
                        oldpassword: '',
                        password_confirmation: '', 
                  }),
            }
      },
      methods:{
            ReserPasswordSubmit(){ 
                  this.ResetPassword.post('/api/v1/change-password',{ headers: { 'Authorization': 'Bearer '+localStorage.getItem('access_token')}})
                  .then((result) => {  
                        if(result.data.status == 'faield') { 
                              toast.fire({
                              icon: 'error',
                              title: 'Old Password Not Metch.'
                              })
                        }else{
                              toast.fire({
                              icon: 'success',
                              title: 'Password Changed Successfully.'
                              })
                              this.ResetPassword.reset(); 
                        }
                  }).catch((err) => { 
                  });
            },
      },
}
</script>