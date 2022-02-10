<template> 
      <div class="account_info_right col-md-10 col-12">
            <h4 class="text-center">Account Setting</h4>
            <br>
            <h2 class="my_acc_subtitle">ACCOUNT INFORMATION</h2>
            <div class="form_inline_border">
                  <label class="required">First Name</label>
                  <input type="text" class="form-control" placeholder="First Name" v-model="UpdateProfile.first_name" value="UserProfile">
                  <has-error :form="UpdateProfile" field="first_name"></has-error>
            </div>
            <div class="form_inline_border">
                  <label class="required">Last Name</label>
                  <input type="text" class="form-control" placeholder="Last Name" v-model="UpdateProfile.last_name">
                  <has-error :form="UpdateProfile" field="last_name"></has-error>
            </div>
            <div class="form_inline_border">
                  <label class="required">Email</label>
                  <input type="text" class="form-control" placeholder="Email Address" v-model="UpdateProfile.email"  >
                  <has-error :form="UpdateProfile" field="email"></has-error>
            </div>
            <div class="form_inline_border">
                  <label class="required">Phone Number</label>
                  <input type="text" class="form-control"   placeholder="Need to set up billing address first" v-model="UpdateProfile.billing_phone">
                  <has-error :form="UpdateProfile" field="billing_phone"></has-error>
            </div>
            <button class="btn_grey width_200p" @click="UpdateProfileData()">Update</button>
      </div> 
</template>
<script>
import buyerprofile from './ProfileTopSection.vue';
export default {
      name:'profile-top-section',
      data(){
            return{
                  profiledata:[],
                  UpdateProfile: new Form({
                        first_name: '',
                        last_name: '', 
                        email: '',
                        billing_phone: '',
                  }),
            }
      },
      mounted(){  
            this.created(); 
    },
    components:{
            'buyerprofile' : buyerprofile
      },
    computed:{ 
    },
      methods:{ 
            UpdateProfileData(){
                  this.UpdateProfile.post('/api/v1/profile-update',{ headers: { 'Authorization': 'Bearer '+localStorage.getItem('access_token')}})
                  .then((result) => {  
                        toast.fire({
                              icon: 'success',
                              title: 'Profile Updated Successfully.'
                        })  
                  }).catch((err) => {
                        
                  });
            }, 
            created() {
                  axios.get('/api/v1/profile',{ headers: { 'Authorization': 'Bearer '+localStorage.getItem('access_token')}})
                  .then( (result) =>{   
                        this.UpdateProfile.fill(result.data.user);  
                        this.UpdateProfile.billing_phone = result.data.user.buyer.billing_phone;
                        }
                  )
        }
      }, 
}
</script>