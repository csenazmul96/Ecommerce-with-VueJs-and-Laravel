<template>
    <span  class="masterview"> 
        <div class="my_account_dashboard ">
                <div class="wishlist_area my_acc_container">
                    <h2 class="my_acc_subtitle">MY WISHLIST <i v-if="AllItems && AllItems.length>0">({{AllItems.length}})</i> </h2>
                    <div class="wishlist_item" v-if="AllItems && AllItems.length>0"> 
                            <div class="product_content_inner" v-for="(item,index) in AllItems" :key="index">
                                <div class="product_inner_wrap"> 
                                        <router-link :to="`/single/${item.slug}`">
                                            <img :src="'/' + item.images[0].compressed_image_path" alt="" class="img-fluid">
                                        </router-link> 
                                        <div class="product_details_text">
                                        <h2 class="product_title"> <router-link :to="`/single/${item.slug}`">{{item.name}}</router-link></h2>
                                        <p class="product_price"><span v-if="item.brand">{{item.brand.name}} </span> | <span>USD${{item.price}}</span></p>
                                        <div class="remove_wishlist">
                                            <span @click="RemoveToWishlist(item.id)">Remove</span>
                                            <span><i class="far fa-check-circle"></i> In stock</span>
                                        </div>
                                        </div>
                                </div>
                            </div> 
                    </div>        
                    <h4 v-else class="text-center" >Wishlist Empty !!</h4>  
                </div>
        </div> 
                
    </span>
</template>

<script>
import buyerprofile from './ProfileTopSection.vue';
export default {
name:'profile-top-section',
    data(){
        return{
            wishlist: new Form({
                id: '',
                user:null,
            }),
        }
    },
    mounted(){   
        this.$store.dispatch('GetProfileWishlist')  
    },
    components:{
        'buyerprofile' : buyerprofile
    },
    computed:{ 
        AllItems(){ 
            return this.$store.getters.Wishlist 
        }
    },
    methods:{   
         RemoveToWishlist(id){ 
                    let user = localStorage.getItem('UserData')  
                    if(user != 'null'){ 
                        this.wishlist.user = JSON.parse(user).id;
                    } 
                this.wishlist.id = id   
                this.wishlist.post('/api/v1/remove_wishlist') 
                .then((result) => {   
                    if(result.data.status == 'success'){ 
                        toast.fire({
                            icon: 'success',
                            title: result.data.message
                        }) 
                    }else{ 
                        toast.fire({
                            icon: 'error',
                            title: result.data.message
                        }) 
                    }
                    this.$store.dispatch('GetProfileWishlist'); 
                }).catch((err) => {
                    
                });
        }, 
    }, 
}
</script>