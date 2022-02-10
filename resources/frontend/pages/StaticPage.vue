<template>
      <section class="static_page_section mt_20" style="min-height:60vh;">
            <div class="static_page_wrapper container" >
                <div class="row" v-if="staticPage && staticPage.content" v-html="staticPage.content"></div>
                <div class="row" v-else><div class="col-md-12 text-center">
                    Contents not found
                </div></div>
            </div>
<!--            <p v-if="staticPage">{{staticPage.static_page_title}}</p>-->
      </section>
</template>
<script>
      import pageStore from './pageStore'
      export default {
            name:'StaticPage',
            beforeCreate() {
                  if (!(this.$store && this.$store.state && this.$store.state['pageStore'])) {
                        this.$store.registerModule("pageStore", pageStore);
                  }
            },
            beforeDestroy() {
                  // if ((this.$store && this.$store.state && this.$store.state['pageStore'])) {
                  //       this.$store.unregisterModule('pageStore');
                  // }
            },
            metaInfo(){
              return {
                  title: this.staticPage ? this.staticPage.metas.title : 'Home - Hologram',
                  }
            },
            mounted(){
                  this.GetStaticPage();
            },
            watch: {
                  successMessage(successMessage) {
                        if (successMessage) {
                            this.$swal({
                                    icon: 'success',
                                    title: successMessage
                              })
                              this.$store.commit('pageStore/setSuccessMessage', '');
                        }
                  },
                  errorMessage(errorMessage) {
                        if (errorMessage) {
                            this.$swal({
                                    icon: 'error',
                                    title: errorMessage
                              })
                              this.$store.commit('pageStore/setErrorMessage', '');
                        }
                  },
                  $route(to,from){
                        this.GetStaticPage();
                  }
            },
            computed:{
                  successMessage() {
                        return this.$store.getters['pageStore/getSuccessMessage'];
                  },
                  errorMessage() {
                        return this.$store.getters['pageStore/getErrorMessage'];
                  },
                  staticPage:{
                        get: function () {
                              return this.$store.getters['pageStore/getStaticPage']
                        },
                        set: function (staticPage = null) {
                              this.$store.commit('pageStore/setStaticPage', staticPage);
                        }
                  },
            },
            methods:{
                  GetStaticPage(){
                        this.$Progress.start()
                        this.$set(this, 'staticPage', null);
                        let payloadId = null;
                        let path = this.$route.path;
                        switch (path) {
                              case '/':
                                    payloadId = 1;
                                    break;
                              case '/about-us':
                                    payloadId = 2;
                                    break;
                              case '/contact-us':
                                    payloadId = 3;
                                    break;
                              case '/privacy-policy':
                                    payloadId = 4;
                                    break;
                              case '/terms-conditions':
                                    payloadId = 6;
                                    break;
                              case '/shipping-returns':
                                    payloadId = 7;
                                    break;
                              case '/size-chart':
                                    payloadId = 8;
                                    break;
                              case '/faq':
                                    payloadId = 16;
                                    break;
                              default:
                                    payloadId = null;
                                    break;
                        }
                        this.$store.dispatch('pageStore/staticPage',payloadId);
                  }
            },
      }
</script>
