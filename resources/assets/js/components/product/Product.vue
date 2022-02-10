<template>
    <span class="masterview">
        <section class="product_area">
            <div class="product_heading">
                <h2 v-if="CategoryInfo">
                    <span v-if="CategoryInfo.parent ">{{CategoryInfo.parent.name}}  </span> 
                    <span v-if="CategoryInfo.sub ">{{CategoryInfo.sub.name}} / </span> 
                    <span v-if="CategoryInfo.current">{{CategoryInfo.current.name}}</span>
                </h2>
            </div>
            <div class="product_filter">
                <span>Clear All Filters</span>
                <ul>
                    <li>Refine By</li>
                    
                    <li data-toggle="collapse_slide" data-target="#Designer">Colors <span><i class="lni-chevron-up"></i></span> </li>
                    <div class="f_bottom f_bottom_designer" id="Designer">
                        <ul v-if="GetAllColors">
                            <li v-for="(color, index) in GetAllColors" :key="index" @click="ColorFilter(color.color_id)"><router-link to="">{{color.name}}</router-link></li> 
                        </ul>
                    </div>
                    <li data-toggle="collapse_slide" data-target="#Price">Price <span><i class="lni-chevron-up"></i></span></li>
                    <div class="f_bottom f_bottom_price" id="Price">
                        <ul>
                            <li><a href="#">Sale items only</a></li>
                            <li @click.prevent="PriceFilter(0,25)"><a href="#"><span>USD$0.00</span> <span>USD$25.00</span></a></li>
                            <li @click.prevent="PriceFilter(26,50)"><a href="#"><span>USD$26.00</span> <span>USD$50.00</span></a></li>
                            <li @click.prevent="PriceFilter(51,75)"><a href="#"><span>USD$51.00</span> <span>USD$75.00</span></a></li>
                            <li @click.prevent="PriceFilter(76,100)"><a href="#"><span>USD$76.00</span> <span>USD$100.00</span></a></li>
                            <li @click.prevent="PriceFilter(100,100000)"><a href="#"><span>USD$101.00</span> <span>and above</span></a></li>
                        </ul>
                    </div>
                    <li data-toggle="collapse_slide" data-target="#Value">Sizes <span><i class="lni-chevron-up"></i></span> </li>
                    <div class="f_bottom f_bottom_value" id="Value">
                        <ul v-if="GetAllSize.sizes && GetAllSize.sizes.length>0">
                            <li v-for="(size,index) in GetAllSize.sizes" :key="index"   @click="SizeFilter(size.id)">  <router-link to=""><span>{{size.name}} / {{size.item_size}}</span></router-link> </li> 
                        </ul>
                    </div>
                </ul>
            </div>
            <div class="product_filter_mobile">
                <div data-toggle="collapse_slide" data-target="#mfilter" class="m_f_button">Refine Your Search </div>
                <div class="p_filter_content" id="mfilter">
                    <div class="header_menu_inner mobile_nav">
                        <ul>
                            <li class="has_child" data-toggle="collapse_m_nav" data-target="#Designer1">
                                Colors
                            </li>
                            <div class="show_from_left" id="Designer1">
                                <ul class="mobile_submenu">
                                    <li><router-link to=""> Refine By: Colors </router-link> <span class="back">Back</span></li>
                                    <li v-for="(color, index) in GetAllColors" :key="index" @click="ColorFilter(color.color_id)"><router-link to="">{{color.name}}</router-link></li>
                                </ul>
                            </div>
                            <li class="has_child" data-toggle="collapse_m_nav" data-target="#Price1">
                                Price 
                            </li>
                            <div class="show_from_left" id="Price1">
                                <ul class="mobile_submenu">
                                    <li><a href="#"> Refine By: Price</a> <span class="back">Back</span></li>  
                                    <li @click.prevent="PriceFilter(0,25)"> <router-link to=""> <span>USD$0.00</span> <span>USD$25.00</span></router-link></li>
                                    <li @click.prevent="PriceFilter(26,50)"> <router-link to=""> <span>USD$26.00</span> <span>USD$50.00</span></router-link></li>
                                    <li @click.prevent="PriceFilter(51,75)"> <router-link to=""> <span>USD$51.00</span> <span>USD$75.00</span></router-link></li>
                                    <li @click.prevent="PriceFilter(76,100)"> <router-link to=""> <span>USD$76.00</span> <span>USD$100.00</span></router-link></li>
                                    <li @click.prevent="PriceFilter(100,100000)"> <router-link to=""> <span>USD$101.00</span> <span>and above</span></router-link></li>
                                </ul>
                            </div>
                            <li class="has_child" data-toggle="collapse_m_nav" data-target="#Value1">
                                Sizes 
                            </li>
                            <div class="show_from_left" id="Value1">
                                <ul class="mobile_submenu">
                                    <li><a href="#"> Refine By: Value</a> <span class="back">Back</span></li>
                                    <li v-for="(size,index) in GetAllSize.sizes" :key="index"   @click="SizeFilter(size.id)">  <router-link to=""><span>{{size.name}} / {{size.item_size}}</span></router-link> </li>
                                </ul>
                            </div>
                        </ul>
                    </div>
                    <span class="close_h_menu p_filter_close">close</span>
                </div>
            </div>
            <div class="product_content"  >
                <div class="product_content_wrap" v-if="paginationData.data && paginationData.data.length>0"> 
                    <div class="product_content_inner" v-for="item in paginationData.data" :key="item.id"  >
                        <div class="product_inner_wrap">
                            <router-link :to="{ path: `/single/${item.slug}` }">
                                <img v-if="item.images[0]" :src="'/' + item.images[0].compressed_image_path" alt="" class="img-fluid">
                                <img v-else :src="'/' + AllItems.default_img.value" alt="" class="img-fluid">
                            </router-link> 
                            <div class="product_details_text">
                                <h2 class="product_title">  <router-link :to="{ path: `/single/${item.slug}` }">{{item.name}}</router-link> </h2>
                                <p class="product_price"><span v-if="item.brand">{{item.brand.name}} </span> | <span>USD${{item.price}}</span></p>
                                <div class="product_on_hover">
                                    <ul class="p_list" v-if="item.sizes"> 
                                        <li v-for="(size, sizeindex) in item.sizes"  :key="sizeindex">
                                            {{ size.item_size }}
                                        </li>
                                    </ul>
                                    <ul class="p_icon">
                                        <li><i class="fab fa-pagelines"></i></li>
                                        <li><i class="fab fa-envira"></i></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>  
                </div>
                <div class="ewrewr" v-else> 
                    <h2 class="text-center">No Item Found.</h2>  
                </div>
                               
            </div>

            <div class="pagination_row"> 
                <pagination :data="paginationData" @pagination-change-page="getResults"></pagination>
            </div> 
            <!-- <div class="product_content"> 
                <div class="main_product_head_right"> 
                    <div class="product_pagination pagination-data" style="">
                        <ul> 
                            <li> <a> <i class="lni-chevron-left"></i></a> </li> 
                            <li class="active disabled"><a>1</a></li>                 
                            <li><a>2</a></li> 
                            <li> 
                               <router-link to="#"><i class="lni-chevron-right"></i></router-link>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>  -->
        </section>
   
    </span>
</template> 

<script>  

import Axios from "axios" 

import _has from 'lodash/has'
import _get from 'lodash/get'

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
                id:'',   
                price:'',
                CategoryInfo:null,
                paginationData: {},
                path:'', 
                default_img:'sdfsdfds',
                form: new Form({
                    route: '', 
                    PerPage: 40, 
                    page: 1, 
                    sorting: 1, 
                    size: null, 
                    color: null, 
                    search_text:'',
                    order_by:'sorting',
                    sort_by:'asc',
                    price: { 
                        first: null,
                        last: null, 
                    }, 
                })
            }
    },
    components:{
            
    },
    mounted(){    
        this.MakeRoute()   
        this.form.search_text = this.$route.query.s 
        this.$store.dispatch('ParentCategoryAction', this.form);
        this.$store.dispatch('GetsAllSizesAction')
        this.$store.dispatch('GetsAllColorsAction') 
    },
    computed:{  
        metaData() {
            return {
                pageTitle: this.$store.getters.CategoryItems.category_page_title,
                charset: 'utf-8',
                viewport: 'width=device-width, initial-scale=1, shrink-to-fit=no',
                title: this.$store.getters.CategoryItems.category_meta_title,
                description: this.$store.getters.CategoryItems.category_meta_description
            }
        },
        // getResults(){   
        //     this.AllItem = this.GetAllItem(); 
        //     return this.GetAllItem();
        // },
        GetAllSize(){
            return this.$store.getters.AllSize
        },
        GetAllColors(){
            return this.$store.getters.AllColors
        },

    },
    created() {
		// Fetch initial results
		this.getResults();
	},
    methods:{ 
        getResults(page) {  
            if (typeof page === 'undefined') {
				page = 1;
			}
            Axios.post('/resource/search_items?page=' + page, this.form)
            .then(response => {
                var parsedata = response.data
                var parsedata = JSON.stringify(parsedata) 
                this.CategoryInfo = JSON.parse(parsedata).currentCategory
                this.paginationData = JSON.parse(parsedata).items;
            }) 
            this.form.page = page  
            
            this.$store.dispatch('ParentCategoryActionPagination',  this.form); 
                
        }, 
        PriceFilter(startPrice,EndPrice){
            this.form.price.first =  startPrice;
            this.form.price.last =  EndPrice;
            this.ItemSearch();
        },
        SizeFilter(id){
            this.form.size = id  
            this.ItemSearch();
        },
        ColorFilter(id){
            this.form.color = id  
            this.ItemSearch();
        },
        ItemSearch(){ 
            this.MakeRoute()   
            this.$store.dispatch('ParentCategoryAction',  this.form); 
            this.GetAllItem();
        },
        MakeRoute(){
            this.form.search_text = this.$route.query.s 
            if(this.$route.params.third){ 
                return this.form.route = this.$route.params.third
            }
            if(this.$route.params.sub){ 
                return this.form.route = this.$route.params.sub
            }
            if(this.$route.params.parent){ 
                return this.form.route = this.$route.params.parent
            } 
        },
        GetAllItem(){   
            return this.paginationData = this.$store.getters.CategoryItems 
        },
        SearchAvailableSize(){ 
            this.$store.dispatch('GetsAllSizesAction')
        },
        SearchAvailableColors(){ 
            this.$store.dispatch('GetsAllColorsAction')
        },
        
        
         
    },
    watch:{  
        $route(to,from){
            this.getResults();
            this.ItemSearch();
            this.MakeRoute();
            this.GetAllItem();
            this.SearchAvailableSize();
            this.SearchAvailableColors();
        }
    }
}

</script>