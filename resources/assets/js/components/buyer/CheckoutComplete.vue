<template>
	<span class="cart_component"> 
             
            <section class="cart_area checkout_area">  
        <div class="checkout_title">
            <h2>Secure checkout</h2>
        </div>
        <div class="checkout_content">
            <div class="customer_information checkout_content_inner text-center"> 
				<h2 class="text-center">Order Complete</h2>
				<router-link to="/" class="text-center"> Back to home page</router-link>	
             </div>
        </div> 
    </section>
	</span>
</template>


<script>
export default {
    name:'Order-Complete',
    data(){
        return{
             
        }
    },
    mounted(){ 
        this.$store.dispatch('GetCartItem');
        this.ShowCartItems();
    },
    methods:{
        ShowCartItems(){
            return this.$store.getters.ShowCartItems
        },
    },
}
</script>
