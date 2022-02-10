<template>
    <!-- ============================
        START PROFILE DASHBOARD SECTION
    ============================== -->
    <div>
        <div class="my_account_dashboard">
            <div class="m_a_dashboard_title my_acc_container">
                <h2>Customer Address</h2>
                <p>Please fill the form below and press Save.</p>
            </div>
            <div class="my_acc_container" id="my_acc_container">
                <div class="account_info account_info_vertical">
                    <h2 class="my_acc_subtitle"> <span class=" mr_15">MY ADDRESSES </span>  <button class="btn_grey add_new_address width_full "    @click.prevent="showAddressModal()"><span> Add New Address </span></button></h2>

                    <ul class="acc_address" v-if="loading == true && loadState == 'list'">
                        <li style="position:relative; min-height: 100px" id="addressPreload">
                            <div class="preloader_wrap">
                                <div class="loader-container">
                                    <div class="loader-circle"></div>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <ul class="acc_address" v-else>
                        <li v-for="(address, addressKey) in addresses" :key="'address_' + addressKey">
                            <span>ADDRESS #{{ addressKey + 1}}
                                <div class="text-danger">
                                    <template v-if="address.set_default && address.set_default.includes('billing')">(Default Billing Address)</template>
                                    <template v-if="address.set_default && address.set_default.includes('shipping')">(Default Shipping Address)</template>
                                </div>
                                <div>
                                <a href="javascript:void(0)" v-if="!address.set_default || !address.set_default.includes('billing')" @click.prevent="setDefaultAddress(address.id, 'billing')">Set Billing |</a>
                                <a href="javascript:void(0)" v-if="!address.set_default || !address.set_default.includes('shipping')" @click.prevent="setDefaultAddress(address.id, 'shipping')">Set Shipping | </a>
                                <a href="javascript:void(0)" @click.prevent="setAddress(address, 'shipping')">Edit</a> |
                                <a href="javascript:void(0)" @click.prevent="deleteAddresses(address.id)">Delete</a>
                                </div>
                            </span>
                            <address>
                                <template v-if="address.first_name || address.last_name">{{address.first_name }} {{address.last_name }}<br></template>
                                <template v-if="address.city">{{address.city}} <br></template>
                                <template v-if="address.address">{{address.address }} <br></template>
                                <template v-if="address.address2">{{address.address2}} <br></template>
                                <template v-if="address.state || address.zip">{{address.state ? address.state.name : ''}} - {{address.zip}} <br></template>
                                <template v-if="address.country">{{address.country ? address.country.name : ''}} <br></template>
                                <template v-if="address.phone">Phone: {{address.phone}}  </template>
                                <template v-if="address.fax">FAX: {{address.fax}}</template>
                            </address>
                        </li>
                    </ul>
                </div>
                <!-- <div class="review_area">
                    <div class="review_content">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="review_bottom">
                                    <addressPagination v-if="addressesPaginate" :paginateData="addressesPaginate" @paginate="pagePreloads" ></addressPagination>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
            <div class="my_acc_container" id="myDiv">
                <template v-if="addressForm">
                <div class="account_info" style="position:relative; min-height: 100px">
                    <div class="preloader_wrap" id="addressFormPreload"  v-if="loading == true && loadState == 'form'">
                        <div class="loader-container">
                            <div class="loader-circle"></div>
                        </div>
                    </div>
                    <div class="account_info_left">
                        <h2 class="my_acc_subtitle">CONTACT INFORMATION</h2>
                        <div class="mb_20">
                            <div class="custom_radio">
                                <input type="radio" id="shipping" name="type" value="shipping" v-model="addressInput.set_default">
                                <label for="shipping">Use for shipping</label>
                            </div>
                            <div class="custom_radio">
                                <input type="radio" id="billing" name="type" value="billing" v-model="addressInput.set_default">
                                <label for="billing">Use for Billing</label>
                            </div>
                            <small style="color:red" v-for="(formError, errorIndex) in formErrors['set_default']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></small>
                        </div>
                        <div class="form_inline_border form_inline">
                            <label class="required" for="input_id_first_name">First Name</label>
                            <input type="text" class="form-control" v-model="addressInput.first_name" id="input_id_first_name" placeholder="First Name">
                            <small v-for="(formError, errorIndex) in formErrors['first_name']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></small>
                        </div>
                        <div class="form_inline_border form_inline">
                            <label class="" for="input_id_last_name">Last Name</label>
                            <input type="text" class="form-control" v-model="addressInput.last_name" id="input_id_last_name" placeholder="Last Name">
                            <small v-for="(formError, errorIndex) in formErrors['last_name']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></small>
                        </div>
                        <div class="form_inline_border form_inline">
                            <label class="required" for="input_id_phone">Telephone</label>
                            <input type="number" class="form-control" placeholder="" v-model="addressInput.phone" id="input_id_phone" placeholder="Phone">
                            <small v-for="(formError, errorIndex) in formErrors['phone']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></small>
                        </div>
                        <div class="form_inline_border form_inline">
                            <label class="" for="input_id_fax">Fax</label>
                            <input type="text" class="form-control" placeholder="" v-model="addressInput.fax" id="input_id_fax" placeholder="Fax">
                            <small v-for="(formError, errorIndex) in formErrors['fax']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></small>
                        </div>
                        <div class="form_inline_border form_inline">
                            <label class="required" for="input_id_zip">Zip</label>
                            <input type="number" class="form-control" placeholder="" v-model="addressInput.zip" id="input_id_zip" placeholder="Zip">
                            <small v-for="(formError, errorIndex) in formErrors['zip']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></small>
                        </div>
                    </div>
                    <div class="account_info_right">
                        <h2 class="my_acc_subtitle">ADDRESS</h2>
                        <div class="form_inline_border form_inline">
                            <label class="required" for="input_id_address">Street Address Line 1</label>
                            <input type="text" class="form-control" placeholder="" v-model="addressInput.address" id="input_id_address" placeholder="Address">
                            <small v-for="(formError, errorIndex) in formErrors['address']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></small>
                        </div>
                        <div class="form_inline_border form_inline">
                            <label for="input_id_location">Street Location</label>
                            <input type="text" class="form-control" placeholder="" v-model="addressInput.address2" id="input_id_location" placeholder="Street Location">
                            <small v-for="(formError, errorIndex) in formErrors['address2']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></small>
                        </div>
                        <div class="form_inline_border form_inline">
                            <label class="required" for="input_id_city">City</label>
                            <input type="text" class="form-control" placeholder="" v-model="addressInput.city" id="input_id_city" placeholder="City">
                            <small v-for="(formError, errorIndex) in formErrors['city']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></small>
                        </div>

                        <div class="form_inline_border form_inline_border_select form_inline">
                            <label class="required" for="input_id_country_id">Country</label>
                            <select class="form-control" v-model="addressInput.country_id" id="input_id_country_id" @change="countryChange">
                                <option value="">Select Country</option>
                                <option v-for="(country, countryIndex) in countries"
                                        :key="'addressCountry_' + countryIndex"
                                        :value="country.id"
                                        :data-code="country.code">{{country.name}} </option>
                            </select>
                            <small v-for="(formError, errorIndex) in formErrors['country_id']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></small>
                        </div>
                        <div class="form_inline_border form_inline_border_select form_inline" v-if="stateFieldStatus === 'id'">
                            <label class="required" for="input_id_state_id">State</label>
                            <select class="form-control" v-model="addressInput.state_id" id="input_id_state_id">
                                <option value="">Select State</option>
                                <option v-for="(state, stateIndex) in computedStates" :key="'addressState_' + stateIndex" :value="state.id"> {{state.name}} </option>
                            </select>
                            <small v-for="(formError, errorIndex) in formErrors['state_id']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></small>
                        </div>
                        <div class="form_inline_border form_inline" v-if="stateFieldStatus === 'text'">
                            <label class="required" for="state_text">State</label>
                            <input type="text" class="form-control" placeholder="" v-model="addressInput.state_text" id="state_text" placeholder="State">
                            <small v-for="(formError, errorIndex) in formErrors['state_text']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></small>
                        </div>
                        <div class="account_info_left width_200p d_flex_inline">
                            <button class="btn_grey m_a_close_btn width_200p" @click.prevent="closeAddressModal()"><span> Close </span></button>
                        </div>
                        <button class="btn_grey width_200p float-right" @click.prevent="addOrUpdateAddress()"  ref="addressButton"><span v-if="addressInput.id"> Update </span><span v-else> Save </span></button>

                    </div>
                </div>
                </template>
            </div>

        </div>
        <div class="my_acc_container my_acc_back">
            <router-link :to="{ name: 'customer-dashboard'}" class="btn btn_transparent width_200p">
                <i class="fas fa-shopping-cart"></i> <span class="ml_5">Back to Dashboard</span>
            </router-link>
            <button class="btn_transparent width_200p" @click.prevent="buyerLogout">Log Out</button>
        </div>

        <div class="logout_mobile">
            <button class="btn_transparent" @click.prevent="buyerLogout">logout</button>
        </div>

        <div class="wcmodel modal" :class="{open_modal:deletemodal}" data-modal="wcm" >
            <div class="modal_overlay" data-modal-close="wcm"></div>
            <div class="welcome_modal_wrapper">
                <div class="modal_inner modal_400p">
                    <span class="close_modal" data-modal-close="wcm" @click="deleteModalClose"></span>
                    <div class="p_20">
                        <div class="d_flex_center d_modal_inner">
                            <i class="fas fa-exclamation-triangle"></i>
                            <p >Are you sure want to delete address ?</p>
                            <div class="d_flex_end width_full">
                                <button class="btn mb_20 btn_common" @click="addressDeleteConfirm">Yes</button>
                                <button class="btn  mb_20 btn_common btn_disabled ml_10" @click="addressDeleteConfirm">No</button>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================
        END PROFILE DASHBOARD SECTION
    ============================== -->
</template>
<script>
    import customerStore from './customerStore'
    import defaultStore from '../layouts/defaultStore';
    import validate from 'validate.js'
    import mixins from '../helpers/mixins'
    export default {
        name:'customerDashboard',
        mixins: [mixins],
        components: {
            productComponent: () => import(/* webpackChunkName: "js/custoemr/productComponent" */ './components/WishlistProduct.vue'),
            addressPagination: () => import(/* webpackChunkName: "js/custoemr/AddressPagination" */ './components/Pagination.vue'),
        },
        data() {
            return {
                addressInput: {
                    id: '',
                    first_name: '',
                    last_name: '',
                    city: '',
                    address: '',
                    address2: '',
                    phone: '',
                    fax: '',
                    zip: '',
                    country_id: '',
                    state_id: '',
                    location: '',
                    set_default: 'shipping',
                    state_text: '',
                },
                loadState: 'list',
                addressLoader: null,
                country_id: '',
                addressForm: false,
                deletemodal: false,
                deleteId: null,
                stateFieldStatus: 'id',
            }
        },
        beforeCreate() {
            if (!(this.$store && this.$store.state && this.$store.state['defaultStore'])) {
                this.$store.registerModule("defaultStore", defaultStore);
            }
            if (!(this.$store && this.$store.state && this.$store.state['customerStore'])) {
                this.$store.registerModule("customerStore", customerStore);
            }
        },
        mounted(){
            this.pagePreloads();
            this.formErrors = {};
        },
        watch: {
            loading(loading) {
                if(loading === false) {
                    $("#addressPreload").fadeOut("slow");
                    $("#addressFormPreload").fadeOut("slow");
                } else {
                    switch (this.loadState) {
                        case 'list':
                            $("#addressPreload").fadeIn();
                            break;
                        case 'form':
                            $("#addressFormPreload").fadeIn();
                            break;
                    }
                }
                if(this.addressLoader && loading === false) {
                    this.addressLoader.hide();
                    this.addressLoader = null;
                }
            },
        },
        computed:{
            loading() {
                return this.$store.getters['customerStore/getLoading']
            },
            formErrors: {
                get: function () {
                    return this.$store.getters['customerStore/getFormErrors']
                },
                set: function (errorClear = {}) {
                    this.$store.commit('customerStore/setFormErrors', errorClear);
                }
            },
            addresses() {
                return this.$store.getters['customerStore/getAddresses']
            },
            addressesPaginate() {
                return this.$store.getters['customerStore/getAddressesPaginate']
            },
            countries() {
                return this.$store.getters['defaultStore/getCountries']
            },
            states() {
                return this.$store.getters['defaultStore/getStates']
            },
            computedStates() {
                let country_id = this.addressInput.country_id;
                let states = this.states;
                let computedStates = [];
                if (country_id) {
                    computedStates = states.filter(state => state.country_id == country_id);
                }
                return computedStates;
            }
        },
        methods:{
            pagePreloads(page = 1){
                this.loadState = 'list'
                this.$store.dispatch('customerStore/addresses', page)
                this.$store.dispatch('defaultStore/states');
                this.$store.dispatch('defaultStore/countries');
            },
            buyerLogout() {
                this.$Progress.start()
            },
            deleteModalClose() {
                this.deletemodal= false;
                $('body').css('padding-right', '0px');
                $('.header_area').css('margin-right', '0px');
                $('body').removeClass('model_open');
                $('.wcmodel').removeClass('open_modal');
            },
            deleteAddresses(addressId) {
                this.deletemodal = true;
                this.deleteId = addressId;
                this.loadState = 'list'
            },
            addressDeleteConfirm(){
                let formData = {
                    address_id: this.deleteId
                }
                this.$store.dispatch('customerStore/deleteAddresses', formData).then(()=>this.deletemodal = false);
            },
            showAddressModal() {
                this.stateFieldStatus = 'id'
                this.addressForm = true;
                var pos = $("#myDiv").offset().top;
                $('body, html').animate({scrollTop: pos});
            },
            closeAddressModal() {
                this.addressForm = false;
                var pos = $("#my_acc_container").offset().top;
                $('body, html').animate({scrollTop: pos});
            },
            setDefaultAddress(addressId, setDefault) {
                this.loadState = 'list'
                let formData = {
                    address_id: addressId,
                    set_default: setDefault
                }
                this.$store.dispatch('customerStore/setDefaultAddress', formData).then((responce)=>{
                    this.addressForm = false;
                });
            },
            setAddress(address) {
                if((address.country_id === '1') || (address.country_id === '2')){
                    this.stateFieldStatus = 'id'
                }else {
                    this.stateFieldStatus = 'text'
                }
                this.loadState = 'form'
                this.formErrors = {};
                this.$set(this, 'addressInput', address);
                this.addressForm = true;
                var pos = $("#myDiv").offset().top;
                $('body, html').animate({scrollTop: pos});
            },
            resetInput() {
                let address = {
                    id: '',
                    first_name: '',
                    last_name: '',
                    city: '',
                    address: '',
                    address2: '',
                    phone: '',
                    fax: '',
                    zip: '',
                    country_id: '',
                    state_id: '',
                    location: '',
                    set_default: '',
                }
                this.$set(this, 'addressInput', address);
            },
            addOrUpdateAddress() {
                this.loadState = 'form'
                if(!this.validateAddress()) return;
                this.addressLoader = this.inlineLoader({ container: this.$refs.addressButton })
                if(this.addressInput.id != '') {
                    this.$store.dispatch('customerStore/updateAddress', this.addressInput).then((responce)=>{
                        this.addressForm = false;
                    });
                } else {
                    this.$store.dispatch('customerStore/newAddress', this.addressInput).then((responce)=>{
                        this.addressForm = false;
                    });
                }
                this.resetInput();
                var pos = $("#my_acc_container").offset().top;
                $('body, html').animate({scrollTop: pos});
            },
            countryChange(el) {
                if (el.target.options.selectedIndex > -1) {
                    const target = el.target.options[el.target.options.selectedIndex].dataset;
                    this.addressInput.location = target.code;
                }

                if((this.addressInput.country_id === 1) || (this.addressInput.country_id === 2)){
                    this.stateFieldStatus = 'id'
                }else {
                    this.stateFieldStatus = 'text'
                }
            },
            validateAddress() {
                let isValid = true;
                if(this.stateFieldStatus === 'id'){
                    const constraints = {
                        state_id: {
                            presence: {
                                allowEmpty: false,
                                message: '^ Field is required.'
                            },
                        },
                    }
                    const addressErrors = validate(this.addressInput, constraints);
                    if (addressErrors) {
                        this.formErrors = addressErrors
                        isValid = false;
                    }
                }
                if(this.stateFieldStatus === 'text'){
                    const constraints = {
                        state_text: {
                            presence: {
                                allowEmpty: false,
                                message: '^ Field is required.'
                            },
                        },
                    }
                    const addressErrors = validate(this.addressInput, constraints);
                    if (addressErrors) {
                        this.formErrors = addressErrors
                        isValid = false;
                    }
                    // return isValid;
                }

                const constraints = {
                    first_name: {
                        presence: {
                            allowEmpty: false,
                            message:'^ Field is required.'
                        },
                    },
                    address: {
                        presence: {
                            allowEmpty: false,
                            message:'^ Field is required.'
                        },
                    },
                    set_default: {
                        presence: {
                            allowEmpty: false,
                            message:'^ Field is required.'
                        },
                    },
                    city: {
                        presence: {
                            allowEmpty: false,
                            message:'^ Field is required.'
                        },
                    },
                    phone: {
                        presence: {
                            allowEmpty: false,
                            message:'^ Field is required.'
                        },
                    },
                    zip: {
                        presence: {
                            allowEmpty: false,
                            message:'^ Field is required.'
                        },
                    },
                    location: {
                        presence: {
                            allowEmpty: false,
                            message:'^ Field is required.'
                        },
                    },
                    country_id: {
                        presence: {
                            allowEmpty: false,
                            message:'^ Field is required.'
                        },
                    },
                }

                const addressErrors = validate(this.addressInput, constraints);
                if (addressErrors) {
                    this.formErrors = addressErrors
                    isValid = false;
                }

                return isValid;

            },
        },
    }
</script>
