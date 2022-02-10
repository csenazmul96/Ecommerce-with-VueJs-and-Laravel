<template> 
<span class="masterview"> 
    <headerComponent v-if="HeaderReBuild" :HeaderRelation="Authentication"></headerComponent> 
    <router-view v-if="RouterLinkReBuild"></router-view>
    <footerComponent v-if="FooterReBuild" :FooterContent="Authentication"></footerComponent> 
</span>
</template> 
 
<script> 
import Header from './Header.vue';

export default {
    name:'master-component', 
    components: {
      headerComponent: () => import(/* webpackChunkName: "js/headerComponent" */ './Header.vue'),
      footerComponent: () => import(/* webpackChunkName: "js/footerComponent" */ './Footer.vue'),
    },
    data(){
        return{ 
            HeaderReBuild: true,
            FooterReBuild: true,
            RouterLinkReBuild: true, 
            Authentication:null,
            HeaderData:null,
            id:null, 
        }
    },
    mounted(){   
        
    },
    computed:{  
    },
    methods:{ 
        ParentAuthMethod(data){ //Generate user login/logout condition resorce get from header and decision send to footer and header.  
            if(data == 0){ //User Not Loged In
                this.RebuildFooter();  //Footer section Re-Build for user logout  
                return this.Authentication = false;  //Footer section menu status condition send to footer by 'FooterContent' props
            }else if(data == 1){ //User  Loged In
                this.RebuildFooter(); //Footer section Re-Build for user logIn 
                return this.Authentication = true; //Footer section menu status condition send to footer by 'FooterContent' props
            } 
        },
        RebuildFooter(){  //Footer Component Re-Build    
            this.FooterReBuild = false;
            this.$nextTick(() => {  
                this.FooterReBuild = true; 
            })  
        }, 
        Rebuildheader(){  //Footer Component Re-Build    
            this.HeaderReBuild = false; 
            this.$nextTick(() => { 
                this.HeaderReBuild = true; 
            })  
        }, 
    }, 
}
</script>