<template> 
    <div class="account_info_right col-md-10 col-12">
        <h4 class="text-center">Orders</h4>
        <br>
            <table class="table table-responsive" v-if="Orders.orders && Orders.orders.length > 0">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Date</th>
                        <th>Total</th> 
                        <th>Status</th>
                        <th>View</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(order,index) in Orders.orders" :key="index">
                        <td>{{order.order_number}}</td>
                        <td>{{order.created_at || DateFormat}}</td>
                        <td>{{order.total}}</td> 
                        <td> 
                            <span v-if="order.status == 1">Init</span>
                            <span v-else-if="order.status == 2">New</span>
                            <span v-else-if="order.status == 3">Confirm</span>
                            <span v-else-if="order.status == 4">Partially Shipped</span>
                            <span v-else-if="order.status == 5">Fully Shipped</span>
                            <span v-else-if="order.status == 6">Back Order</span>
                            <span v-else-if="order.status == 7">Cancelled By Buyer</span>
                            <span v-else-if="order.status == 8">Cancelled By R3</span>
                            <span v-else-if="order.status == 9">Cancelled By Agrement</span>
                            <span v-else-if="order.status == 10">Returned</span>
                        </td>
                        <td>
                            <router-link class="v-btn__content" to="" @click.native="GetOrderDetails(order)" >Details</router-link>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="no_order" v-else> No Order Found</div>
    </div>  
</template>
<script>
import buyerprofile from './ProfileTopSection.vue';
export default {
    name:'profile-orders',
    data(){
        return{
            
        }
    },
    mounted(){   
        this.$store.dispatch('AllOrders');
        
    },
    components:{
            'buyerprofile' : buyerprofile
      },
    computed:{  
        Orders(){
            return this.$store.getters.GetOrders
        }
    },
    methods:{   
         GetOrderDetails(order){
            this.$parent.someMethod(order);
         }
    }, 
}
</script>