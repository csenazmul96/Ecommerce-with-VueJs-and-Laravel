<template>
    <!-- ============================
        START PROFILE DASHBOARD SECTION
    ============================== -->
    <div>
        <div class="account_bredcrumbs">
            <div class="a_c_bredcrumbs_left">
                <a href="javascript:void(0)" onclick="window.history.back()">← back </a>
            </div>
            <div class="a_c_bredcrumbs_right">
                <a href="javascript:void(0)" @click.prevent="buyerLogout">Log Out</a>
            </div>
        </div>
        <div class="my_account_dashboard ">
            <div class="m_a_dashboard_title my_acc_container">
                <h2>My Account Dashboard</h2>
                <p>From your My Account Dashboard you have the ability to view a snapshot of your recent account activity and update your account information. <br> Select a link below to view or edit information.</p>
            </div>
        </div>
        <div class="wishlist_area my_acc_container" v-if="wishlistItems && wishlistItems.length > 0">
            <h2 class="my_acc_subtitle">MY WISHLIST <span></span></h2>
            <div class="wishlist_item" style="position:relative; min-height:100px;">
                <div class="preloader_wrap" id="wishlistPreload">
                    <div class="loader-container">
                        <div class="loader-circle"></div>
                    </div>
                </div>
                <template >
                    <productComponent v-for="(product, productKey) in wishlistItems" :key="'product_' + productKey"  :product="product" :defaultImage="defaultImage"></productComponent>
                </template>
            </div>

            <router-link :to="{ name: 'customer-wishlist'}" class="btn btn_grey">
                View All Wishlist Items
            </router-link>
        </div>
        <div class="my_acc_container">
            <div class="account_info">
                <div class="account_info_left">
                    <h2 class="my_acc_subtitle">ACCOUNT INFORMATION</h2>
                    <div style="position:relative; min-height:100px;" id="userPreload">
                        <div class="preloader_wrap">
                            <div class="loader-container">
                                <div class="loader-circle"></div>
                            </div>
                        </div>
                    </div>
                    <ul v-if="userInformation">
                        <li>
                            <span>First Name</span>
                            <span>{{userInformation.first_name}}</span>
                        </li>
                        <li>
                            <span>Last Name</span>
                            <span>{{userInformation.last_name}}</span>
                        </li>
                        <li>
                            <span>Email</span>
                            <span>{{userInformation.email}}</span>
                        </li>
                        <li>
                            <span>Password</span>
                            <span>
                                <router-link :to="{ name: 'customer-change-password'}" class="td_underline">
                                    Change Password
                                </router-link>
                            </span>
                        </li>
                    </ul>
                    <router-link :to="{ name: 'customer-profile'}" class="btn btn_grey">
                        Update Account Details
                    </router-link>
                </div>
                <div class="account_info_right">
                    <h2 class="my_acc_subtitle">MY ADDRESSES</h2>
                    <ul class="acc_address">
                        <li style="position:relative; min-height:100px;">
                            <div class="preloader_wrap" id="defaultBillingPreload">
                                <div class="loader-container">
                                    <div class="loader-circle"></div>
                                </div>
                            </div>
                            <span>DEFAULT BILLING ADDRESS</span>
                            <address v-if="!defaultBilling">NOT SET</address>
                            <address v-else>
                                <template v-if="defaultBilling.store_no">{{defaultBilling.store_no}} <br></template>
                                <template v-if="defaultBilling.first_name || defaultBilling.last_name">{{defaultBilling.first_name +' '+ defaultBilling.last_name}} <br></template>
                                <template v-if="defaultBilling.address">{{defaultBilling.address }} <br></template>
                                <template v-if="defaultBilling.address2">{{defaultBilling.address2}} <br></template>
                                <template v-if="defaultBilling.state || defaultBilling.zip">{{defaultBilling.state ? defaultBilling.state.name : ''}} - {{defaultBilling.zip}} <br></template>
                                <template v-if="defaultBilling.country">{{defaultBilling.country ? defaultBilling.country.name : ''}} <br></template>
                                <template v-if="defaultBilling.phone">Phone: {{defaultBilling.phone}} , </template>
                                <template v-if="defaultBilling.fax">FAX: {{defaultBilling.fax}}</template>
                            </address>
                        </li>
                        <li style="position:relative">
                            <div class="preloader_wrap" id="defaultShippingPreload">
                                <div class="loader-container">
                                    <div class="loader-circle"></div>
                                </div>
                            </div>
                            <span>DEFAULT SHIPPING ADDRESS</span>
                            <address v-if="!defaultShipping">NOT SET</address>
                            <address v-else>
                                <template v-if="defaultShipping.store_no">{{defaultShipping.store_no}} <br></template>
                                <template v-if="defaultShipping.first_name || defaultShipping.last_name">{{defaultShipping.first_name + ' '+ defaultShipping.last_name}} <br></template>
                                <template v-if="defaultShipping.address">{{defaultShipping.address }} <br></template>
                                <template v-if="defaultShipping.address2">{{defaultShipping.address2}} <br></template>
                                <template v-if="defaultShipping.state || defaultShipping.zip">{{defaultShipping.state ? defaultShipping.state.name : ''}} - {{defaultShipping.zip}} <br></template>
                                <template v-if="defaultShipping.country">{{defaultShipping.country ? defaultShipping.country.name : ''}} <br></template>
                                <template v-if="defaultShipping.phone">Phone: {{defaultShipping.phone}} , </template>
                                <template v-if="defaultShipping.fax">FAX: {{defaultShipping.fax}}</template>
                            </address>
                        </li>
                    </ul>
                    <router-link :to="{ name: 'customer-addresses'}" class="btn btn_grey">
                        Manage Addresses
                    </router-link>
                </div>
            </div>
        </div>
<!--        <div class="my_account_mobile_menu">-->
<!--            <ul>-->
<!--                <li><a href="#">My Wishlist </a></li>-->
<!--                <li><a href="#">Account Information</a></li>-->
<!--                <li><a href="#">My Addresses</a></li>-->
<!--                <li><a href="#">My Saved Credit Cards</a></li>-->
<!--                <li><a href="#">Subscription</a></li>-->
<!--            </ul>-->
<!--        </div>-->

        <!-- <div class="my_acc_container">
            <h2 class="my_acc_subtitle">MY SAVED CREDIT CARDS</h2>
            <p>You do not have any saved credit cards.</p>
        </div> -->
        <div class="my_acc_container">
            <h2 class="my_acc_subtitle">SUBSCRIPTION</h2>
            <div class="custom_checkbox">
                <input type="checkbox" id="buyerNewsletter" :checked="userInformation && userInformation.buyer.mailing_list === '1'" @change="newsletterUpdate(userInformation.buyer.mailing_list)">
                <label for="buyerNewsletter">Subscribed to the hologram subscription.</label>
            </div>
        </div>
        <div class="my_acc_container">
            <div class="card my_acc_order">
                <div class="card_header">
                    <h2>Order History</h2>
                </div>
                <div class="card-body" style="position:relative; min-height:100px;">
                    <div class="preloader_wrap" id="orderListPreload">
                        <div class="loader-container">
                            <div class="loader-circle"></div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <th>Date</th>
                                    <th>Order #</th>
                                    <th>Order Tracking No.</th>
                                    <th>Amount</th>
                                    <th class="text-right">Status</th>
                                </tr>
                                <template v-if="orders && orders.data && orders.data.length > 0">
                                    <tr v-for="(order, orderIndex) in orders.data" :key="'order_index_' + orderIndex">
                                        <td>{{order.created_at | dateFormate("MMMM DD, YYYY hh:mm:ss A")}}</td>
                                        <td>
                                            <router-link :to="{ name: 'order-detail', params: { order_number: order.order_number }}">{{order.order_number}}
                                            </router-link>
                                        </td>
                                        <td>
                                            {{order.tracking_number}}
                                        </td>
                                        <td>$ {{order.total | round(2)}}</td>
                                        <td class="text-right">
                                            <span class="label label-info">{{orderEnumeration[order.status]}}</span>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <orderPagination v-if="orders && orders.data && orders.data.length > 0" :paginateData="orders" @paginate="ordersPreload" ></orderPagination>
        </div>

        <div class=" my_acc_container" v-if="messages && messages.allmessage && messages.allmessage.data.length > 0">
            <div class="card my_acc_order">
                <div class="card_header">
                    <h2>Admin Messages ({{messages ? messages.unread_messages : 0}}) </h2>
                </div>
                <div class="card-body" style="position:relative; min-height:100px;">
<!--                    <div class="preloader_wrap" id="messageListPreload">-->
<!--                        <div class="loader-container">-->
<!--                            <div class="loader-circle"></div>-->
<!--                        </div>-->
<!--                    </div>-->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <th>Date</th>
                                    <th>Subject #</th>
                                    <th>Send By</th>
                                    <th>Message</th>
                                </tr>
                                <template v-if="messages && messages.allmessage && messages.allmessage.data.length > 0">
                                    <tr @click.prevent="readMessage(message)" :style="message.reading_status == '1' ? style : null" v-for="(message, messageIndex) in messages.allmessage.data" :key="'message_index_' + messageIndex">
                                        <td>{{message.created_at | dateFormate("MMMM DD, YYYY hh:mm:ss A")}}</td>
                                        <td><small>{{message.subject}}</small></td>
                                        <td><small>{{message.sender}} </small></td>
                                        <td>{{ message.message.substring(0,100)+".." }} </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <orderPagination v-if="messages" :paginateData="messages.allmessage" @paginate="massegesPreload" ></orderPagination>
        </div>
        <div class="my_acc_container my_acc_back">
            <router-link :to="{ name: 'home'}" class="btn btn_transparent width_240p">
                <i class="fas fa-shopping-cart"></i> <span class="ml_5">Continue Shopping</span>
            </router-link>
            <button class="btn_transparent width_240p" @click.prevent="buyerLogout">Log Out</button>
        </div>



        <div class="logout_mobile">
            <button class="btn_transparent">logout</button>
        </div>
        <div class="wcmodel modal" :class="showMessageModal?' open_modal':''" data-modal="wcm">
            <div class="modal_overlay" data-modal-close="wcm"></div>
                <div class="welcome_modal_wrapper">
                    <div class="modal_inner modal_600p">
                        <span class="close_modal" data-modal-close="wcm" v-on:click="closeMessageModal"></span>
                        <div class="col-md-12">
                            <br>
                            <p><strong>Dear: </strong> {{messageReplay.recipient}}</p>
                            <span><strong>Message:</strong></span>
                            <p>{{messageReplay.message}}</p>
                            <span>Attachment</span> <br>
                            <div class="attatch_file">
                                <ul style="display:flex">
                                    <li class="mr_15" v-if="messageReplay.attachment1"><img :src="messageReplay.attachment1" width="100"></li>
                                    <li class="mr_15" v-if="messageReplay.attachment2"><img :src="messageReplay.attachment2" width="100"></li>
                                    <li class="mr_15" v-if="messageReplay.attachment3"><img :src="messageReplay.attachment3" width="100"></li>
                                </ul>
                            </div>
                            <br>
                            <span class="btn_grey width_200p text-center float-right mb_15" @click="replayMessage(messageReplay)">Replay</span>

                        </div>
                    </div>
                </div>
        </div>

        <div class="wcmodel modal " :class="showMessageReplayModal?' open_modal':''" data-modal="wcm">
            <div class="modal_overlay" data-modal-close="wcm"></div>
                <div class="welcome_modal_wrapper">
                    <div class="modal_inner modal_600p">
                        <span class="close_modal" data-modal-close="wcm" v-on:click="closeMessageReplayModal"></span>
                        <div class="col-md-12">
                            <br>
                            <div class="form_inline_border form_inline">
                            <label class="required" for="sender">From</label>
                            <input type="text" class="form-control" placeholder="" v-model="msgReplaySend.recipient" id="sender">
                            <small v-for="(formError, errorIndex) in formErrors['sender']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></small>
                            </div>
                            <div class="form_inline_border form_inline">
                                <label class="required" for="recipient">To</label>
                                <input type="text" class="form-control" placeholder="" v-model="msgReplaySend.sender" id="recipient">
                            </div>
                            <div class="form_inline_border form_inline">
                                <label class="required" for="subject">Subject</label>
                                <input type="text" class="form-control" placeholder="" v-model="msgReplaySend.subject" id="subject">
                                <small v-for="(formError, errorIndex) in formErrors['subject']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></small>
                            </div>
                            <div class="form_inline_border  ">
                                <label class="required" for="subject">Message</label>
                                <textarea class="form-control" cols="30" rows="2" v-model="msgReplaySend.message" ></textarea>
                                <small v-for="(formError, errorIndex) in formErrors['message']" :key="'error_name_'+errorIndex"><span v-if="errorIndex == 0">{{formError}}</span></small>
                            </div>
                            <div class="custom-file mb_10">

                                <input type="file" class="custom-file-input" id="validatedCustomFile"  @change="onImageChange" multiple>
                                <label class="custom-file-label" for="validatedCustomFile">Choose images...</label>
                            </div>
                            <div class="" v-if="msgReplaySend.attachment.length > 0" >
                                <ul style="display:flex">
                                    <li class="mr_15" v-for="(img, index) in msgReplaySend.attachment" :key="'attachement_'+index">
                                        <img :src="img" alt="" width="100"   >
                                    </li>
                                </ul>
                            </div>
                            <span>(Select maximum 3 images.)</span>
                            <button class="btn_grey width_200p float-right mb_15" @click.prevent="sendMessage()"><span> Send </span></button>
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
    import * as dayjs from 'dayjs';
    import * as image from '../helpers/imageHelper'
    import defaultStore from '../layouts/defaultStore';
    import customerStore from './customerStore';
    import orderPagination from './components/Pagination'
    import productComponent from '../ecommerce/components/Product'
    export default {
        name:'customerDashboard',
        data() {
            return {
                orderEnumeration: {
                    1: 'init',
                    2: 'new',
                    3: 'confirm',
                    4: 'partially shipped',
                    5: 'fully shipped',
                    6: 'back',
                    7: 'cancel by buyer',
                    8: 'cancel by vendor',
                    9: 'cancel by agrement',
                    10: 'returned',
                    11: 'declined',
                },
                messageReplay:{
                    id:null,
                    user_id:null,
                    message:null,
                    subject:null,
                    recipient:null,
                    sender:null,
                    attachment:[],
                },
                msgReplaySend: {
                    id:null,
                    user_id:null,
                    message:null,
                    subject:null,
                    recipient:null,
                    sender:null,
                    attachment:[],
                },
                style: {
                    background: 'rgb(228 218 196)',
                },
                showMessageModal: false,
                showMessageReplayModal: false,
            }
        },
        components: {
            productComponent: productComponent,
            orderPagination: orderPagination,
        },
        beforeCreate() {
            if (!(this.$store && this.$store.state && this.$store.state['defaultStore'])) {
                this.$store.registerModule("defaultStore", defaultStore);
            }
            if (!(this.$store && this.$store.state && this.$store.state['customerStore'])) {
                this.$store.registerModule("customerStore", customerStore);
            }
        },
        filters: {
            dateFormate: function (date, format) {
                return dayjs(date).format(format);
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
        },
        mounted(){
            this.pagePreloads();
            this.formErrors = {};
        },
        watch: {
            // wishlistItems(wishlistItems) {
            //     if(wishlistItems && wishlistItems.length > 0) $("#wishlistPreload").fadeOut("slow");
            // },
        },
        computed: {
            wishlistItems() {
                return this.$store.getters['defaultStore/getWishlistItems']
            },
            newsletter() {
                return this.$store.getters['customerStore/getNewsletter']
            },
            defaultImage() {
                return this.$store.getters['defaultStore/getDefaultImage']
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
            orders() {
                return this.$store.getters['customerStore/getOrders']
            },
            messages() {
                return this.$store.getters['customerStore/getMessages']
            },
            formErrors: {
                get: function () {
                    return this.$store.getters['customerStore/getFormErrors']
                },
                set: function (errorClear = {}) {
                    this.$store.commit('customerStore/setFormErrors', errorClear);
                }
            },
        },
        methods:{
            async deleteFromWhislist(whishlist) {
                this.$Progress.start()
                let formData = {
                    item_id: whishlist.id
                }
                $("#wishlistPreload").fadeIn();
                await this.$store.dispatch('defaultStore/deleteFromWishlist', formData)
                .then((response)=>{
                    $("#wishlistPreload").fadeOut("slow");
                })
            },
            async replayMessage(message){
                this.showMessageReplayModal = true;
                this.closeMessageModal();
                this.formErrors = {};
                //this.$set(this, 'messageSendReplay', message);
                //this.messageSendReplay.attachment = [];
                //console.log(this.messageSendReplay)

                this.msgReplaySend.user_id = message.user_id;
                this.msgReplaySend.message = null;
                this.msgReplaySend.subject = message.subject;
                this.msgReplaySend.recipient = message.recipient;
                this.msgReplaySend.sender = message.sender;
                this.msgReplaySend.attachment = [];
            },
            async readMessage(message){
                this.showMessageModal = true;
                this.$set(this, 'messageReplay', message);
                if(message.reading_status == 1){
                    this.$Progress.start();
                    let formData = {
                        msgId: message.id
                    }
                    await this.$store.dispatch('customerStore/setMessageStatus', formData)
                    .then((response)=>{

                    })
                }
            },
            async sendMessage(){
                this.$Progress.start();
                let response = await this.$store.dispatch('customerStore/messageReplaySend', this.msgReplaySend);
                if (response) {
                    this.showMessageReplayModal = false;
                } else {
                    this.showMessageReplayModal = true;
                }

                this.$Progress.finish();
            },
            onImageChange(e) {
                var that = this;
                image.temporaryImageUpload(e.target.files)
                    .then((response) => {
                        var reviewImages = that.msgReplaySend.attachment;
                        response.forEach(function (element) {
                            reviewImages.push(element);
                        })
                        //that.$set(that.messageReplay, 'attachment', reviewImages);
                    })
            },
            async closeMessageModal(){
                return this.showMessageModal = false;
            },
            async closeMessageReplayModal(){
                return this.showMessageReplayModal = false;
            },
            async pagePreloads(){
                await this.$store.dispatch('defaultStore/wishlistItems')
                    .then((response)=>{
                        $("#wishlistPreload").fadeOut("slow");
                    })
                    .catch((error) => {
                        // console.log(error)
                    })
                await this.$store.dispatch('customerStore/profile')
                    .then((response)=>{
                        $("#userPreload").fadeOut("slow");
                        $("#defaultBillingPreload").fadeOut("slow");
                        $("#defaultShippingPreload").fadeOut("slow");
                    })
                    .catch((error) => {
                        // console.log(error)
                    })
                await this.$store.dispatch('customerStore/orders')
                    .then((response)=>{
                        $("#orderListPreload").fadeOut("slow");
                    })
                    .catch((error) => {
                        // console.log(error)
                    })
                await this.$store.dispatch('customerStore/messages')
                    .then((response)=>{
                        $("#messageListPreload").fadeOut("slow");
                    })
                    .catch((error) => {
                        // console.log(error)
                    })

                $("#messageListPreload").fadeOut("slow");
            },
            async ordersPreload(page = 1){
                this.$Progress.start()
                $("#orderListPreload").fadeIn();
                await this.$store.dispatch('customerStore/orders', page)
                    .then((response)=>{
                        $("#orderListPreload").fadeOut("slow");
                    })
                    .catch((error) => {
                        // console.log(error)
                    })
            },
            async massegesPreload(page = 1){
                this.$Progress.start()
                $("#messageListPreload").fadeIn();
                await this.$store.dispatch('customerStore/messages', page)
                    .then((response)=>{
                        $("#messageListPreload").fadeOut("slow");
                    })
                    .catch((error) => {
                        // console.log(error)
                    })
            },
            buyerLogout() {
                this.$store.dispatch('customerStore/tryLogout');
            },
            async newsletterUpdate(status) {
                if(status === '0' || status === null){
                    let formData = {
                        email : this.userInformation.email,
                    }
                    await this.$store.dispatch('defaultStore/addNewsletter',  formData).then(()=>{
                        this.$swal({
                            icon: 'success',
                            title: 'subscribe success'
                        })
                    })
                }
                if(status === '1'){
                    console.log(status)
                    this.$store.dispatch('customerStore/newsletterUpdate').then(()=>{
                        this.$swal({
                            icon: 'success',
                            title: 'unsubscribe success'
                        })
                    });
                }



                return this.$store.getters['customerStore/getUserInformation']
            },
        },
    }
</script>
