<template>
    <!-- ============================
        START CUSTOMER FOOTER SECTION
    ============================== -->
    <div>
        <div class="my_acc_container">
            <div class="my_acc_back">
                <router-link :to="{ name: 'home'}" class="btn btn_transparent width_240p">
                    <i class="fas fa-shopping-cart"></i> <span class="ml_5">Continue Shopping</span> 
                </router-link>
                <button class="btn_transparent width_240p" @click.prevent="buyerLogout">Log Out</button>
            </div>
        </div>

        <div class="logout_mobile">
            <button class="btn_transparent" @click.prevent="buyerLogout">logout</button>
        </div>
    </div>
    <!-- ============================
        END CUSTOMER FOOTER SECTION
    ============================== -->
</template>
<script> 
    import customerStore from '../../customer/customerStore';
    export default {
        name:'customerFooter',
        beforeCreate() {
            if (!(this.$store && this.$store.state && this.$store.state['customerStore'])) {
                this.$store.registerModule("customerStore", customerStore);
            }
        },
        methods:{
            buyerLogout() {
                this.$Progress.start()
                this.$store.dispatch('customerStore/tryLogout');
            },
        },
    }
</script>