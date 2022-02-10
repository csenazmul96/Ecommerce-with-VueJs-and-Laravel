<template>
    <div class="account_info_right col-md-10 col-12">
        <h4 class="text-center">BIilling and Shipping Address</h4>
        <br>
        <div class="row">
            <div class="col-md-6">
                <h2 class="my_acc_subtitle">Billing INFORMATION</h2>
                <div class="c_i_form">
                <label for="">Location</label>
                <div class="form-group">
                    <div class="custom_radio">
                        <input class="location" type="radio" id="factoryLocationUS" v-model="BillingAddress.factoryLocation" value="US" :checked="BillingAddress.factoryLocation == 'US'" name="factoryLocation" @click="ChangeLocationFactory(1)">
                        <label for="factoryLocationUS">United States </label>
                    </div>
                    <div class="custom_radio">
                        <input class="location" type="radio" id="factoryLocationCA" v-model="BillingAddress.factoryLocation" value="CA" :checked="BillingAddress.factoryLocation == 'CA'"  name="factoryLocation" @click="ChangeLocationFactory(2)">
                        <label for="factoryLocationCA">Canada</label>
                    </div>
                    <div class="custom_radio">
                        <input class="location" type="radio" id="factoryLocationInt" v-model="BillingAddress.factoryLocation" value="INT" :checked="BillingAddress.factoryLocation == 'INT'"  name="factoryLocation" @click="ChangeLocationFactory(null)">
                        <label for="factoryLocationInt">International</label>
                    </div>
                </div>
                <div class="form-group">
                    <input class="form-control" type="text" id="factoryAddress" name="factoryAddress" placeholder="Address" v-model="BillingAddress.factoryAddress">
                    <span v-if="FormError.factoryAddress" class="has-error">{{FormError.factoryAddress[0]}}</span>
                </div>
                <div class="form-row">
                    <div class="form-group col-6">
                        <input class="form-control" type="text" id="factoryCity" name="factoryCity" Placeholder="City" v-model="BillingAddress.factoryCity">
                        <span v-if="FormError.factoryCity" class="has-error">{{FormError.factoryCity[0]}}</span>
                    </div>

                        <div class="form-group col-6">
                        <input class="form-control" type="text" id="factoryPhone" name="factoryPhone" Placeholder="Number" v-model="BillingAddress.factoryPhone">
                        <span v-if="FormError.factoryPhone" class="has-error">{{FormError.factoryPhone[0]}}</span>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-6">
                        <input class="form-control" type="text" id="factoryZipCode" name="factoryZipCode" placeholder="Zip Code" v-model="BillingAddress.factoryZipCode">
                        <span v-if="FormError.factoryZipCode" class="has-error">{{FormError.factoryZipCode[0]}}</span>
                    </div>
                    <div class="col-lg-6 form-group"   v-if="BillingAddress.factoryLocation == 'INT'">
                        <input class="form-control" type="text" id="factoryState" name="factoryState" Placeholder="State" v-model="BillingAddress.factoryState">
                        <span v-if="FormError.factoryState" class="has-error">{{FormError.factoryState[0]}}</span>
                    </div>
                    <div class="col-lg-6 form-group" v-if="BillingAddress.factoryLocation != 'INT'">
                        <select class="form-control" id="factoryStateSelect" name="factoryStateSelect" v-model="BillingAddress.factoryStateSelect">
                            <option value="">Select State</option>
                            <template v-for="state in StatesFactory">
                                <option  v-bind:value="state.id" :selected="state.code == billingstateselect" >{{state.name}} /{{state.code}}</option>
                            </template>
                        </select>
                        <span v-if="FormError.factoryStateSelect" class="has-error">{{FormError.factoryStateSelect[0]}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <select class="form-control"  v-model="BillingAddress.factoryCountry"  :disabled="BillingAddress.factoryLocation != 'INT'">
                        <template v-for="country in GetCountry.countries">
                            <option  :data-code="country.code"  :value="country.id"  :selected="country.code == BillingAddress.factoryLocation">{{country.name}}</option>
                        </template>
                    </select>
                    <span v-if="FormError.factoryCountry" class="has-error">{{FormError.factoryCountry[0]}}</span>
                </div>
                    <button class="btn_grey width_200p" @click="UpdateBillingAddress()">Update Billing Address</button>
            </div>
            </div>

            <div class="col-md-6">
                <h2 class="my_acc_subtitle">Shipping Address <span class="float-right"  @click="AddNewAddress()" data-toggle="modal" data-target="#myModal">Add new address</span></h2>

                <div class=" ">
                    <table class="table table-bordered" v-if="ShippingAddress && ShippingAddress.length>0">
                        <tr>
                            <th>Address</th>
                            <th class="text-center">Default</th>
                            <th class="text-center">Action</th>
                        </tr>
                            <tr v-for="rowaddrss  in ShippingAddress"  >
                                <td>
                                    {{ rowaddrss.address }}, {{ rowaddrss.city }},
                                    <span v-if="rowaddrss.country">{{rowaddrss.country.name }}</span> - {{ rowaddrss.zip }}
                                </td>
                                <td class="text-center">
                                    <div class="custom-control  ">
                                        <input class="custom-control-input" type="radio" v-bind:id="[rowaddrss.city]"  :checked="rowaddrss.default=='1'" name="defaultAddress" @change="DefaultAddress(rowaddrss.id)">
                                        <label class="custom-control-label" :for="rowaddrss.city" ></label>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a class="text-info btnEdit"  href="#" role="button" @click="EditAddress(rowaddrss)" data-toggle="modal" data-target="#myModal" >Edit</a> |
                                    <a class="text-danger btnDelete" href="#"  role="button" @click="AddressDelete(rowaddrss.id)">Delete</a>
                                </td>
                            </tr>
                    </table>
                </div>
            </div>
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" v-bind:class="{ show: isActive, 'd_none': isActive }">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="c_i_form">
                                <label for="">Location</label>
                                <div class="form-group">
                                    <div class="custom_radio">
                                        <input class="location" type="radio" id="locationUS" v-model="ShippingAddressForm.location" value="US" :checked="ShippingAddressForm.location=='US'" name="location" @click="ChangeLocation(1)" >
                                        <label for="locationUS">United States </label>
                                    </div>
                                    <div class="custom_radio">
                                        <input class="location" type="radio" id="locationCA" v-model="ShippingAddressForm.location" value="CA" :checked="ShippingAddressForm.location=='CA'"  name="location" @click="ChangeLocation(2)">
                                        <label for="locationCA">Canada</label>
                                    </div>
                                    <div class="custom_radio">
                                        <input class="location" type="radio" id="locationInt" v-model="ShippingAddressForm.location" value="INT" :checked="ShippingAddressForm.location=='INT'"  name="location" @click="ChangeLocation(null)">
                                        <label for="locationInt">International</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" type="text" id="address" name="address" placeholder="Address" v-model="ShippingAddressForm.address">
                                    <span v-if="FormError.address" class="has-error">{{FormError.address[0]}}</span>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" type="text" id="city" name="city" Placeholder="City" v-model="ShippingAddressForm.city">
                                    <span v-if="FormError.city" class="has-error">{{FormError.city[0]}}</span>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-6">
                                        <input class="form-control" type="text" id="zip" name="zip" placeholder="Zip Code" v-model="ShippingAddressForm.zip">
                                        <span v-if="FormError.zip" class="has-error">{{FormError.zip[0]}}</span>
                                    </div>
                                    <div class="col-lg-6 form-group" id="form-group-state" v-if="ShippingAddressForm.location == 'INT'">
                                        <input class="form-control" type="text" id="state" name="state_text" Placeholder="State" v-model="ShippingAddressForm.state_text">
                                    </div>
                                    <div class="col-lg-6 form-group" id="form-group-state-select" v-if="ShippingAddressForm.location != 'INT'">
                                        <select class="form-control" id="state_id" name="state_id" v-model="ShippingAddressForm.state_id" >
                                            <option  value="">Select State</option>
                                            <template v-for="state in States">
                                                <option  v-bind:value="state.id" :selected="state.code == billingstateselect">{{state.name}}</option>
                                            </template>
                                        </select>
                                        <span v-if="FormError.state_id" class="has-error">{{FormError.state_id[0]}}</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <select class="form-control" id="country_id" name="country_id" ref="country_id" v-model="ShippingAddressForm.country_id" :disabled="ShippingAddressForm.location != 'INT'">
                                        <template v-for="country in GetCountry.countries">
                                            <option :data-code="country.code"  v-bind:value="country.id"  :selected="country.code == ShippingAddressForm.location">{{country.name}}</option>
                                        </template>
                                    </select>
                                    <span v-if="FormError.country_id" class="has-error">{{FormError.country_id[0]}}</span>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" type="number" id="phone" name="phone" placeholder="Phone" v-model="ShippingAddressForm.phone">
                                    <span v-if="FormError.phone" class="has-error">{{FormError.phone[0]}}</span>
                                </div>
                                <div class="form-group">
                                    <button v-show="!editmode" class="btn_grey width_200p" @click="AddNewShippingAddrss()">Add New Address</button>
                                    <button v-show="editmode" class="btn_grey width_200p" @click="UpdateAddress()">Update Address</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {

    name:'BuyerAddress',
    data(){
        return{
                isActive:false,
                FormError:  { },
                editmode:false,
                StatesFactory:[],
                FormError:  { },
                billingstateselect:'US',
                States:[],
                profiledata:[],
                ShippingAddress:[],
                BillingAddress: new Form({
                    factoryLocation: 'US',
                    factoryAddress: '',
                    factoryZipCode: '',
                    factoryCountry: 1,
                    factoryCity: '',
                    factoryStateSelect: '',
                    factoryState: '',
                    factoryPhone:'',
                }),
                ShippingAddressForm: new Form({
                    id: '',
                    location: 'US',
                    address: '',
                    city: '',
                    state_id: '',
                    state_text: '',
                    zip: '',
                    country_id: '1',
                    phone: '',
                }),
                StatesStore:[],
        }
    },
    components:{
    },
    mounted(){
        this.BuyerAllAddress();
        this.$store.dispatch('GetAllState')
        this.$store.dispatch('GetAllCountry')
    },
    computed:{
        GetCountry(){
            this.StatesStore = this.$store.getters.GetAllSate
            this.ChangeLocation();
            this.ChangeLocationFactory();
            return this.$store.getters.GetAllCountry
        },
    },
    methods:{
        UpdateBillingAddress(){
            axios.post('/api/v1/buyer/UpdateBillingAddress', this.BillingAddress, {
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer '+localStorage.getItem('access_token')
                }
            })
            .then((response)=>{
                if(response.data.error){
                    this.FormError= [];
                    return this.FormError= response.data.error;
                }else{
                    toast.fire({
                        icon: 'success',
                        title: 'Wow...',
                        text: 'New Billing Address Updated.',
                    })
                    this.BuyerAllAddress()
                }
            })
            .catch(()=>{
                toast.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!',
                })
            });
        },
        AddNewAddress(){
            this.editmode = false,
            this.ShippingAddressForm.reset();
            this.ShippingAddressForm.country_id = 1;
        },
        AddNewShippingAddrss(){
            axios.post('/api/v1/buyer/addnewshipping', this.ShippingAddressForm, {
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer '+localStorage.getItem('access_token')
                }
            })
            .then((response)=>{
                if(response.data.error){
                    this.FormError= [];
                    return this.FormError= response.data.error;
                }else{
                    toast.fire({
                        icon: 'success',
                        title: 'Wow...',
                        text: 'New Shipping Address Added.',
                    })
                    this.BuyerAllAddress()
                    $('#myModal').modal('hide');
                }


            })
            .catch(()=>{
                toast.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!',
                })
            });
        },
        ChangeLocationFactory(id){
            this.billingstateselect = this.BillingAddress.factoryLocation;
            if(this.BillingAddress.factoryLocation == 'US'){
                this.StatesFactory  =  this.StatesStore.usStates
                return this.StatesFactory;
            }else if(this.BillingAddress.factoryLocation == 'CA'){
                this.StatesFactory  =  this.StatesStore.caStates
                return this.StatesFactory;
            }
        },
        ChangeLocation(id){
            if(this.ShippingAddressForm.location == 'US'){
                this.States  =  this.StatesStore.usStates
                return this.States;
            }else if(this.ShippingAddressForm.location == 'CA'){
                this.States  =  this.StatesStore.caStates
                return this.States;
            }
        },
        BuyerAllAddress() {
            axios.get('/api/v1/address',{ headers: { 'Authorization': 'Bearer '+localStorage.getItem('access_token')}})
            .then( (result) =>{
                let address = result.data.metabuyer
                    this.BillingAddress.factoryLocation = address.billing_location
                    this.BillingAddress.factoryAddress = address.billing_address
                    this.BillingAddress.factoryZipCode = address.billing_zip
                    this.BillingAddress.factoryCountry = address.billing_country_id
                    this.BillingAddress.factoryCity = address.billing_city
                    this.BillingAddress.factoryStateSelect = address.billing_state_id
                    this.BillingAddress.factoryState = address.billing_state

                    this.BillingAddress.factoryPhone = address.billing_phone
                    this.billingstateselect = address.billing_location;
                    this.ShippingAddress = result.data.shippingAddress
                }
            )
        },
        DefaultAddress(id){
            axios.post('/api/v1/default-address', id, {
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer '+localStorage.getItem('access_token')
                }
            })
            .then((response)=>{
                if(response.data.result == 'success'){
                    toast.fire({
                        icon: 'success',
                        title: 'Wow...',
                        text: 'Default Address Updated!.',
                    })
                }else{
                    toast.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                    })
                }
            })
        },
        EditAddress(data){
            this.editmode = true;
            this.isActive = false;
            this.ShippingAddressForm.fill(data);
        },
        AddressDelete(id){
            axios.post('/api/v1/buyer/shippingAddressDelete', id, {
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer '+localStorage.getItem('access_token')
                }
            })
            .then((response)=>{
                    if(response.data.message == 'success'){
                        toast.fire({
                        icon: 'success',
                        title: 'Wow...',
                        text: 'Shipping Address Deleted.',
                        })
                        this.BuyerAllAddress()
                    }else{
                        toast.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                        })
                    }

            })
            .catch(()=>{
                toast.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!',
                })
            });
        },
        UpdateAddress(){
            axios.post('/api/v1/buyer/updateaddress', this.ShippingAddressForm, {
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer '+localStorage.getItem('access_token')
                }
            })
            .then((response)=>{
                if(response.data.error){
                    this.FormError= [];
                    return this.FormError= response.data.error;
                }else{
                    toast.fire({
                        icon: 'success',
                        title: 'Wow...',
                        text: 'New Shipping Address Added.',
                    })
                    this.BuyerAllAddress()
                    $('#myModal').modal('hide');
                }
            })
            .catch(()=>{
                toast.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!',
                })
            });
        }
    },
}
</script>
