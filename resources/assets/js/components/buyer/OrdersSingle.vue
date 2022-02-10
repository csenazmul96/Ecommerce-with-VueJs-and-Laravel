<template>
      <div class="account_info_right col-md-10 col-12"  v-if="order.orders"> 
        <h4 class="text-center" v-if="order.orders">Order #{{ order.orders.order_number }} </h4> 
            <dl class="order-info">
                <dt>About This Order:</dt>
                <dd>
                    <ul id="order-info-tabs">
                        <li class="current first last">Order Information</li> 
                    </ul> 
                </dd>
            </dl> 
            <div class="box">
                <table class="table table-bordered" >
                    <tr>
                        <th>Order Status</th>
                        <td> 
                            <span v-if="order.orders.status == 6 && order.orders.rejected == 0">

                            </span>
                        </td>
                    </tr>  
                </table>
                <table class="table table-bordered">
                    <tr>
                        <th>Order No.</th>
                        <td>{{ order.orders.order_number }}</td>
                    </tr>

                    <tr>
                        <th>Order Date</th>
                        <td>{{ order.orders.created_at  | DateFormat}}</td>
                    </tr>

                    <tr v-if="order.orders.statusText">
                        <th>Status</th>
                        <td>
                            {{ order.orders.statusText }}
 
                        </td>
                    </tr>
                </table>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Shipping Address</th>
                        <th>Billing Address</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ order.orders.shipping_address }}<br>
                                {{ order.orders.shipping_state }}, {{ order.orders.shipping_city }}, {{ order.orders.shipping_country }} - {{ order.orders.shipping_zip }}</td>
                            <td>{{ order.orders.billing_address }}<br>
                                {{ order.orders.billing_state }}, {{ order.orders.billing_city }}, {{ order.orders.billing_country }} - {{ order.orders.billing_zip }}</td>
                        </tr>
                        <tr>
                            <td><b>Phone: </b>{{ order.orders.shipping_phone }}</td>
                            <td><b>Phone: </b>{{ order.orders.billing_phone }}</td>
                        </tr>
                    </tbody>
                </table> 
            </div>
            <div class="box"> 
                <table class="table table-bordered" >
                    <tr>
                        <th>Shipping Method</th>
                        <td>{{ order.orders.shipping }}</td>
                    </tr>  
                </table>
                <dt>Order Items</dt>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Image</th>
                        <th>Style No.</th>
                        <th class="text-center">Color</th>
                        <th class="text-center" >Size</th> 
                        <th class="text-center"> Qty</th>
                        <th class="text-center">Unit Price</th>
                        <th class="text-center">Amount</th>
                    </tr>
                    </thead>

                    <tbody> 
                        <template v-for="items in order.allItems">
                            <tr>
                                <td > 
                                    <img v-if="items.image" :src="items.image.thumbs_image_path" alt="Product" width="70px"> 
                                    <img v-else :src="order.default_img.value" alt="Product" width="70px"> 
                                </td>

                                <td class="text-center">
                                    {{ items.style_no }}
                                </td>
                                <td class="text-center">
                                    <span v-if="items.colors">{{items.colors.name}}</span>
                                </td> 

                                <td class="text-center">
                                 <span v-if="items.sizes">{{items.sizes.item_size}}</span>
                                </td> 

                                <td class="text-center">
                                    {{ items.qty }}
                                </td> 

                                <td class="text-center">
                                    {{ items.per_unit_price }} 
                                </td>

                                <td class="text-center">
                                   {{ items.qty*items.per_unit_price }}
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                <table class="table table-bordered">
                <tr>
                    <th>Sub Total</th>
                    <td>${{ order.orders.subtotal }}</td>
                </tr>

                <tr v-if="order.orders.discount">
                    <th>Discount</th>
                    <td>${{ order.orders.discount }}</td>
                </tr>

                <tr>
                    <th>Shipping Cost</th>
                    <td>${{ order.orders.shipping_cost}}
                        <br>
                        <span v-if="order.orders.free_shipping ==1">Free Shipping</span>        
                    </td>
                </tr>  
                <tr>
                    <th>Total</th>
                    <td><b>${{ order.orders.total - order.orders.discount}}</b></td>
                </tr> 
            </table>
            <table class="table table-bordered" >
                <tr v-if="order.orders.note" >
                    <th>Order Note</th>
                    <td>{{ order.orders.note }}</td>
                </tr> 
                <tr v-if="order.orders.admin_note">
                    <th>Admin Note</th>
                    <td>{{ order.orders.admin_note }}</td>
                </tr> 
            </table>
            <!-- <ul class="print_invoice">
                <li>
                    <button class="btn_grey" >Print Invoice</button >
                    <button class="btn_grey" >Print with Images</button >
                    <button class="btn_grey" >Print without Images</button > 
                </li> 
            </ul> -->
            </div>  
        <br>   
      </div>
</template>
<script>
export default {
    name:'profile-orders-single',
    props:['orderid'],
    data(){
        return{
            order:[],
            SingleId:null, 
            rebuield: true,
            
        } 
    }, 
    mounted(){   
        this.SingleId = this.orderid;
        // this.SingleId = 1;
        this.Order();
    },
    
    computed:{  
        
    },
    methods:{   
        Order(){    
             if(this.SingleId){ 
              axios.get('/api/v1/single/order/'+this.SingleId,{  headers: { 'Authorization': 'Bearer '+localStorage.getItem('access_token')}})
                .then( (result) =>{     
                    var parsedata = result.data
                var parsedata = JSON.stringify(parsedata) 
                this.order = JSON.parse(parsedata)  
                    console.log(this.order) 
                    }
                )
            }  
        } , 
    }, 
}
</script>