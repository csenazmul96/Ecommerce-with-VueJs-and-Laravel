<template>
    <section class="hl_checkout">
        <div class="hl_checkout_wrap">
            <div class="container">
                <div class="row" v-if="cartItems.length">
                    <div class="col-12">
                        <div class="checkout_step_head_wrap">
                            <div class="checkout_step_head">
                                <ul v-if="isLoggedIn">
                                    <li :class="{active:activeTab === 'shipping'}" @click="selectTab('shipping')"><span>1</span> Shipping</li>
                                    <li :class="{active:activeTab === 'payment'}" @click="selectTab('payment')"><span>2</span> Payment</li>
                                </ul>
                                <ul v-if="!isLoggedIn">
                                    <li :class="{active:activeTab === 'information'}" @click="selectTab('information')"><span>1</span> Information</li>
                                    <li :class="{active:activeTab === 'shipping'}" @click="selectTab('shipping')"><span>2</span> Shipping</li>
                                    <li :class="{active:activeTab === 'payment'}" @click="selectTab('payment')"><span>3</span> Payment</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7 col-lg-8">
                        <div class="hl_checkout_left">
                            <div class="checkout_login_btn" v-if="!customer">
                                <div class="inner">
                                    <router-link :to="{ name:'customer-login'}"><button class="btn_common">Login</button></router-link>
                                </div>
                                <div class="inner">
                                    <button id="btn_google_mbl" class="btn_google"><span><i class="fab fa-google"></i></span> <span> Google</span></button>
                                </div>
                                <div class="inner">
                                    <button class="btn_facebook" @click="logInWithFacebook"><span><i class="fab fa-facebook-f"></i></span> <span> facebook</span></button>
                                </div>
                            </div>
                            <div class="checkout_table_mobile below_mobile">
                                <div class="hl_checkout_right pt_0">
                                    <button class="btn btn-link checkout_mobile_collapse_ic" type="button">
                                        <span class="checkout_text_toggle"><i class="lni lni-cart"></i><b id="toggle_text"> order summary <i class="lni lni-chevron-down"></i></b> </span> <span>${{computedTotal | round(2)}}</span>
                                    </button>
                                    <div class="checkout_mobile_collapse_content">
                                        <div class="hl_order_show" :class="{scroll: cartItems.length > 3}">
                                            <table>
                                                <template v-if="cartItems && cartItems.length > 0">
                                                    <tr v-for="(cartItem, cartItemKey) in cartItems" :key="'cartItem_' + cartItemKey">
                                                        <td>
                                                            <div class="inner">
                                                                <img :src="cartItem.item_images && cartItem.item_images.length > 0 ? (cartItem.item_images[0].thumbs_img) : ('/' + defaultImage.value)"
                                                                     :alt="'Hologram Product ' + cartItem.style_no"
                                                                     class="img-fluid"  @error="imgErrHndlHasan($event, cartItem.item_images[0].thumbs_img)">
                                                                <div class="text">
                                                                    <h2>{{cartItem.item && cartItem.item.name ? cartItem.item.name : 'No Specific Name'}}</h2>
                                                                    <p>${{cartItem.item ? cartItem.item.price : '0'}}</p>
                                                                    <p>Qty : {{cartItem.quantity}}</p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- =================== First step start ================== -->
                            <div class="step_1 d_none" :class="{'d-block':activeTab === 'information'}">
                                <h3 class="hl_checkout_subtitle">Email Address</h3>
                                <div class="form-group mb_5">
                                    <input type="text" class="form-control" placeholder="Email" v-model="userInput.email">
                                    <small style="color:red" v-for="(formError, errorIndex) in formErrors['userInput.email']" :key="'error_name_'+errorIndex">{{formError.replace("user input.", "")}}</small>
                                    <small style="color: red;" v-if="errorsCheck.email">Email field required.</small>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-6">
                                        <input type="text" class="form-control" placeholder="First Name" v-model="userInput.first_name">
                                        <small style="color:red" v-for="(formError, errorIndex) in formErrors['userInput.first_name']" :key="'error_name_'+errorIndex">{{formError.replace("user input.", "")}}</small>
                                        <small style="color: red;" v-if="errorsCheck.first_name">First Name field required.</small>
                                    </div>
                                    <div class="form-group col-6">
                                        <input type="text" class="form-control" placeholder="Last Name" v-model="userInput.last_name">
                                        <small style="color:red" v-for="(formError, errorIndex) in formErrors['userInput.last_name']" :key="'error_name_'+errorIndex">{{formError.replace("user input.", "")}}</small>
                                        <small style="color: red;" v-if="errorsCheck.last_name">Last Name field required.</small>
                                    </div>
                                </div>

                                <h3 class="hl_checkout_subtitle pt_20">Shipping Address</h3>
                                <div class="form-group" v-if="isLoggedIn">
                                    <select class="form-control active" v-model="shippingInput.id" @change="shippingChange()">
                                        <option value="">Select Shipping Address</option>
                                        <option v-for="(shippingAddress, shippingAddressIndex) in shippingAddresses" :key="'customerShipping_' + shippingAddressIndex" :value="shippingAddress.id">
                                            {{shippingAddress.first_name ? shippingAddress.first_name : '' + ' ' + shippingAddress.first_name ? shippingAddress.first_name : '' + ', '}}
                                            {{shippingAddress.address }}
                                            {{shippingAddress.state ? shippingAddress.state.name : ''}}
                                            {{' ' + shippingAddress.zip + ', '}}
                                            {{shippingAddress.country ? shippingAddress.country.code : ''}}
                                        </option>
                                        <option value="-1">New Address</option>
                                    </select>
                                    <small style="color:red" v-for="(formError, errorIndex) in formErrors['shippingInput.id']" :key="'error_name_'+errorIndex" >{{formError.replace("shipping input.", "")}}</small>
                                    <small style="color: red;" v-if="errorsCheck.s_address">Please select shipping address.</small>
                                </div>
                                <div v-else v-show="newShippingForm == true">
                                    <shipping-Address :shippingInput="shippingInput" :errorsCheck="errorsCheck" :countries="countries" :states="states" :shippingStates="shippingStates" :shippingCountries="shippingCountries"></shipping-Address>
                                </div>
                                <div class="d_flex_end c_shipping m_none">
                                    <router-link :to="{ name: 'cart'}">Return to cart</router-link>
                                    <button @click="selectTab('shipping')" class="btn_common width_200p step_1_btn"> Continue Shipping</button>
                                </div>
                            </div>
                            <!-- =================== First step Exit ================== -->

                            <!-- =================== Second step Start ================== -->
                            <div class="step_2" :class="{'d-block':activeTab === 'shipping'}">
                                <div class="inner"  v-if="userInput.email">
                                    <div class="c_custom_section">
                                        <div class="inner">
                                            <div class="c_custom_address">
                                                <span>Email</span>
                                                <p>{{userInput.email}}</p>
                                                <span v-if="!isLoggedIn" @click="selectTab('information')">Change</span>
                                            </div>
                                        </div>
                                        <div class="inner" v-if="shippingInput && shippingInput.first_name">
                                            <div class="c_custom_address c_ship_to" :class="{pb_10:editShippingAddress}">
                                                <span>Ship to</span>
                                                <p>
                                                    {{shippingInput.first_name ? shippingInput.first_name : '' + ' ' + shippingInput.first_name ? shippingInput.first_name : '' + ', '}}
                                                    {{shippingInput.address}}
                                                    {{shippingInput.state ? shippingInput.state.name : ''}}
                                                    {{' ' + shippingInput.zip + ', '}}
                                                    {{shippingInput.country ? shippingInput.country.code : ''}}
                                                </p>
                                                <span @click="editShippingAddress === true ? editShippingAddress = false : editShippingAddress = true">Change</span>
                                            </div>
                                            <div class="edit_shipping mt_20" v-if="editShippingAddress">
                                                <select class="form-control active" v-model="shippingInput.id" @change="shippingChange()" v-if="isLoggedIn && shippingAddresses.length > 1">
                                                    <option value="">Select Shipping Address</option>
                                                    <option v-for="(shippingAddress, shippingAddressIndex) in shippingAddresses" :key="'customerShipping_' + shippingAddressIndex" :value="shippingAddress.id">
                                                        {{shippingAddress.first_name ? shippingAddress.first_name : '' + ' ' + shippingAddress.first_name ? shippingAddress.first_name : '' + ', '}}
                                                        {{shippingAddress.address }}
                                                        {{shippingAddress.state ? shippingAddress.state.name : ''}}
                                                        {{' ' + shippingAddress.zip + ', '}}
                                                        {{shippingAddress.country ? shippingAddress.country.code : ''}}
                                                    </option>
                                                </select>
                                                <shipping-Address v-else :shippingInput="shippingInput" :errorsCheck="errorsCheck" :countries="countries" :states="states" :shippingStates="shippingStates" :shippingCountries="shippingCountries"></shipping-Address>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h3 class="hl_checkout_subtitle mt_20">Shipping Method </h3>
                                <div class="c_custom_section">
                                    <div class="inner" v-for="(shipMethod, shipMethodIndex) in shippingMethods" :key="'shipMethod_' + shipMethodIndex">
                                        <div class="custom_radio">
                                            <input type="radio" :id="'shipMethod_' + shipMethodIndex" name="shipMethod" :value="shipMethod" v-model="shippingMethod">
                                            <label :for="'shipMethod_' + shipMethodIndex">{{shipMethod.courier ? (shipMethod.courier.name + ' | ') : '' }} {{shipMethod.name }}</label>
                                        </div>
                                        <span>{{shipMethod.fee ? ('USD$ ' + shipMethod.fee) : 'Actual Cost'}}</span>
                                    </div>
                                    <small v-if="errorsCheck.payment" style="color:red">Select payment method</small>
                                </div>
                                <div class="d_flex_end c_shipping m_none">
                                    <router-link :to="{ name: 'cart'}">Return to cart</router-link>
                                    <button class="btn_common width_200p step_2_btn" @click="selectTab('payment')"> Continue to Payment</button>
                                </div>
                            </div>
                            <!-- =================== Second step Exit ================== -->
                            <!-- =================== Third step Start ================== -->
                            <div class="step_3" :class="{'d-block':activeTab === 'payment'}">
                                <div class="inner">
                                    <div class="c_custom_section">
                                        <div class="inner">
                                            <div class="c_custom_address">
                                                <span>Email</span>
                                                <p>{{userInput.email}}</p>
                                                <span v-if="!isLoggedIn" @click="selectTab('information')">Change</span>
                                            </div>
                                        </div>
                                        <div class="inner">
                                            <div class="c_custom_address" :class="{pb_10:editShippingAddress}">
                                                <span>Ship to</span>
                                                <p v-if="shippingInput">
                                                    {{shippingInput.first_name ? shippingInput.first_name : '' + ' ' + shippingInput.first_name ? shippingInput.first_name : '' + ', '}}
                                                    {{shippingInput.address}}
                                                    {{shippingInput.state ? shippingInput.state.name : ''}}
                                                    {{' ' + shippingInput.zip + ', '}}
                                                    {{shippingInput.country ? shippingInput.country.code : ''}}
                                                </p>
                                                <span @click="editShippingAddress === true ? editShippingAddress = false : editShippingAddress = true">Change</span>
                                            </div>
                                            <div class="edit_shipping mt_20" v-if="editShippingAddress">
                                                <shipping-Address :shippingInput="shippingInput" :errorsCheck="errorsCheck" :countries="countries" :states="states" :shippingStates="shippingStates" :shippingCountries="shippingCountries"></shipping-Address>
                                            </div>
                                        </div>
                                        <div class="inner">
                                            <div class="c_custom_address">
                                                <span>Shipping Method</span>
                                                <p v-if="shippingMethod" class="ml_20">
                                                    <i class="ml_20 pl_20">{{shippingMethod.courier ? (shippingMethod.courier.name + ' | ') : '' }} {{shippingMethod.name + ' | ' }} {{shippingMethod.fee ? ('USD$ ' + shippingMethod.fee) : 'Actual Cost'}}</i>
                                                </p>
                                                <span @click="selectTab('shipping')">Change</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row mt_20">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="c_custom_checkbox">
                                                <input type="checkbox" id="shipping_to_billing" :value="shippingToBilling" v-model="shippingToBilling" @click="useShippingToBilling()">
                                                <label for="shipping_to_billing">Use shipping address to billing address</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <template v-if="!shippingToBilling && userInformation && billingAddresses.length > 1">
                                    <h3 class="hl_checkout_subtitle mt_10">Billing Address</h3>
                                    <div class="form-group">
                                        <select class="form-control" v-model="billingInput.id" @change="billingChange()">
                                            <option value="">Select Billing Address</option>
                                            <option v-for="(billingAddress, billingAddressIndex) in billingAddresses" :key="'customerBilling_' + billingAddressIndex" :value="billingAddress.id">
                                                {{billingAddress.first_name ? billingAddress.first_name : '' + ' ' + billingAddress.first_name ? billingAddress.first_name : '' + ', '}}
                                                {{billingAddress.address }}
                                                {{billingAddress.state ? billingAddress.state.name : ''}}
                                                {{' ' + billingAddress.zip + ', '}}
                                                {{billingAddress.country ? billingAddress.country.code : ''}}
                                            </option>
                                            <option value="-1">New Address</option>
                                        </select>
                                        <small style="color:red" v-for="(formError, errorIndex) in formErrors['billingInput.id']" :key="'error_name_'+errorIndex">{{formError.replace("billing input.", "")}}</small>
                                    </div>
                                </template>

                                <div v-show="newBillingForm == true">
                                    <div class="form-row">
                                        <div class="col-md-4">
                                            <div class="custom_radio">
                                                <input type="radio" id="billinglocationUS" name="billinglocation" value="US" @change="changeBillingLocation('US')" v-model="billingInput.location">
                                                <label for="billinglocationUS">United States</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="custom_radio">
                                                <input type="radio" id="billinglocationCA" name="billinglocation" value="CA" @change="changeBillingLocation('CA')"  v-model="billingInput.location">
                                                <label for="billinglocationCA">Canada</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="custom_radio">
                                                <input type="radio" id="billinglocationInt" name="billinglocation" value="INT" @change="changeBillingLocation('INT')"  v-model="billingInput.location">
                                                <label for="billinglocationInt">International</label>
                                            </div>
                                        </div>

                                        <small style="color:red" v-for="(formError, errorIndex) in formErrors['billingInput.location']" :key="'error_name_'+errorIndex">{{formError.replace("billing input.", "")}}</small>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-6">
                                            <input type="text" placeholder="First Name" class="form-control" v-model="billingInput.first_name">
                                            <small style="color:red" v-for="(formError, errorIndex) in formErrors['billingInput.first_name']" :key="'error_name_'+errorIndex">{{formError.replace("shipping input.", "")}}</small>
                                        </div>
                                        <div class="form-group col-6">
                                            <input type="text" placeholder="Last Name" class="form-control" v-model="billingInput.last_name">
                                            <small style="color:red" v-for="(formError, errorIndex) in formErrors['billingInput.last_name']" :key="'error_name_'+errorIndex">{{formError.replace("shipping input.", "")}}</small>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Street Address" v-model="billingInput.address">
                                        <small style="color:red" v-for="(formError, errorIndex) in formErrors['billingInput.address']" :key="'error_name_'+errorIndex">{{formError.replace("billing input.", "")}}</small>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-6">
                                            <input type="text" class="form-control" placeholder="City" v-model="billingInput.city">
                                            <small style="color:red" v-for="(formError, errorIndex) in formErrors['billingInput.city']" :key="'error_name_'+errorIndex">{{formError.replace("billing input.", "")}}</small>
                                        </div>
                                        <div class="form-group col-6" v-if="billingStates.length">
                                            <select class="form-control" v-model="billingInput.state_id">
                                                <option value="" selected>Select State</option>
                                                <option v-for="(state, stateIndex) in billingStates" :key="'shipState_' + stateIndex" :value="state.id"> {{state.name}} </option>
                                            </select>
                                            <small style="color:red" v-for="(formError, errorIndex) in formErrors['billingInput.state_id']" :key="'error_name_'+errorIndex">{{formError.replace("billing input.", "").replace("state id.", "State")}}</small>
                                        </div>
                                        <div class="form-group col-6" v-else>
                                            <input type="text" class="form-control" placeholder="State Name" v-model="billingInput.state_text">
                                            <small style="color:red" v-for="(formError, errorIndex) in formErrors['billingInput.state_text']" :key="'error_name_'+errorIndex">{{formError.replace("billing input.", "")}}</small>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-6">
                                            <input type="number" class="form-control" placeholder="Zip" v-model="billingInput.zip">
                                            <small style="color:red" v-for="(formError, errorIndex) in formErrors['billingInput.zip']" :key="'error_name_'+errorIndex">{{formError.replace("billing input.", "")}}</small>
                                        </div>
                                        <div class="form-group col-6">
                                            <select class="form-control" v-model="billingInput.country_id" :disabled="billingInput.country_id === 1 || billingInput.country_id === 2">
                                                <option value="">Select Country</option>
                                                <option v-for="(country, countryIndex) in billingCountries" :key="'shipCountry_' + countryIndex" :value="country.id">{{country.name}} </option>
                                            </select>
                                            <small style="color:red" v-for="(formError, errorIndex) in formErrors['billingInput.country_id']" :key="'error_name_'+errorIndex">{{formError.replace("billing input.", "").replace("country id.", "Country")}}</small>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-12">
                                            <input type="number" class="form-control" placeholder="Telephone" v-model="billingInput.phone">
                                            <small style="color:red" v-for="(formError, errorIndex) in formErrors['billingInput.phone']" :key="'error_name_'+errorIndex">{{formError.replace("billing input.", "")}}</small>
                                        </div>
                                    </div>

                                </div>
                                <h3 class="hl_checkout_subtitle mt_10">Pay By</h3>
                                <div class="inner billing_img">
                                    <div class="custom_radio">
                                        <input type="radio" id="Checkme4"  checked="checked" v-model="paymentMethod" value="2">
                                        <label for="Checkme4">
                                            Credit Card
                                            <div class="img">
                                                <img src="images/pay1.png" alt="pay1" class="img-fluid">
                                                <img src="images/pay2.png" alt="pay2" class="img-fluid">
                                                <img src="images/pay3.png" alt="pay3" class="img-fluid">
                                                <img src="images/pay4.png" alt="pay4" class="img-fluid">
                                            </div>
                                        </label>
                                    </div>
                                    <div class="text-danger">{{cardError}}</div>
                                    <div id="">
                                        <div class="credit_card_info c_t_info" id="Credit" style="display:block">
                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="Full Name" v-model="cardInput.card_full_name">
                                                <small style="color:red" v-for="(formError, errorIndex) in formErrors['cardInput.card_full_name']" :key="'error_name_'+errorIndex">{{formError.replace("card input.", "")}}</small>
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="Card number" v-model="cardInput.card_number">
                                                <small style="color:red" v-for="(formError, errorIndex) in formErrors['cardInput.card_number']" :key="'error_name_'+errorIndex">{{formError.replace("card input.", "")}}</small>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-6">
                                                    <input type="text" class="form-control" placeholder="MM/YY" v-model="cardInput.card_expire" @keyup="cardExpire">
                                                    <small style="color:red" v-for="(formError, errorIndex) in formErrors['cardInput.card_expire']" :key="'error_name_'+errorIndex">{{formError.replace("card input.", "")}}</small>
                                                </div>
                                                <div class="form-group col-6">
                                                    <input type="text" class="form-control" placeholder="CVC" v-model="cardInput.card_cvc" @keyup="cardCvc">
                                                    <small style="color:red" v-for="(formError, errorIndex) in formErrors['cardInput.card_cvc']" :key="'error_name_'+errorIndex">{{formError.replace("card input.", "")}}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="inner">
                                    <div class="float_right full_width mt_10 step_3_btn paypal-button-container" :disabled="checkoutLoader" v-if="cartItems && cartItems.length > 0"></div>
                                </div>
                                <div class="c_custom_section mt_20" v-if="!emailCheck">
                                    <div class="inner border-bottom-0">
                                        <p class="mb_0 pb_0">you are new here please help us to provide password to create your profile.</p>
                                    </div>
                                    <div>
                                        <div class="form-row">
                                            <div class="form-group col-6">
                                                <input type="password" placeholder="Password" class="form-control" v-model="userInput.password">
                                            </div>
                                            <div class="form-group col-6">
                                                <input type="password" placeholder="Confirm Password" class="form-control" v-model="userInput.password_confirmation">
                                            </div>
                                        </div>
                                        <small style="color:red" v-for="(formError, errorIndex) in formErrors['userInput.password']" :key="'error_name_'+errorIndex">{{formError.replace("user input.", "")}}</small>
                                    </div>
                                </div>

                                <div class="d_flex_end c_shipping m_none">
                                    <router-link :to="{ name: 'cart'}">Return to cart</router-link>
                                    <button class="btn_common width_200p step_3_btn checkout_btn" @click="checkout($event,2, null)"> Continue to Checkout <span :class="{showCheckout:processCehckout}"><i class="fas fa-spinner fa-pulse"></i></span> </button>
                                </div>
                                <div class="d_flex_end c_shipping p_0 m_none" v-if="customer">
                                    <!--                                    <div class="float_right width_200p step_3_btn paypal-button-container" :disabled="checkoutLoader" v-if="cartItems && cartItems.length > 0"></div>-->
                                </div>
                            </div>
                            <!-- =================== Third step Exit ================== -->
                        </div>
                    </div>
                    <div class="col-md-5 col-lg-4">
                        <div class="hl_checkout_right above_mobile">
                            <h2 class="hl_checkout_title">Order Summery</h2>
                            <div class="hl_order_show" :class="{scroll: cartItems.length > 3}">
                                <table>
                                    <template v-if="cartItems && cartItems.length > 0">
                                        <tr v-for="(cartItem, cartItemKey) in cartItems" :key="'cartItem_' + cartItemKey">
                                            <td>
                                                <div class="inner">
                                                    <img :src="cartItem.item_images && cartItem.item_images.length > 0 ? (cartItem.item_images[0].thumbs_img) : ('/' + defaultImage.value)"
                                                         :alt="'Hologram Product ' + cartItem.style_no"
                                                         class="img-fluid"  @error="imgErrHndlHasan($event, cartItem.item_images[0].thumbs_img)">
                                                    <div class="text">
                                                        <h2>{{cartItem.item && cartItem.item.name ? cartItem.item.name : 'No Specific Name'}}</h2>
                                                        <p>${{cartItem.item ? cartItem.item.price : '0'}}</p>
                                                        <p>Qty : {{cartItem.quantity}}</p>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </table>
                            </div>
                            <div class="d-block full_width" v-if="promoCode && promoCode.coupon_code">
                                <span> (Applied promo is: {{promoCode.coupon_code}}. Discount is: {{promoCode.discount}}) </span>
                            </div>
                            <div class="checkout_promo">
                                <input type="text" placeholder="Enter Promo Code" class="form-control" v-model="newCoupon">
                                <button class="btn_common" @click.prevent="applyCoupon" :disabled="couponLoader" ref="couponButton">apply now</button>
                            </div>
                            <div class="d-block full_width" v-if="promoCode && promoCode.coupon_code">
                                <a @click.prevent="removeCoupon" href="javascript:void(0)">Remove Coupon</a>
                            </div>
                            <div class="err_msg" v-for="(formError, errorIndex) in defaultFormErrors['coupon']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></div>
                            <div class="hl_total_show" v-if="cartItems && cartItems.length > 0">
                                <table>
                                    <tr>
                                        <td>Items({{ cartItems.length }})</td>
                                        <td>${{computedSubTotal | round(2)}}</td>
                                    </tr>
                                    <tr v-if="computedDiscount && computedDiscount > 0">
                                        <td>Discount
                                            <small v-if="promoCode && promoCode.coupon_code"> (Promo : {{promoCode.coupon_code}}. Discount :)</small>
                                        </td>
                                        <td class="text-danger">-${{computedDiscount | round(2)}}</td>
                                    </tr>
                                    <tr v-if="pointDiscount && pointDiscount > 0">
                                        <td>Point Discount </td>
                                        <td>${{pointDiscount | round(2)}}</td>
                                    </tr>
                                    <tr>
                                        <td>Shipping
                                            <small v-if="promoCode && promoCode.coupon_code && promoCode.free_shipping == 1"> (Promo : {{promoCode.coupon_code}}. Free Shipping)</small>
                                        </td>
                                        <td>${{computedShipping | round(2)}}</td>
                                    </tr>
                                    <tr>
                                        <td><b>TOTAL</b></td>
                                        <td><b>${{computedTotal | round(2)}}</b></td>
                                    </tr>
                                    <tr v-if="formErrors['grandTotal']">
                                        <td colspan="2"><small style="color:red" v-for="(formError, errorIndex) in formErrors['grandTotal']" :key="'grandTotal'+errorIndex">{{formError.replace("card input.", "")}}</small></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="hl_checkout_right below_mobile">
                            <div class="d-block full_width" v-if="promoCode && promoCode.coupon_code">
                                <span> (Applied promo is: {{promoCode.coupon_code}}. Discount is: {{promoCode.discount}}) </span>
                            </div>
                            <div class="checkout_promo">
                                <input type="text" placeholder="Enter Promo Code" class="form-control" v-model="newCoupon">
                                <button class="btn_common" @click.prevent="applyCoupon" :disabled="couponLoader" ref="couponButton">apply now</button>
                            </div>
                            <div class="d-block full_width" v-if="promoCode && promoCode.coupon_code">
                                <a @click.prevent="removeCoupon" href="javascript:void(0)">Remove Coupon</a>
                            </div>
                            <div class="err_msg" v-for="(formError, errorIndex) in defaultFormErrors['coupon']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></div>
                            <div class="hl_total_show" v-if="cartItems && cartItems.length > 0">
                                <table>
                                    <tr>
                                        <td>Items({{ cartItems.length }})</td>
                                        <td>${{computedSubTotal | round(2)}}</td>
                                    </tr>
                                    <tr v-if="computedDiscount && computedDiscount > 0">
                                        <td>Discount
                                            <small v-if="promoCode && promoCode.coupon_code"> (Promo : {{promoCode.coupon_code}}. Discount :)</small>
                                        </td>
                                        <td class="text-danger">-${{computedDiscount | round(2)}}</td>
                                    </tr>
                                    <tr v-if="pointDiscount && pointDiscount > 0">
                                        <td>Point Discount </td>
                                        <td>${{pointDiscount | round(2)}}</td>
                                    </tr>
                                    <tr>
                                        <td>Shipping
                                            <small v-if="promoCode && promoCode.coupon_code && promoCode.free_shipping == 1"> (Promo : {{promoCode.coupon_code}}. Free Shipping)</small>
                                        </td>
                                        <td>${{computedShipping | round(2)}}</td>
                                    </tr>
                                    <tr>
                                        <td><b>TOTAL</b></td>
                                        <td><b>${{computedTotal | round(2)}}</b></td>
                                    </tr>
                                    <tr v-if="formErrors['grandTotal']">
                                        <td colspan="2"><small style="color:red" v-for="(formError, errorIndex) in formErrors['grandTotal']" :key="'grandTotal'+errorIndex">{{formError.replace("card input.", "")}}</small></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="d_flex_end cs_btn_mobile ipad_none pb_20">
                            <button v-if="activeTab === 'information'" @click="selectTab('shipping')" class="btn_common"> Continue Shipping</button>
                            <button v-if="activeTab === 'shipping'" @click="selectTab('payment')" class="btn_common"> Continue to Payment</button>
                            <button v-if="activeTab === 'payment'" @click="checkout($event,2, null)" class="btn_common checkout_btn"> Continue to Checkout <span :class="{showCheckout:processCehckout}"><i class="fas fa-spinner fa-pulse"></i></span></button>
                        </div>
                    </div>
                </div>
                <div class="row" v-if="loaded && !cartItems.length">
                    <div class="col-12">
                        <div class="empty_checkout">
                            <img src="https://img.icons8.com/ios/100/000000/empty-box.png"/>
                            <p>Your cart is empty!</p>

                            <router-link :to="{name:'home'}" class="btn_common width_200p">Back to home</router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hl_checkout_footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="hl_footer_inner">
                            <img src="images/hl-c-left.png" alt="" class="img-fluid">
                            <p>All major card accepted</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="hl_footer_inner right">
                            <img src="images/hl-c-right.png" alt="" class="img-fluid">
                            <p>100% Satisfied guarranted</p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="hl_footer_copyright">
                            <p>© 2021 hologram, LLC. All Rights Reserved. | Call Us On 1000</p>
                            <p>Register office : olympic road 1000 L A</p>
                            <p>Company No 00000</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import validate from 'validate.js'
import mixin from '../helpers/mixins'
import ecommerceStore from './ecommerceStore'
import defaultStore from '../layouts/defaultStore'
import customerStore from '../customer/customerStore'
import shippingAddress from "./components/ShippingAddress";
export default {
    name:'checkout',
    mixins: [mixin],
    components:{
        shippingAddress
    },
    data() {
        return {
            loaded: false,
            activeTab: '',
            processCehckout:false,
            createAccount: false,
            shippingStates:[],
            shippingCountries:[],
            billingStates:[],
            billingCountries:[],
            paymentForm:null,
            cardError:null,
            cartInputs: [],
            cardInput:{
                card_full_name: '',
                card_number: '',
                card_expire: '',
                card_cvc: '',
            },
            billingInput:{
                id: '',
                location:'US',
                first_name: '',
                last_name: '',
                address: '',
                city: '',
                phone: '',
                zip: '',
                country_id: '1',
                state_id: '',
                state_text: '',
            },
            shippingInput:{
                id: '',
                location: "US",
                first_name: '',
                last_name: '',
                address: '',
                city: '',
                phone: '',
                zip: '',
                country_id: '1',
                state_id: '',
                state_text: '',
            },
            userInput:{
                first_name: '',
                last_name: '',
                email: '',
                password: '',
                password_confirmation: '',
                userInput: 0,
            },
            errorsCheck: {
                email: false,
                s_address: false,
                payment: false,
                address: false,
                location: false,
                city: false,
                phone: false,
                country_id: false,
                zip: false,
                first_name: false,
                last_name: false,
                state: false,
                state_text: false,
            },
            paymentMethod: '2',
            shippingMethod: null,
            checkoutLoader: null,
            couponLoader: null,
            newCoupon: '',
            note: '',
            user_point: 0,
            pointDiscount: 0,
            storecredit: 0,
            storeCreditDiscount: 0,
            grandTotal: 0,
            newShippingForm: false,
            newBillingForm: false,
            shippingToBilling: true,
            editShippingAddress: false,
            emailCheck: null,
        }
    },
    metaInfo(){
        return {
            title: 'Checkout - Hologram '
        }
    },
    async beforeCreate() {
        if (!(this.$store && this.$store.state && this.$store.state['ecommerceStore'])) {
            this.$store.registerModule("ecommerceStore", ecommerceStore);
        }
        if (!(this.$store && this.$store.state && this.$store.state['defaultStore'])) {
            this.$store.registerModule("defaultStore", defaultStore);
        }
        if (!(this.$store && this.$store.state && this.$store.state['customerStore'])) {
            this.$store.registerModule("customerStore", customerStore);
        }

        await this.$loadScript("https://www.paypal.com/sdk/js?client-id=ARYZHGHgdNn11ZW7jeR2ny2IBRxTUGfgxt2CYQIzPULohz0Whk1--gE86byETcVJNDq2iCdDwUPcB8Tk");
        window.fbAsyncInit = function() {
            FB.init({
                appId      : process.env.MIX_FACEBOOK_ID,
                cookie     : true,
                xfbml      : true,
                version    : 'v11.0'
            });

            FB.AppEvents.logPageView();
        };

        (function(d, s, id){
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {return;}
            js = d.createElement(s); js.id = id;
            js.src = "https://connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    },
    filters: {
        doubleSlashFilter: function (value) {
            return value.replace("//", "/");
        },
        round: function (value, decimals) {
            if(!value) {
                value = 0;
            }

            if(!decimals) {
                decimals = 0;
            }
            var value = Number(value);
            value = value.toFixed(decimals);

            return value;
        },
        checkshippingerrro(e = null){

        }
    },
    mounted(){
        this.pagePreloads();
        this.defaultFormErrors = {}
        let tempThis = this;
        gapi.load('auth2', function(){
            // Retrieve the singleton for the GoogleAuth library and set up the client.
            tempThis.googleAuth = gapi.auth2.init({
                client_id: process.env.MIX_GOOGLE_CLIENT_KEY,
                cookiepolicy: 'single_host_origin',
            });
            tempThis.attachGoogleSignIn(document.getElementById('btn_google_mbl'));
        });
    },
    watch: {
        successMessage(successMessage) {
            if (successMessage) {
                this.$swal({
                    icon: 'success',
                    title: successMessage
                })
                this.$store.commit('ecommerceStore/setSuccessMessage', '');
            }
        },
        errorMessage(errorMessage) {
            if (errorMessage) {
                this.$swal({
                    icon: 'error',
                    title: errorMessage
                })
                this.$store.commit('ecommerceStore/setErrorMessage', '');
            }
        },
        ecommerceLoading(loading) {
            if(this.checkoutLoader && loading === false) {
                this.checkoutLoader.hide();
                this.checkoutLoader = null;
            }
        },
        defaultLoading(loading) {
            if(this.couponLoader && loading === false) {
                this.couponLoader.hide();
                this.couponLoader = null;
            }
        },
        cartItems(cartItems) {
            this.cartInputs = cartItems;
        },
        defaultBilling(defaultBilling) {
            this.billingInput = defaultBilling
        },
        defaultShipping(defaultShipping) {
            this.shippingInput = defaultShipping;
        },
        shippingMethods(shippingMethods) {
            if(this.isLoggedIn)
                this.shippingMethod = shippingMethods[0];
        },
        userInformation(userInformation) {
            this.userInput = userInformation;
        },
        computedTotal(total) {
            this.renderPaypal();
        },
    },
    computed:{
        customer() {
            return this.$store.getters['customerStore/getCustomer'];
        },
        ecommerceLoading() {
            return this.$store.getters['ecommerceStore/getLoading'];
        },
        successMessage() {
            return this.$store.getters['ecommerceStore/getSuccessMessage'];
        },
        errorMessage() {
            return this.$store.getters['ecommerceStore/getErrorMessage'];
        },
        formErrors: {
            get: function () {
                return this.$store.getters['ecommerceStore/getFormErrors']
            },
            set: function (errorClear = {}) {
                this.$store.commit('ecommerceStore/setFormErrors', errorClear);
            }
        },
        billingAddresses() {
            return this.$store.getters['ecommerceStore/getAddresses']
        },
        shippingAddresses() {
            return this.$store.getters['ecommerceStore/getAddresses']
        },
        defaultLoading() {
            return this.$store.getters['defaultStore/getLoading'];
        },
        countries() {
            return this.$store.getters['defaultStore/getCountries']
        },
        states() {
            return this.$store.getters['defaultStore/getStates']
        },
        shippingMethods() {
            return this.$store.getters['defaultStore/getShippingMethods']
        },
        cartItems() {
            return this.$store.getters['defaultStore/getCartItems']
        },
        defaultImage:{
            get: function () {
                return this.$store.getters['defaultStore/getDefaultImage']
            },
            set: function (defaultImage = null) {
                this.$store.commit('defaultStore/setDefaultImage', defaultImage);
            }
        },
        defaultFormErrors: {
            get: function () {
                return this.$store.getters['defaultStore/getFormErrors']
            },
            set: function (errorClear = {}) {
                this.$store.commit('defaultStore/setFormErrors', errorClear);
            }
        },
        isLoggedIn() {
            return this.$store.getters['customerStore/getIsLoggedIn'];
        },
        defaultBilling() {
            return this.$store.getters['customerStore/getDefaultBilling']
        },
        defaultShipping() {
            return this.$store.getters['customerStore/getDefaultShipping']
        },
        userInformation() {
            return this.$store.getters['customerStore/getUserInformation']
        },
        computedSubTotal () {
            let total = 0;
            this.cartItems.forEach(function (cartItem) {
                if(cartItem.item) {
                    total += Number(cartItem.item.price * cartItem.quantity);
                }
            })
            return total;
        },
        computedShipping () {
            if(this.promoCode && this.promoCode.free_shipping == 1) {
                return 0.00;
            }
            if(this.shippingMethod && this.shippingMethod.fee) {
                return this.shippingMethod.fee;
            }
            return 0.00;
        },
        computedDiscount () {
            if(this.promoCode && this.promoCode.discount) {
                return this.promoCode.discount;
            }
            return 0.00;
        },
        computedTotal () {
            let total =  Number(this.computedSubTotal) + Number(this.computedShipping) - Number(this.computedDiscount) - Number(this.pointDiscount) - Number(this.storeCreditDiscount);
            if(total <= 0){
                return
            }
            return Number(this.computedSubTotal) + Number(this.computedShipping) - Number(this.computedDiscount) - Number(this.pointDiscount) - Number(this.storeCreditDiscount);
        },
        promoCode () {
            return this.$store.getters['defaultStore/getPromoCode']
        },
        pointExists () {
            return this.$store.getters['defaultStore/getPointExists']
        },
        dollarDiscount () {
            return this.$store.getters['defaultStore/getDollarDiscount']
        },
        computedBillingStates() {
            let country_id = this.billingInput.country_id;
            let states = this.states;
            let computedStates = [];
            if (country_id) {
                computedStates = states.filter(state => state.country_id == country_id);
            } else {

            }

            return computedStates;
        },
        computedShippingStates() {
            let country_id = this.shippingInput.country_id;
            let states = this.states;

            let computedStates = [];
            if (country_id) {
                computedStates = states.filter(state => state.country_id == country_id);
            } else {

            }
            return computedStates;
        }
    },
    methods:{
        useShippingToBilling(){
            if(this.userInformation){//if User LogedIn
                if (this.shippingToBilling === true) {
                    if(this.billingAddresses && this.billingAddresses.length > 1){
                        this.shippingToBilling = false;
                        this.newBillingForm = false;
                    }else if(this.billingAddresses && this.billingAddresses.length === 1){
                        this.billingInput = this.billingAddresses[0]
                        this.shippingToBilling = false;
                        this.newBillingForm = true;
                    }else{
                        this.billingInput = this.shippingInput
                        this.shippingToBilling = false;
                        this.newBillingForm = true;
                    }
                }else{
                    this.billingInput = this.shippingInput
                    this.newBillingForm = false
                    this.shippingToBilling = true;
                }
            }else {//if User Not LogedIn
                if (this.shippingToBilling === true) {
                    this.shippingToBilling = false;
                    this.newBillingForm = true;
                }else{
                    this.shippingToBilling = true;
                    this.newBillingForm = false;
                }
                this.billingInput = this.shippingInput
                if (this.shippingInput.id === '-1')
                    this.billingInput.id = '-1'
            }
        },
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
        },
        changeBillingLocation(location){
            this.billingCountries = this.countries
            if(location != 'INT'){
                let country = this.countries.find(q => q.code == location)
                this.billingStates = this.states.filter(q => q.country_id == country.id)
                this.billingInput.country_id = country.id;
            }else{
                this.billingCountries = this.countries;
                this.billingStates = [];
                this.billingInput.country_id = '';
            }
        },
        selectTab(tab){
            let flash = false;
            if(tab === 'payment'){
                this.billingCountries = this.countries
                this.billingStates = this.states
                if(this.userInformation){ //if User LogedIn
                    if(this.billingAddresses && this.billingAddresses.length > 1){
                        this.shippingToBilling = false;
                        this.newBillingForm = false;
                    }else if(this.billingAddresses && this.billingAddresses.length === 1){
                        this.billingInput = this.billingAddresses[0]
                        this.shippingToBilling = false;
                        this.newBillingForm = true;
                    }else{
                        this.billingInput = this.shippingInput
                        this.shippingToBilling = true;
                        this.newBillingForm = false;
                    }
                }else {//if User Not LogedIn
                    this.billingInput = this.shippingInput
                    if (this.shippingInput.id === '-1')
                        this.billingInput.id = '-1'
                    this.shippingToBilling = true;
                }
                if(this.shippingMethod === null){
                    this.errorsCheck.payment = true;
                    flash= true;
                }else{
                    this.errorsCheck.payment = false;
                }
            }
            if(tab === 'shipping' || tab === 'payment'){


                if(this.userInput.email === ''){
                    this.errorsCheck.email = true;
                    flash= true;
                }else{this.errorsCheck.email = false;}

                if(this.userInput.first_name === ''){
                    this.errorsCheck.first_name = true;
                    flash= true;
                }else{this.errorsCheck.first_name = false;}

                if(this.userInput.last_name === ''){
                    this.errorsCheck.last_name = true;
                    flash= true;
                }else{this.errorsCheck.last_name = false;}

                if(this.shippingInput.id === '' || this.shippingInput.id === '-1')
                {
                    if(this.shippingInput.id === ''){
                        this.errorsCheck.s_address = true;
                        flash= true;
                    }else{this.errorsCheck.s_address = false;}
                    if(this.shippingInput.id === '-1') {
                        if (this.shippingInput.city === '') {
                            this.errorsCheck.city = true;
                            flash = true;
                        } else {
                            this.errorsCheck.city = false;
                        }

                        if (this.shippingInput.address === '') {
                            this.errorsCheck.address = true;
                            flash = true;
                        } else {
                            this.errorsCheck.address = false;
                        }

                        if (this.shippingInput.location === '') {
                            this.errorsCheck.location = true;
                            flash = true;
                        } else {
                            this.errorsCheck.location = false;
                        }

                        if (this.shippingInput.state_id == '' && this.shippingInput.state_text == '') {
                            this.errorsCheck.state = true;
                            flash = true;
                        } else {
                            this.errorsCheck.state = false;
                        }

                        if (this.shippingInput.location === '') {
                            this.errorsCheck.location = true;
                            flash = true;
                        } else {
                            this.errorsCheck.location = false;
                        }

                        if (this.shippingInput.phone === '') {
                            this.errorsCheck.phone = true;
                            flash = true;
                        } else {
                            this.errorsCheck.phone = false;
                        }

                        if (this.shippingInput.country_id === '') {
                            this.errorsCheck.country_id = true;
                            flash = true;
                        } else {
                            this.errorsCheck.country_id = false;
                        }

                        if (this.shippingInput.zip === '') {
                            this.errorsCheck.zip = true;
                            flash = true;
                        } else {
                            this.errorsCheck.zip = false;
                        }

                        if (this.shippingInput.location === 'INT') {
                            if(this.shippingInput.state_text === ''){
                                this.errorsCheck.state_text = true;
                                flash   = true
                            }else{
                                this.errorsCheck.state_text = false;
                            }
                        } else {
                            if(this.shippingInput.state_id === ''){
                                this.errorsCheck.state = true;
                                flash   = true
                            }else{
                                this.errorsCheck.state = false;
                            }
                        }
                    }
                }
                //check user email exists
                axios.get('/api/v1/check-email?email='+this.userInput.email)
                    .then((response) => {
                        this.emailCheck = response.data
                    })

            }
            if(flash){
                window.scrollTo(0,0);
                return false;
            }

            this.activeTab = tab;
        },
        async pagePreloads(){
            this.$store.dispatch('defaultStore/states');
            this.$store.dispatch('defaultStore/countries');
            this.$store.dispatch('defaultStore/shippingMethods');
            this.$store.dispatch('defaultStore/cartItems');
            await this.$store.dispatch('customerStore/checkCustomer')
                .then((response)=>{
                    if(this.isLoggedIn) {
                        this.$store.dispatch('defaultStore/cartItems').finally(() => this.loaded = true);
                        this.$store.dispatch('ecommerceStore/checkoutAddresses');
                        this.$store.dispatch('customerStore/profile');
                        this.activeTab = 'shipping'
                        this.newShippingForm = false;
                        // this.shippingMethod = this.shippingMethods[0];
                    }else{
                        this.activeTab = 'information'
                        this.newShippingForm = true;
                        this.shippingInput.id = '-1';
                    }
                })
                .catch((error) => {
                    // this.$router.push('/login');
                })
        },
        applyPoints(){
            if(this.userInformation){
                var usablePoints = this.userInformation.buyer.points - this.userInformation.buyer.points_spent;
                if(usablePoints< this.user_point ){
                    alert('You can not use more than '+ usablePoints.toFixed(2));
                    this.user_point = 0;
                    this.pointDiscount = 0;
                    return false;
                }
            }
            var calculatedDiscount = this.dollarDiscount.dollar_disounts * this.user_point / this.dollarDiscount.points_use
            this.pointDiscount = calculatedDiscount;
        },
        applyStoreCredit(){
            if(Number(this.storecredit) > Number(this.userInformation.storecredit.amount)){
                alert('You can not use more than '+ this.userInformation.storecredit.amount + ' Credit');
                this.storecredit = 0;
                this.storeCreditDiscount = 0;
                return false;
            }
            this.storeCreditDiscount = this.storecredit;
        },
        checkout(event, paymentMethod, paymentId){
            this.paymentMethod = paymentMethod;
            this.billingCountries = this.countries
            this.billingStates = this.states
            if (!this.validateCheckout ()) {
                if(this.formErrors ){
                    if(this.formErrors['userInput.email'] || this.formErrors['userInput.first_name'] || this.formErrors['userInput.last_name'])
                        this.activeTab = 'information'

                    if(this.formErrors['userInput.password'] || this.formErrors['userInput.password_confirmation'])
                        this.createAccount = true

                    if(this.formErrors['shippingInput.address'] || this.formErrors['shippingInput.country_id'] || this.formErrors['shippingInput.zip']){
                        this.activeTab = 'information'
                        this.newShippingForm = true;
                    }

                    if(this.formErrors['billingInput.address'] || this.formErrors['billingInput.country_id'] || this.formErrors['billingInput.zip'])
                        this.newBillingForm = true
                }
                return;
            }

            let tempuCard = JSON.parse(JSON.stringify(this.cardInput));

            if(this.cardInput.nonce === null){
                this.cardError = 'Invalid Card Information.'
                return false;
            }
            this.checkoutLoader = null
            let formData = {
                cardInput: tempuCard,
                billingInput: this.billingInput,
                shippingInput: this.shippingInput,
                userInput: this.userInput,
                shippingMethod: this.shippingMethod,
                user_point: this.user_point,
                storeCredit: this.storecredit,
                grandTotal: this.computedTotal,
                paymentMethod: paymentMethod,
                billingShippnigSame: this.shippingToBilling,
                paymentId: paymentId
            }
            this.$store.dispatch('ecommerceStore/checkout', formData).then(()=>{
                this.checkoutLoader = null
                this.$Progress.finish()
                this.processCehckout = false;
            });

        },
        validateCheckout() {
            this.formErrors = {}
            let isValid = true;
            let errors = {};
            // validate customer
            if(!this.isLoggedIn) {
                const userConstraints = {
                    first_name: {
                        presence: {
                            allowEmpty: false,
                            message: '^ Field is required.'
                        },
                    },
                    last_name: {
                        presence: {
                            allowEmpty: false,
                            message: '^ Field is required.'
                        },
                    },
                    email: {
                        presence: {
                            allowEmpty: false,
                            message: '^ Field is required.'
                        },
                        email: true
                    },
                };
                const userErrors = validate(this.$data.userInput, userConstraints);
                if (userErrors) {
                    for (const [key, value] of Object.entries(userErrors)) {
                        errors['userInput.' + key] = value;
                    }
                    isValid = false;
                }
            }
            // validate billing address
            const billingConstraints = {
                address: {
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
                zip: {
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
            };
            if (this.billingInput.location === 'US' || this.billingInput.location === 'CA') {
                billingConstraints.state_id = {
                    presence: {
                        allowEmpty: false,
                        message:'^ Field is required.'
                    },
                };
            } else {
                billingConstraints.state_text = {
                    presence: {
                        allowEmpty: false,
                        message:'^ Field is required.'
                    },
                };
            }

            const billingErrors = validate(this.$data.billingInput, billingConstraints);
            if (billingErrors) {
                for (const [key, value] of Object.entries(billingErrors)) {
                    errors['billingInput.' + key] = value;
                }
                isValid = false;
            }
            // validate shipping address
            const shippingConstraints = {
                address: {
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
                zip: {
                    presence: {
                        allowEmpty: false,
                        message:'^ Field is required.'
                    },
                },
            };
            const shippingErrors = validate(this.$data.shippingInput, shippingConstraints);
            if (shippingErrors) {
                for (const [key, value] of Object.entries(shippingErrors)) {
                    errors['shippingInput.' + key] = value;
                }
                isValid = false;
            }
            // validate ship method
            const shippingMethodConstraints = {
                id: {
                    presence: {
                        allowEmpty: false,
                        message:'^ Field is required.'
                    },
                },
            };
            const shippingMethodErrors = validate(this.$data.shippingMethod, shippingMethodConstraints);
            if (shippingMethodErrors) {
                for (const [key, value] of Object.entries(shippingMethodErrors)) {
                    errors['shippingMethod.' + key] = value;
                }
                isValid = false;
            }
            // validate card only if paymentMethod = 2
            if (this.paymentMethod == 2) {
                const cardConstraints = {
                    card_full_name: {
                        presence: {
                            allowEmpty: false,
                            message:'^ Field is required.'
                        },
                    },
                    card_number: {
                        presence: {
                            allowEmpty: false,
                            message:'^ Field is required.'
                        },
                    },
                    card_expire: {
                        presence: {
                            allowEmpty: false,
                            message:'^ Field is required.'
                        },
                    },
                    card_cvc: {
                        presence: {
                            allowEmpty: false,
                            message:'^ Field is required.'
                        },
                    },
                };
                const cardErrors = validate(this.$data.cardInput, cardConstraints);
                if (cardErrors) {
                    for (const [key, value] of Object.entries(cardErrors)) {
                        errors['cardInput.' + key] = value;
                    }
                    isValid = false;
                }
            }
            // validate point
            if (this.user_point < 0) {
                errors['user_point'] = ['point at least 0'];
                isValid = false;
            }
            if (this.userInformation && this.userInformation.buyer && (this.userInformation.buyer.points - this.userInformation.buyer.points_spent) < this.user_point) {
                errors['user_point'] = ['Unsufficient point'];
                isValid = false;
            }
            this.formErrors = errors

            return isValid;
        },
        billingChange() {
            let inputValue = this.billingInput.id;
            let newBilling = {
                id: '-1',
                first_name: '',
                last_name: '',
                address: '',
                location: 'US',
                city: '',
                phone: '',
                zip: '',
                country_id: '1',
                state_id: '',
            }
            if (inputValue != '-1') {
                this.newBillingForm = false; //Hide add new Shipping address form
                newBilling = this.billingAddresses.find(allAddress => allAddress.id == inputValue)
            }else{
                this.billingStates = this.states
                this.billingCountries = this.countries
                this.billingInput.country_id = '1';
                this.billingInput.location = 'US';
                this.billingInput.state_id = '';
                this.newBillingForm = true; //show add new shipping address form
                return false;
            }
            let that = this;
            setTimeout(() => {
                that.$set(that, 'billingInput', newBilling);
                that.$store.dispatch('ecommerceStore/checkoutAddresses');
            }, 100);
        },
        shippingChange() {
            let inputValue = this.shippingInput.id ? this.shippingInput.id : null;
            if(this.shippingInput.id === ''){
                this.errorsCheck.s_address = true;
                return false;
            }else{this.errorsCheck.s_address = false;}
            let newShipping = {
                id: '-1',
                first_name: '',
                last_name: '',
                location: 'US',
                address: '',
                city: '',
                phone: '',
                zip: '',
                country_id: '',
                state_id: '',
            }
            if (inputValue != '-1') {
                this.newShippingForm = false; //Hide add new shipping address form
                newShipping = this.shippingAddresses.find(address => address.id == inputValue)
            }else{
                this.shippingStates = this.states
                this.shippingCountries = this.countries
                this.shippingInput.country_id = '1';
                this.shippingInput.state_id = '';
                this.shippingInput.location = 'US';

                this.newShippingForm = true; //show add new shipping address form
                return false;
            }
            let that = this;
            setTimeout(() => {
                that.$set(that, 'shippingInput', newShipping);
                that.$store.dispatch('ecommerceStore/checkoutAddresses');
            }, 200);
        },
        cardExpire(event) {
            event = (event) ? event : window.event;
            let inputValue = event.target.value;
            let outputValue = inputValue.replace(/\D/g, "");
            outputValue = outputValue.substring(0,2) + '/' + outputValue.substring(2,4)

            this.cardInput.card_expire = outputValue
        },
        cardCvc(event) {
            event = (event) ? event : window.event;
            let inputValue = event.target.value;
            let outputValue = inputValue.replace(/\D/g, "");
            outputValue = outputValue.substring(0,4)
            this.$set(this.cardInput, 'card_cvc', outputValue);
        },
        async applyCoupon() {
            this.$Progress.start()
            if(!this.validateCoupon()) return;
            this.couponLoader = this.inlineLoader({ container: this.$refs.couponButton })
            let formData = {
                coupon: this.newCoupon
            }
            await this.$store.dispatch('defaultStore/applyCoupon',  formData)
                .then((response) => {
                    $('li[data-target="#promo_code"]').click().trigger('change');
                })
                .catch((error) => {
                    // $('li[data-target="#promo_code"]').click().trigger('change');
                })

        },
        validateCoupon() {
            let isValid = true;

            const constraints = {
                newCoupon: {
                    presence: {
                        allowEmpty: false,
                        message:'^ Field is required.'
                    },
                },
            }

            const newCouponErrors = validate(this.$data, constraints);
            if (newCouponErrors) {
                this.$Progress.fail()
                this.$swal({
                    icon: 'error',
                    title: 'Please Insert a coupon code.'
                })
                let errors = {
                    coupon: newCouponErrors['newCoupon']
                };
                this.defaultFormErrors = errors
                isValid = false;
            }

            return isValid;
        },
        removeCoupon() {
            this.$Progress.start()
            this.$store.dispatch('defaultStore/removeCoupon')
        },

        renderPaypal() {
            let div = $('.paypal-button-container').html('');
            let user = this.userInput;
            let total = this.computedTotal.toFixed(2);
            let paypalThis = this;
            if(div.length) {
                paypal.Buttons({
                    env: 'sandbox',
                    style: {
                        color: 'black',
                        shape: 'rect',
                        label: 'pay',
                        height: 40
                    },
                    createOrder: function (data, actions) {
                        // This function sets up the details of the transaction, including the amount and line item details.

                        return actions.order.create({
                            purchase_units: [{
                                amount: {
                                    currency_code: 'USD',
                                    value: total,
                                },
                            }]
                        });
                    },
                    onApprove: function (data, actions) {
                        // This function captures the funds from the transaction.
                        return actions.order.capture().then(function (details) {
                            paypalThis.checkout(3, details.id);
                            // This function shows a transaction success message to your buyer.
                            //alert('Transaction completed by ' + details.payer.name.given_name);
                        });
                    },
                    onClick: function (data, actions) {
                        paypalThis.$Progress.start()
                        paypalThis.paymentMethod = 3;
                        if (!paypalThis.validateCheckout()) {
                            paypalThis.$Progress.fail()
                            return actions.reject();
                        }
                        return actions.resolve();
                    }
                }).render('.paypal-button-container');
            }
        },
        logInWithFacebook() {
            let formData = {
                provider: 'facebook',
                providerId: '566121214536217',
                firstName: 'Mark',
                lastName: 'Nam',
                email: 'mkn102@gmail.com',
            }
            let that = this;
            window.FB.getLoginStatus(function(response) {
                if (response.authResponse) {
                    that.fetchFacebookUser();
                } else {
                    window.FB.login(function(response) {
                        if (response.authResponse) {
                            that.fetchFacebookUser();
                        }
                    }, {scope: 'email'});
                }
            });
        },
        fetchFacebookUser() {
            let that = this;

            window.FB.api('/me?fields=id,name,first_name,last_name,email', function(response) {
                let formData = {
                    provider: 'facebook',
                    providerId: response.id,
                    firstName: response.first_name,
                    lastName: response.last_name,
                    email: response.email,
                }

                that.$store.dispatch('customerStore/trySocialLogin', formData);
            });
        },
        attachGoogleSignIn(element) {
            let googleThis = this;
            this.googleAuth.attachClickHandler(element, {},
                function(googleUser) {
                    let profile = googleThis.googleAuth.currentUser.get().getBasicProfile();

                    let formData = {
                        provider: 'google',
                        providerId: profile.getId(),
                        firstName: profile.getGivenName(),
                        lastName: profile.getFamilyName(),
                        email: profile.getEmail(),
                    }

                    googleThis.$store.dispatch('customerStore/trySocialLogin', formData);
                }, function(error) {
                    //alert(JSON.stringify(error, undefined, 2));
                });
        },
    },
}
</script>

