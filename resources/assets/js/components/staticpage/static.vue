<template>
      <section class="product_single_area" style="min-height:60vh"> 
            <div class="static_page_wrapper" v-if="Contents" v-html="Contents"> </div> 
            <div class="static_page_wrapper" v-else> Contents not found</div> 
      </section>
</template>
<script>  
export default {
      name:'Header',
      metaInfo() {
            return {
                  title: this.metaData.pageTitle,
                  meta: [
                  { charset: this.metaData.charset },
                  { name: 'viewport', content: this.metaData.viewport },
                  { name: 'title', content: this.metaData.title },
                  { name: 'description', content: this.metaData.description }
                  ]
            }
      },
      data(){
            return{
                  id:''
            }
      },
      components:{
             
      },
      mounted(){ 
            this.GetStaticPage(); 
      },
      computed:{
            metaData() {
                  return {
                        pageTitle: this.$store.getters.StaticPageContents.static_page_title,
                        charset: 'utf-8',
                        viewport: 'width=device-width, initial-scale=1, shrink-to-fit=no',
                        title: this.$store.getters.StaticPageContents.static_page_meta_title,
                        description: this.$store.getters.StaticPageContents.static_page_meta_description
                  }
            }, 
            Contents(){
                  return this.$store.getters.StaticPageContents.content
            } 
      },
      methods:{ 
            GetStaticPage(){
                  if(this.$route.path == '/about-us'){
                        this.id = 2
                  }else if(this.$route.path == '/contact-us'){
                        this.id = 3
                  }else if(this.$route.path == '/terms-conditions'){
                        this.id = 6
                  }else if(this.$route.path == '/privacy-policy'){
                        this.id = 4
                  }else if(this.$route.path == '/shipping-returns'){
                        this.id = 7
                  }else if(this.$route.path == '/faq'){
                        this.id = 19
                  }else if(this.$route.path == '/size-chart'){
                        this.id = 8
                  }else{
                       this.id = null; 
                  }
                  this.$store.dispatch('StaticPageDispatch',this.id);
            }
      },
      watch:{ 
            $route(to,from){
                  this.GetStaticPage();
            }
      }
}
</script>