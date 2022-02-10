<template>
      <div class="my_account_dashboard">
        <div class="m_a_dashboard_title my_acc_container">
            <h2>Create a new account</h2> 
        </div>
        <div class="my_acc_container">
            <div class="account_info">
                  <form role="form" class="registration_form_wrap"  >
                  <div class="account_info_left registration_form">
                        <h2 class="my_acc_subtitle">ACCOUNT INFORMATION</h2>
                        <div class="form_inline_border">
                              <label class="required">First Name</label>
                              <input type="text" class="form-control" v-model="register.firstName">
                              <has-error :form="register" field="firstName"></has-error>
                        </div>
                        <div class="form_inline_border">
                              <label class="required">Last Name</label>
                              <input type="text" class="form-control" v-model="register.lastName">
                        </div>
                        <div class="form_inline_border">
                              <label class="required">Email</label>
                              <input type="text" class="form-control" v-model="register.email">
                              <has-error :form="register" field="email"></has-error>
                        </div>
                        <div class="form_inline_border">
                              <label class="required">Password</label>
                              <input type="password" class="form-control" v-model="register.password">
                              <has-error :form="register" field="password"></has-error>
                        </div> 
                        <div class="form_inline_border">
                              <label class="required">Confirm Password</label>
                              <input type="password" class="form-control" v-model="register.password_confirmation">
                              <has-error :form="register" field="password_confirmation"></has-error>
                        </div> 
                  <button class="btn_grey width_200p " @click.prevent="Registration()">Submit</button>
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
                  register: new Form({
                        firstName: '',
                        lastName: '',
                        password: '',
                        password_confirmation: '',
                        email: '',
                  }),
            }
      },
      methods:{
            Registration(){ 
                  this.register.post('/api/v1/registration/post')
                  .then((result) => {  
                        this.$parent.Rebuildheader();
                        this.$parent.RebuildFooter();
                        this.$store.dispatch('UserLogin',result.data);  
                        toast.fire({
                        icon: 'success',
                        title: 'Registration Complete successfully'
                        })
                        this.$router.push('/buyer/profile')
                  }).catch((err) => {
                        console.log(err.data)
                  });
            }
      }, 
}
</script>