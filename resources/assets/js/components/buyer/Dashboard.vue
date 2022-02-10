<template> 
      <div class="account_info_right col-md-10 col-12">
            <h4 class="text-center">Profile Information</h4>
            <br>
            <div class="row">
            <div class="col-md-6">
                <h2 class="my_acc_subtitle">ACCOUNT INFORMATION</h2>
                    <ul> 
                        <li v-if="UserProfileData.user">
                        <span>First Name</span>
                        <span>{{UserProfileData.user.first_name}}</span>
                        </li>
                        <li v-if="UserProfileData.user">
                        <span>Last Name</span>
                        <span>{{UserProfileData.user.last_name}}</span>
                        </li>
                        <li v-if="UserProfileData.email">
                        <span>Email</span>
                        <span>{{UserProfileData.user.email}}</span> 
                        </li>
                        <li >
                        <span>Password</span>
                        <span @click.prevent="MenuTriger('ResetPassword')">   
                                <router-link to="" class="td_underline" >Change Password</router-link> 
                        </span>
                        </li>
                        <li class="reset_password_wrap d_none">
                                <div class="reset_password"> 
                                    <div class="account_info">
                                        <form role="form" class="resetpassword_form_wrap"   >  
                                            <!-- <div class="form_inline_border">
                                                    <label class="required">Old Password</label>
                                                    <input type="password" class="form-control" placeholder="Old Password" v-model="ResetPassword.oldpassword">
                                                    <has-error :form="ResetPassword" field="oldpassword" class="has-error"></has-error>
                                            </div>  -->
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
                        </li>
                        <li v-if="UserProfileData.user  ">
                        <span>Phone</span>
                        <span>{{UserProfileData.user.buyer.billing_phone}}</span>
                        </li>
                    </ul>
                    <button class="btn_grey width_200p" @click.prevent="MenuTriger('UpdateDetails')"> Update Account Details</button>
            </div>
            <div class="col-md-6">
                <h2 class="my_acc_subtitle">MY ADDRESSES</h2>
                    <ul class="acc_address">
                        <li>
                            <span>DEFAULT BILLING ADDRESS</span>
                            <address v-if="UserProfileData.user">
                                    {{UserProfileData.user.buyer.billing_address}}, {{UserProfileData.user.buyer.billing_city}}
                                    <br>
                                    <template v-if="UserProfileData.user.buyer.billing_country">{{UserProfileData.user.buyer.billing_country.name}} </template> <template v-if="UserProfileData.user.buyer">,-{{UserProfileData.user.buyer.billing_zip}} </template>
                                    <br>
                                    {{UserProfileData.user.buyer.billing_location}}
                            </address>
                            <address v-else>NOT SET</address>
                        </li>  
                        <li>
                            <span>DEFAULT SHIPPING ADDRESS</span>
                            <template v-if="UserProfileData.shippingAddress " v-for="address in UserProfileData.shippingAddress">
                                <address v-if="address.default == 1">      
                                    {{address.address}}, {{address.city}}     <br>
                                    <template v-if="address.country"> {{address.country.name}} </template> - {{address.zip}}  <br>
                                    {{address.location}}   
                                </address>
                            </template>
                            <template v-else>NOT SET</template>
                        </li>
                    </ul>
                    <button class="btn_grey" @click.prevent="MenuTriger('ManageAddress')">Manage Addresses</button>
            </div>
        </div>
             
      </div> 
</template>
<script> 
export default {
    name:'profile-dashboard',
    data(){
        return{
            profiledata:[], 
            ResetPassword: new Form({ 
                    password: '',
                    // oldpassword: '',
                    password_confirmation: '', 
                }),
        }
    },
    mounted(){   
        this.$store.dispatch('GetProfileInformation');
    },
    components:{ 
    },
    computed:{ 
        UserProfileData(){  
            return this.UserProfile();
        } ,
    },
    methods:{  
        MenuTriger(menu){
            this.$parent.ParentMenuTriger(menu); 
        },
        UserProfile(){   
            return this.$store.getters.ProfileInfo
        }, 
        ReserPasswordSubmit(){
                  console.log(this.ResetPassword) 
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
    }, 
    watch:{  
        $route(to,from){ 
            this.UserProfile();  
        } 
    }
}
</script>