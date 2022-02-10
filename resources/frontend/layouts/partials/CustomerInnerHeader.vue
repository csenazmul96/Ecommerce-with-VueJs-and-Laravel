<template>
    <!-- ============================
        START CUSTOMER FOOTER SECTION
    ============================== -->
    <div class="account_bredcrumbs">
        <div class="a_c_bredcrumbs_left">
            <a href="javascript:void(0)" onclick="window.history.back()">← back </a>
        </div>
        <div class="a_c_bredcrumbs_right">
            <a href="javascript:void(0)" @click.prevent="buyerLogout">Log Out</a>
        </div>
    </div>
    <!-- ============================
        END CUSTOMER FOOTER SECTION
    ============================== -->
</template>
<script> 
    import customerStore from '../../customer/customerStore';
    export default {
        name:'customerHeader',
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