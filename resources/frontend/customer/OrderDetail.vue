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
        <div class="my_acc_container" v-if="!orderNumber">
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

                <div class="review_area">
                    <div class="review_content">
                        <div class="container">
                            <div class="review_bottom r_b_without_login">
                                <orderPagination v-if="orders" :paginateData="orders" @paginate="ordersPreload" ></orderPagination>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="my_acc_container" v-else>
            <div class="card my_acc_order">
                <div class="card_header">
                    <h2>Vendor Order Details - {{orderNumber}}</h2>
                </div>
                <div class="card-body" style="position:relative; min-height:150px;">
                    <div class="preloader_wrap" id="orderListPreload">
                        <div class="loader-container">
                            <div class="loader-circle"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="content col-md-12 margin-bottom-1x">
                            <h4>Vendor Order Details - {{orderNumber}}</h4>
                            <br><br><br>
                        </div>
                    </div>
                    <div class="row" v-if="orderSingle">
                        <div class="col-md-5">
                            <img :src="headerContents && headerContents.logo ? headerContents.logo : ''" alt="Site logo">
                            <!-- <p>
                                Arizona,
                                United States - 1235
                            </p> -->
                        </div>

                        <div class="col-md-2"></div>
                        <div class="col-md-5 ">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th>Order No.</th>
                                        <td class="text-right">{{orderSingle.order_number}}</td>
                                    </tr>
                                    <tr>
                                        <th>Order Date</th>
                                        <td class="text-right">{{orderSingle.created_at | dateFormate("MMMM DD, YYYY hh:mm:ss A")}}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td class="text-right">
                                            {{orderEnumeration[orderSingle.status]}}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class=" padding-bottom-1x" v-if="orderSingle">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Shipping Address</th>
                                    <th>Billing Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <template v-if="orderSingle.shipping_location">{{orderSingle.shipping_location}} <br></template>
                                        <template v-if="orderSingle.shipping_address">{{orderSingle.shipping_address}} <br></template>
                                        <template v-if="orderSingle.shipping_unit">{{orderSingle.shipping_unit}} <br></template>
                                        <template v-if="orderSingle.shipping_city">{{orderSingle.shipping_city}} <br></template>
                                        <template v-if="orderSingle.shipping_state || orderSingle.shipping_zip">{{orderSingle.shipping_state}} - {{orderSingle.shipping_zip}} <br></template>
                                        <template v-if="orderSingle.shipping_country">{{orderSingle.shipping_country}} <br></template>
                                        <template v-if="orderSingle.shipping_phone">Phone: {{orderSingle.shipping_phone}} , </template>
                                    </td>
                                    <td>
                                        <template v-if="orderSingle.billing_location">{{orderSingle.billing_location}} <br></template>
                                        <template v-if="orderSingle.billing_address">{{orderSingle.billing_address}} <br></template>
                                        <template v-if="orderSingle.billing_unit">{{orderSingle.billing_unit}} <br></template>
                                        <template v-if="orderSingle.billing_city">{{orderSingle.billing_city}} <br></template>
                                        <template v-if="orderSingle.billing_state || orderSingle.billing_zip">{{orderSingle.billing_state}} - {{orderSingle.billing_zip}} <br></template>
                                        <template v-if="orderSingle.billing_country">{{orderSingle.billing_country}} <br></template>
                                        <template v-if="orderSingle.billing_phone">Phone: {{orderSingle.billing_phone}} , </template>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Phone: </b></td>
                                    <td><b>Phone: </b>{{orderSingle.shipping_phone}}</td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Shipping Method</th>
                                    <td>{{orderSingle.shipping}}</td>
                                    <th>Tracking Number</th>
                                    <td>{{orderSingle.tracking_number}}</td>
                                    <th>Invoice Number</th>
                                    <td>{{orderSingle.invoice_number}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h4>Items</h4>
                    <hr class="padding-bottom-1x">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th class="text-center align-middle">Style No.</th>
                                    <th class="text-center align-middle">Size</th>
                                    <th class="text-center align-middle">Color</th>
                                    <th class="text-center align-middle">Qty</th>
                                    <th class="text-center align-middle">Unit Price</th>
                                    <th class="text-right align-middle">Amount</th>
                                </tr>
                            </thead>

                            <tbody v-if="orderSingle && orderSingle.items">
                                <tr v-for="(orderItem, itemIndex) in orderSingle.items" :key="'orderItem_' + itemIndex">
                                    <td rowspan="">
                                        <img :src="(orderItem.item.images && orderItem.item.images.length > 0 ? orderItem.item.images[0].thumbs_img : defaultImage.value)"
                                             :alt="'Hologram Product ' + orderItem.item.style_no"
                                             style="height:100px" @error="imgErrHndlHasan($event, orderItem.item.images[0].thumbs_image_path)">
                                    </td>
                                    <td class="text-center align-middle"> {{orderItem.style_no}}</td>
                                    <td class="text-center align-middle"> {{ (orderItem.size_name ? orderItem.size_name : '') }}</td>
                                    <td class="text-center align-middle"> {{ (orderItem.color_name ? orderItem.color_name : '')}}</td>
                                    <td class="text-center align-middle"> {{orderItem.qty}}</td>
                                    <td class="text-center align-middle"> ${{orderItem.per_unit_price | round(2)}}</td>
                                    <td class="text-right align-middle"> ${{orderItem.amount | round(2)}}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="row" v-if="orderSingle">
                        <div class="col-md-9"></div>
                        <div class="col-md-3">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th>Sub Total</th>
                                        <td class="text-right">${{orderSingle.subtotal | round(2)}}</td>
                                    </tr>

                                    <tr v-if="orderSingle.discount > 0">
                                        <th>Discount</th>
                                        <td class="text-right">${{orderSingle.discount | round(2)}}</td>
                                    </tr>
                                    <tr v-if="orderSingle.dollar_point_discount > 0">
                                        <th>Point Discount</th>
                                        <td class="text-right">${{orderSingle.dollar_point_discount | round(2)}}</td>
                                    </tr>

                                    <tr>
                                        <th>Shipping Cost</th>
                                        <td class="text-right">${{orderSingle.shipping_cost | round(2)}}</td>
                                    </tr>
                                    <tr>
                                        <th>Total</th>
                                        <td class="text-right"><b>${{orderSingle.total | round(2)}}</b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row" v-if="orderSingle">
                        <div class="col-md-12">
                            <p>
                                <b>Note: {{orderSingle.note}}</b>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="content col-md-12 margin-top-1x">
                            <router-link :to="{ name: 'order-detail'}" class="btn btn_common width_200p float-left">Back To
                                Order List
                            </router-link>
                            <!-- <a class="btn btn_common btnPrintInvoiceOrder width_200p float-right" href="#">Print Invoice</a> -->
                        </div>


                        <div class="modal fade" id="print-modal" tabindex="-1" role="dialog" aria-labelledby="modalLabelSmall"
                            aria-hidden="true">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="modalLabelSmall">Print</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <a class="btn btn-primary" href="" target="_blank" id="btnPrintWithImage">Print with
                                            Images</a><br><br>
                                        <a class="btn btn-primary" href="" target="_blank" id="btnPrintWithoutImage">Print without
                                            Images</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="my_acc_container">
            <div class="my_acc_back">
                <router-link :to="{ name: 'home'}" class="btn btn_transparent width_240p">
                    <i class="fas fa-shopping-cart"></i> <span class="ml_5">Continue Shopping</span>
                </router-link>
                <button class="btn_transparent width_240p" @click.prevent="buyerLogout">Log Out</button>
            </div>
        </div>

        <div class="logout_mobile">
            <button class="btn_transparent">logout</button>
        </div>
    </div>
    <!-- ============================
        END PROFILE DASHBOARD SECTION
    ============================== -->
</template>
<script>
    import * as dayjs from 'dayjs'
    import defaultStore from '../layouts/defaultStore';
    import customerStore from './customerStore';
    import mixins from "../helpers/mixins";
    export default {
        name:'customerDashboard',
        mixins: [mixins],
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
                }
            }
        },
        components: {
            productComponent: () => import(/* webpackChunkName: "js/customerOrder/productComponent" */ './components/WishlistProduct.vue'),
            orderPagination: () => import(/* webpackChunkName: "js/customerOrder/orderPagination" */ './components/Pagination.vue'),
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
            if(this.orderNumber) {
                this.singleOrder();
            } else {
                this.ordersPreload();
            }
        },
        watch: {
            $route(to,from){
                if(this.orderNumber) {
                    this.singleOrder();
                } else {
                    this.ordersPreload();
                }
            },
        },
        computed: {
            defaultImage() {
                return this.$store.getters['defaultStore/getDefaultImage']
            },
            orders() {
                return this.$store.getters['customerStore/getOrders']
            },
            orderSingle() {
                return this.$store.getters['customerStore/getSingleOrder']
            },
            orderNumber() {
                return this.$route.params.order_number
            },
            headerContents:{
                get: function () {
                    return this.$store.getters['defaultStore/getHeaderContents']
                },
                set: function (contents = null) {
                    this.$store.commit('defaultStore/setHeaderContents', contents);
                }
            },
        },
        methods:{
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
            async singleOrder(){
                this.$Progress.start()
                $("#orderListPreload").fadeIn();
                await this.$store.dispatch('customerStore/singleOrder', this.orderNumber)
                    .then((response)=>{
                        $("#orderListPreload").fadeOut("slow");
                    })
                    .catch((error) => {
                        // console.log(error)
                    })
            },
            buyerLogout() {
                this.$Progress.start()
                this.$store.dispatch('customerStore/tryLogout');
            },
        },
    }
</script>
