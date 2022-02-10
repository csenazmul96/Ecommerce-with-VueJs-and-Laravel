<template>
      <span class="cart_component">
            <header class="header_area checkout_header">
                  <div class="main_header cm_header fixed-top">
                        <div class="c_l_logo">
                            <router-link to="/"><img :src="RetatedData.logo_path" alt="" class="img-fluid"></router-link>
                            <router-link to="/"><img :src="RetatedData.logo_path" alt="" class="img-fluid"></router-link>
                        </div>
                        <div class="checkout_nav">
                            <h2>Secure checkout</h2>
                            <div class="checkout_logo">
                                <!-- <a href="home.html"><img :src="assets/media/images/logov2.png" alt="" class="img-fluid"></a>  -->
                            </div>
                        </div>
                        <div class="checkout_others">
                            <ul>
                                <li data-toggle="collapse_slide"  ><a href="#">Help</a></li>
                                <!-- <li><img :src="assets/media/images/secure_Checkout.png" alt="" class="img-fluid"  ></li> -->
                            </ul>
                        </div>
                  </div>
                  <div class="ct_margin"></div>
            </header>
            <section class="cart_area checkout_area">
            <form @submit.prevent="CheckoutPost()" >
                <input type="hidden" name="_token" :value="csrf">
                <div class="checkout_title">
                    <h2>Secure checkout</h2>
                </div>
                <div class="checkout_content">
                    <div class="customer_information checkout_content_inner">
                        <h2>SHIPPING INFORMATION</h2>
                        <table class="table">
                            <thead class="thead-default">
                            <tr>
                                <th></th>
                                <th>Select Address</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(address,index) in shippingaddress">
                                <td class="align-middle">
                                    <div class="form-group">
                                        <div class="custom_radio">
                                            <input class="location" v-bind:id="'address'+[address.id]" type="radio"  v-bind:checked="index"  v-model="Checkout.address_id" :value="address.id" name="address_id" @click="SelectAddress(address)" >
                                            <label :for="'address'+[address.id]"></label>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                        <span >{{address.address}}, {{address.city}}</span>
                                        <span v-if="address.state == null ">{{address.state_text}}</span>
                                        <span v-else>{{address.state.name}}</span>
                                        <span v-if="address.country" >{{address.country.name}},-{{address.zip}}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="align-middle">
                                    <div class="form-group">
                                        <div class="custom_radio">
                                            <input class="location"  type="radio" id="address999"  v-model="Checkout.address_id" value="0" name="address_id" >
                                            <label for="address999"> </label>
                                        </div>
                                    </div>
                                </td>
                                <td>  <label for="address999">Add New Address</label>  </td>
                            </tr>
                            <span v-if="FormError.address_id" class="has-error">{{FormError.address_id[0]}}</span>
                            </tbody>
                        </table>

                        <div class="c_i_form" v-if="Checkout.address_id == 0">
                            <label for="">Location</label>
                            <div class="form-group">
                                <div class="custom_radio">
                                    <input class="location" type="radio" id="locationUS" v-model="Checkout.location" value="US" :checked="Checkout.location=='US'" name="location" @click="ChangeLocation(1)" >
                                    <label for="locationUS">United States </label>
                                </div>
                                <div class="custom_radio">
                                    <input class="location" type="radio" id="locationCA" v-model="Checkout.location" value="CA" :checked="Checkout.location=='CA'"  name="location" @click="ChangeLocation(2)">
                                    <label for="locationCA">Canada</label>
                                </div>
                                <div class="custom_radio">
                                    <input class="location" type="radio" id="locationInt" v-model="Checkout.location" value="INT" :checked="Checkout.location=='INT'"  name="location" @click="ChangeLocation(null)">
                                    <label for="locationInt">International</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" id="address" name="address" placeholder="Address" v-model="Checkout.address">
                                <span v-if="FormError.address" class="has-error">{{FormError.address[0]}}</span>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" id="city" name="city" Placeholder="City" v-model="Checkout.city">
                                <span v-if="FormError.city" class="has-error">{{FormError.city[0]}}</span>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-6">
                                    <input class="form-control" type="text" id="zipCode" name="zipCode" placeholder="Zip Code" v-model="Checkout.zipCode">
                                    <span v-if="FormError.zipCode" class="has-error">{{FormError.zipCode[0]}}</span>
                                </div>
                                <div class="col-lg-6 form-group" id="form-group-state" v-if="Checkout.location == 'INT'">
                                    <input class="form-control" type="text" id="state" name="state" Placeholder="State" v-model="Checkout.state">
                                    <span v-if="FormError.state" class="has-error">{{FormError.state[0]}}</span>
                                </div>
                                <div class="col-lg-6 form-group" id="form-group-state-select" v-if="Checkout.location != 'INT'">
                                    <select class="form-control" id="stateSelect" name="stateSelect" v-model="Checkout.stateSelect">
                                        <option  value="">Select State</option>
                                        <template v-for="state in States">
                                            <option  v-bind:value="state.id" :selected="state.code == 'US'">{{state.name}}</option>
                                        </template>
                                    </select>
                                    <span v-if="FormError.stateSelect" class="has-error">{{FormError.stateSelect[0]}}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <select class="form-control" id="country" name="country" ref="country" v-model="Checkout.country" :disabled="Checkout.location != 'INT'">
                                    <template v-for="country in GetCountry.countries">
                                        <option :data-code="country.code"  v-bind:value="country.id"  :selected="country.code == Checkout.location">{{country.name}}</option>
                                    </template>
                                </select>
                                <span v-if="FormError.country" class="has-error">{{FormError.country[0]}}</span>
                            </div>
                        </div>






                        <h2>BILLING INFORMATION</h2>
                        <div class="c_i_form">
                            <label for="">Location</label>
                            <div class="form-group">
                                <div class="custom_radio">
                                    <input class="location" type="radio" id="factoryLocationUS" v-model="Checkout.factoryLocation" value="US" :checked="Checkout.factoryLocation=='US'" name="factoryLocation" @click="ChangeLocationFactory(1)">
                                    <label for="factoryLocationUS">United States </label>
                                </div>
                                <div class="custom_radio">
                                    <input class="location" type="radio" id="factoryLocationCA" v-model="Checkout.factoryLocation" value="CA" :checked="Checkout.factoryLocation=='CA'"  name="factoryLocation" @click="ChangeLocationFactory(2)">
                                    <label for="factoryLocationCA">Canada</label>
                                </div>
                                <div class="custom_radio">
                                    <input class="location" type="radio" id="factoryLocationInt" v-model="Checkout.factoryLocation" value="INT" :checked="Checkout.factoryLocation=='INT'"  name="factoryLocation" @click="ChangeLocationFactory(null)">
                                    <label for="factoryLocationInt">International</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" id="factoryAddress" name="factoryAddress" placeholder="Address" v-model="Checkout.factoryAddress">
                                <span v-if="FormError.factoryAddress" class="has-error">{{FormError.factoryAddress[0]}}</span>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" id="factoryCity" name="factoryCity" Placeholder="City" v-model="Checkout.factoryCity">
                                <span v-if="FormError.factoryCity" class="has-error">{{FormError.factoryCity[0]}}</span>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-6">
                                    <input class="form-control" type="text" id="factoryZipCode" name="factoryZipCode" placeholder="Zip Code" v-model="Checkout.factoryZipCode">
                                    <span v-if="FormError.factoryZipCode" class="has-error">{{FormError.factoryZipCode[0]}}</span>
                                </div>
                                <div class="col-lg-6 form-group"   v-if="Checkout.factoryLocation == 'INT'">
                                    <input class="form-control" type="text" id="factoryState" name="factoryState" Placeholder="State" v-model="Checkout.factoryState">
                                    <span v-if="FormError.factoryState" class="has-error">{{FormError.factoryState[0]}}</span>
                                </div>
                                <div class="col-lg-6 form-group" v-if="Checkout.factoryLocation != 'INT'">
                                    <select class="form-control" id="factoryStateSelect" name="factoryStateSelect" v-model="Checkout.factoryStateSelect">
                                        <option value="">Select State</option>
                                        <template v-for="state in StatesFactory">
                                            <option  v-bind:value="state.id" :selected="state.code == 'US'">{{state.name}}</option>
                                        </template>
                                    </select>
                                    <span v-if="FormError.factoryStateSelect" class="has-error">{{FormError.factoryStateSelect[0]}}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <select class="form-control"  v-model="Checkout.factoryCountry"  :disabled="Checkout.factoryLocation != 'INT'">
                                    <template v-for="country in GetCountry.countries">
                                        <option  :data-code="country.code"  :value="country.id"  :selected="country.code == Checkout.factoryLocation">{{country.name}}</option>
                                    </template>
                                </select>
                                <span v-if="FormError.factoryCountry" class="has-error">{{FormError.factoryCountry[0]}}</span>
                            </div>
                        </div>












                    </div>
                    <div class="payment_opt checkout_content_inner">
                        <h2>PAY BY</h2>
                        <div class="checkout_tab">
                            <ul>
                                <div class="credit_card_info  " id="Credit">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Name" v-model="Checkout.name">
                                        <span v-if="FormError.name" class="has-error">{{FormError.name[0]}}</span>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Card Number" v-model="Checkout.number"  />
                                        <span v-if="FormError.number" class="has-error">{{FormError.number[0]}}</span>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-6">
                                            <input class="form-control" type="text"  placeholder="MM/YY"  v-mask="{mask: '99/99',greedy: true}" id="expiry" v-model="Checkout.expiry">
                                            <span v-if="FormError.expiry" class="has-error">{{FormError.expiry[0]}}</span>
                                        </div>
                                        <div class="form-group col-6">
                                            <input class="form-control" type="text" name="cvc" placeholder="CVC"  v-model="Checkout.cvc">
                                            <span v-if="FormError.cvc" class="has-error">{{FormError.cvc[0]}}</span>
                                        </div>
                                    </div>
                                </div>
                            </ul>
                        </div>

                        <h2>DELIVERY OPTION</h2>
                        <input type="hidden" id="free_shipping" name="free_shipping" :value="OrderData.free_shipping">
                        <p>Select the address that matches your card or payment method.</p>
                        <div class="checkout_tab mb-3">
                            <table class="table">
                                <thead class="thead-default">
                                <tr>
                                    <th></th>
                                    <th>Shipping method</th>
                                    <th>Fee</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="method in shippingmethod">
                                    <td class="align-middle">
                                        <div class="form-group">
                                            <div class="custom_radio">
                                                <input class="location" v-bind:id="'method'+[method.id]" type="radio"   v-model="Checkout.shipping_method" :value="method.id" name="shipping_method" >
                                                <label :for="'method'+[method.id]"></label>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                            <span class="text-medium">{{method.courier.name}}</span><br>
                                            <span class="text-muted text-sm">{{method.name}}</span>
                                    </td>
                                    <td>
                                        <span v-if="method.fee === null">Actual Rate</span>
                                        <span v-else>{{method.fee}}</span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        <span v-if="FormError.shipping_method" class="has-error">{{FormError.shipping_method[0]}}</span>
                        </div>

                        <!-- <div class="c_i_form">
                            <h2>PROMOTIONAL CODE / GIFT VOUCHER CODE</h2>
                            <div class="checkout_promo">
                                <input type="text" class="form-control" placeholder="Enter Promo Code" v-model="Coupon.coupon">
                                <button class="btn_common" @click.prevent="ApplyCoupon()">apply</button>
                            </div>
                        </div> -->
                    </div>
                    <div class="order_summery checkout_content_inner">
                        <h2>ORDER SUMMARY</h2>
                        <div class="checkout_table_summery" v-if="ShowCartItems.cartitems" v-bind:class="{ addscroll: ShowCartItems.cartitems.length > 4 }">
                            <table class="table mb_0">
                                <colgroup>
                                    <col style="width: 92px;">
                                    <col style="width: calc(100% - 92px);">
                                </colgroup>

                                <tr v-for="item in ShowCartItems.cartitems" :key="item.id">
                                    <td>
                                        <div class="c_t_img">
                                            <img v-if="item.item.images" :src="item.item.images[0].thumbs_image_path" style="width: 70px;" class="img-fluid" alt="">
                                            <img v-else :src="ShowCartItems.default_img.value" style="width: 70px;" class="img-fluid" alt="">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="c_t_text">
                                            <h2>{{item.item.name}} <span v-if="item.color"> - {{item.color.name}}</span></h2>
                                            <p v-if="item.item.brand">{{item.item.brand.name}}</p>
                                            <ul>
                                                <li v-if="item.itemsize">Size: <span>{{item.itemsize.item_size}}</span></li>
                                                <li>Price: <span>USD ${{item.item.price}}</span></li>
                                                <li>Qty: <span>{{item.quantity}}</span></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>


                            </table>
                        </div>
                        <div class="cart_right_table">
                            <table class="table">
                                <tr>
                                    <td>SUBTOTAL</td>
                                    <td>USD ${{OrderData.subtotal}} </td>
                                </tr>
                                <tr>
                                    <td><b>GRAND TOTAL</b></td>
                                    <td><b>USD ${{OrderData.total}}</b></td>
                                </tr>
                            </table>
                        </div>
                        <div class="proceed_btn text-center">
                            <button class="btn_common" type="submit"><i class="lni-lock"></i> <span class="ml_5">Pay Securely Now <i v-if="CheckoutLoader" class="fa fa-spinner fa-pulse fa-3x fa-fw"></i> </span> </button>
                        </div>
                    </div>
                </div>
            </form>
    </section>

      </span>
</template>
<script src="./node_modules/inputmask/dist/inputmask/dependencyLibs/inputmask.dependencyLib.js"></script>
<script src="./node_modules/inputmask/dist/inputmask/inputmask.js"></script>
<script src="./dist/vue-inputmask-browser.js"></script>
<script>

export default {
    name:'Header',
    props: ['csrf', 'oldName'],
    data(){
        return{
                id:'',
                CheckoutLoader:false,
                Countries:'',
                StatesStore:[],
                States:'',
                StatesFactory:'',
                OrderData:{},
                FormError:  { },
                RetatedData:{},
                shippingmethod:{},
                shippingaddress:{},
                Coupon: new Form({
                    'id':null,
                    'coupon':null,
                }),
                Checkout: new Form({
                    id: this.$route.query.id,
                    location: 'US',
                    address: '',
                    city: '',
                    address_id: null,
                    zipCode: '',
                    state: '',
                    stateSelect: '',
                    country: 1,
                    phone: '',

                    factoryLocation: 'US',
                    factoryAddress: '',
                    factoryZipCode: '',
                    factoryCountry: 1,
                    factoryStateSelect: '',
                    factoryState: '',
                    factoryPhone:'',

                    name: '',
                    number:'',
                    expiry:'',
                    cvc:'',
                    free_shipping: null,
                    shipping_method:'',
                    paymentMethod:2,
                }),
        }
    },
    mounted(){
        this.HeaderHide();
        this.$store.dispatch('GetAllState')
        this.$store.dispatch('GetAllCountry')
        this.$store.dispatch('GetCartItem');
        this.GetOrderInformation();


    },
    computed:{
        GetCountry(){
            this.StatesStore = this.$store.getters.GetAllSate
            this.ChangeLocation();
            this.ChangeLocationFactory();
            return this.$store.getters.GetAllCountry
        },
        GetState(){

        },
        ShowCartItems(){
            return this.$store.getters.ShowCartItems
        },
    },
    methods:{
        ChangeLocation(id){
            if(this.Checkout.location == 'US'){
                this.States  =  this.StatesStore.usStates
                return this.States;
            }else if(this.Checkout.location == 'CA'){
                this.States  =  this.StatesStore.caStates
                return this.States;
            }
        },
        ChangeLocationFactory(id){
            if(this.Checkout.factoryLocation == 'US'){
                this.StatesFactory  =  this.StatesStore.usStates
                return this.StatesFactory;
            }else if(this.Checkout.factoryLocation == 'CA'){
                this.StatesFactory  =  this.StatesStore.caStates
                return this.StatesFactory;
            }
        },
        SelectAddress(data){
            this.Checkout.address_id = data.id;
        },
        HeaderHide(){
            //   $('header.Common_header').css('display','none');
        } ,

        RecallStateCountry(){
            this.$store.dispatch('GetAllState')
            this.$store.dispatch('GetAllCountry')
        },
        CheckoutPost(){
            this.CheckoutLoader = true;
            this.Checkout.post('/api/v1/checkoutpost',{headers: { 'Authorization': 'Bearer '+localStorage.getItem('access_token')}})
            .then((response)=>{
                if(response.data.message == 'success'){
                    toast.fire({
                    icon: 'success',
                    title: 'Order Complete successfully'
                    })
                    this.$router.push('/order-complete')
                }
                this.FormError= [];
                this.CheckoutLoader = false;
                return this.FormError= response.data.error;
            })
        },
        GetOrderInformation(){
            axios.get('/api/v1/checkout?id='+this.$route.query.id, { id:'id',headers: { 'Authorization': 'Bearer '+localStorage.getItem('access_token')}})
                .then((response)=>{
                this.OrderData = response.data.order
                this.RetatedData = response.data
                this.shippingmethod = response.data.shipping_methods
                this.shippingaddress = response.data.shippingaddress
                })
        },
        ApplyCoupon(){
            this.Coupon.id = this.OrderData.id;
            this.Coupon.post('/api/v1/ApplyCoupon', {headers: { 'Authorization': 'Bearer '+localStorage.getItem('access_token')}})
                .then((response)=>{
                    alert('Yet, Coupon Part Not complete on backend.');
                })
        },

    },
    watch:{
        $route(to,from){
            this.RecallStateCountry();
        }
    }
}

$(function () {
// $('#expiry').inputmask();
});
</script>
