<template>
<div>
    <div class="form-row mb_10">
        <div class="col-md-4">
            <div class="custom_radio">
                <input type="radio" id="locationUS" name="location" value="US" @change="changeShippingLocation('US')" v-model="shippingInput.location">
                <label for="locationUS">United States</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="custom_radio">
                <input type="radio" id="locationCA" name="location" value="CA" @change="changeShippingLocation('CA')"  v-model="shippingInput.location">
                <label for="locationCA">Canada</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="custom_radio">
                <input type="radio" id="locationINT" name="location" value="INT" @change="changeShippingLocation('INT')"  v-model="shippingInput.location">
                <label for="locationINT">International</label>
            </div>
        </div>
        <small style="color: red;" v-if="errorsCheck.location">This field required.</small>
        <small style="color:red" v-for="(formError, errorIndex) in formErrors['shippingInput.location']" :key="'error_name_'+errorIndex">{{formError.replace("shipping input.", "")}}</small>
    </div>
    <div class="form-row">
        <div class="form-group col-6">
            <input type="text" placeholder="First Name" class="form-control" v-model="shippingInput.first_name">
            <small style="color:red" v-for="(formError, errorIndex) in formErrors['shippingInput.first_name']" :key="'error_name_'+errorIndex">{{formError.replace("shipping input.", "")}}</small>
        </div>
        <div class="form-group col-6">
            <input type="text" placeholder="Last Name" class="form-control" v-model="shippingInput.last_name">
            <small style="color:red" v-for="(formError, errorIndex) in formErrors['shippingInput.last_name']" :key="'error_name_'+errorIndex">{{formError.replace("shipping input.", "")}}</small>
        </div>
    </div>
    <div class="form-group">
        <input type="text" class="form-control" placeholder="Street Address" v-model="shippingInput.address">
        <small style="color:red" v-for="(formError, errorIndex) in formErrors['shippingInput.address']" :key="'error_name_'+errorIndex">{{formError.replace("shipping input.", "")}}</small>
        <small style="color: red;" v-if="errorsCheck.address">This field required.</small>
    </div>
    <div class="form-row">
        <div class="form-group col-6">
            <input type="text" class="form-control" placeholder="City" v-model="shippingInput.city">
            <small style="color:red" v-for="(formError, errorIndex) in formErrors['shippingInput.city']" :key="'error_name_'+errorIndex">{{formError.replace("shipping input.", "")}}</small>
            <small style="color: red;" v-if="errorsCheck.city">This field required.</small>
        </div>
        <div class="form-group col-6" v-if="shippingStates.length">
            <select class="form-control" v-model="shippingInput.state_id" name="state_id">
                <option value="" selected>Select State</option>
                <option v-for="(state, stateIndex) in shippingStates" :key="'shipState_' + stateIndex" :value="state.id"> {{state.name}} </option>
            </select>
            <small style="color: red;" v-if="errorsCheck.state">This field required.</small>
            <small style="color:red" v-for="(formError, errorIndex) in formErrors['shippingInput.state_id']" :key="'error_name_'+errorIndex">{{formError.replace("shipping input.", "").replace("state id.", "State")}}</small>
        </div>
        <div class="form-group col-6" v-else>
            <input type="text" class="form-control" placeholder="State Name" v-model="shippingInput.state_text">
            <small style="color: red;" v-if="errorsCheck.state_text">This field required.</small>
            <small style="color:red" v-for="(formError, errorIndex) in formErrors['billingInput.state_text']" :key="'error_name_'+errorIndex">{{formError.replace("billing input.", "")}}</small>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-6">
            <input type="number" min="0" class="form-control" placeholder="Zip" v-model="shippingInput.zip">
            <small style="color:red" v-for="(formError, errorIndex) in formErrors['shippingInput.zip']" :key="'error_name_'+errorIndex">{{formError.replace("shipping input.", "")}}</small>
            <small style="color: red;" v-if="errorsCheck.zip">This field required.</small>
        </div>
        <div class="form-group col-6">
            <select class="form-control" v-model="shippingInput.country_id" :disabled="shippingInput.country_id === 1 || shippingInput.country_id === 2">
                <option value="">Select Country</option>
                <option v-for="(country, countryIndex) in shippingCountries" :key="'shipCountry_' + countryIndex" :value="country.id">{{country.name}} </option>
            </select>
            <small style="color:red" v-for="(formError, errorIndex) in formErrors['shippingInput.country_id']" :key="'error_name_'+errorIndex">{{formError.replace("shipping input.", "").replace("country id.", "Country")}}</small>
            <small style="color: red;" v-if="errorsCheck.country_id">This field required.</small>
        </div>
    </div>
    <div class="form-group">
        <input type="number" min="0" class="form-control" placeholder="Telephone" v-model="shippingInput.phone">
        <small style="color: red;" v-if="errorsCheck.phone">This field required.</small>
    </div>
</div>
</template>

<script>
export default {
    name: "ShippingAddress",
    data(){
        return {
            shippingCountries: this.countries,
            shippingStates: this.states,
        }
    },
    watch:{
        errorMessage(errorMessage) {
            if (errorMessage) {
                this.$swal({
                    icon: 'error',
                    title: errorMessage
                })
                this.$store.commit('ecommerceStore/setErrorMessage', '');
            }
        },
        states(states) {
            if (states) {
                this.shippingStates = states
            }
        },
        countries(countries) {
            if (countries) {
                this.shippingCountries = countries
            }
        },
    },
    computed:{
        formErrors: {
            get: function () {
                return this.$store.getters['ecommerceStore/getFormErrors']
            },
            set: function (errorClear = {}) {
                this.$store.commit('ecommerceStore/setFormErrors', errorClear);
            }
        },
    },
    props: {
        shippingInput: {
            type: Object,
            default() {
                return {};
            }
        },
        errorsCheck: {
            type: Object,
            default() {
                return {};
            }
        },
        states: {},
        countries: {},
    },
    methods:{
        changeShippingLocation(location){
            this.shippingCountries = this.countries
            if(location != 'INT'){
                let country = this.countries.find(q => q.code == location)
                this.shippingStates = this.states.filter(q => q.country_id == country.id)
                this.shippingInput.country_id = country.id;
            }else{
                this.shippingCountries = this.countries;
                this.shippingStates = [];
                this.shippingInput.country_id = '';
            }
            this.shippingInput.location = 'CA'
            console.log(this.shippingInput)
        },
    }
}
</script>

