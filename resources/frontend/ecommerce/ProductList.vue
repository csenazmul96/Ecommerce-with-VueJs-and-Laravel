<template>
    <!-- ============================
        START PRODUCT SECTION
    ============================== -->
    <section class="product_area" :style="productAreaCss">
        <div class="product_banner" v-if="breadCrumbs && breadCrumbs.parent && breadCrumbs.parent.image">
            <img :src="breadCrumbs.parent.image" alt="" class="img-fluid">
            <div class="inner_text">
                <h1 v-if="breadCrumbs && breadCrumbs.parent">{{breadCrumbs.parent.sub_title}}</h1>
                <p v-if="breadCrumbs && breadCrumbs.parent">{{breadCrumbs.parent.description}}</p>
            </div>
        </div>

        <div :class="['hl_bredcrumbs', {'has_margin': breadCrumbs && breadCrumbs.parent && (!breadCrumbs.parent.image || breadCrumbs.parent.image === '') }]">
            <ul class="breadcrumb">
                <li><router-link :to="{ name: 'home'}" > Home</router-link></li>
                <template v-if="$route.query && $route.query.s">
                    <li> search key= "{{$route.query.s}}"</li>
                </template>
                <template v-else>
                    <template v-if="breadCrumbs && breadCrumbs.parent &&  breadCrumbs.sub_category">
                        <li v-if="breadCrumbs.parent"><router-link :to="`/${breadCrumbs.parent.slug}`">{{breadCrumbs.parent.name | capitalize}}</router-link></li>
                        <li>{{ breadCrumbs.sub_category.name | capitalize }}</li>
                    </template>
                    <template v-else>
                        <li v-if="breadCrumbs && breadCrumbs.parent">{{ breadCrumbs.parent.name | capitalize }}</li>
                    </template>
                </template>
            </ul>
        </div>

        <div class="product_filter" v-if="$route.name != 'search'">
            <ul class="p_f_first_child">
                <li>
                    <span @click.prevent="clearFilter">Clear All Filters</span>
                </li>
            </ul>
            <ul class="p_f_last_child">
                <li>Refine By</li>
                <div class="f_bottom f_bottom_designer" id="Designer">
                    <ul>
                        <li @click.prevent="brandFilter(null)"><a href="javascript:void(0)">No Specific Brand</a></li>
                        <li v-for="(brand, brandIndex) in allBrands" :key="'mobAllBrand_' + brandIndex" @click.prevent="brandFilter(brand.id)" :class="{active:submitForm.designers.includes(brand.id)}">
                            <a href="javascript:void(0)">{{brand.name}}</a>
                        </li>
                    </ul>
                </div>
                <li data-toggle="collapse_slide" data-target="#Price">Price
                    <span class="down"><i class="lni lni-chevron-down"></i></span>
                    <span class="up"><i class="lni lni-chevron-up"></i></span>
                </li>
                <div class="f_bottom " id="Price">
                    <ul>
                        <li @click.prevent="priceFilter('0', '25')" :class="{active:filters.startPrice.includes('0')}"><a href="javascript:void(0)"><span>USD$0.00</span> <span>USD$25.00</span></a></li>
                        <li @click.prevent="priceFilter('26', '50')" :class="{active:filters.startPrice.includes('26')}"><a href="javascript:void(0)"><span>USD$26.00</span> <span>USD$50.00</span></a></li>
                        <li @click.prevent="priceFilter('51', '75')" :class="{active:filters.startPrice.includes('51')}"><a href="javascript:void(0)"><span>USD$51.00</span> <span>USD$75.00</span></a></li>
                        <li @click.prevent="priceFilter('76', '100')" :class="{active:filters.startPrice.includes('76')}"><a href="javascript:void(0)"><span>USD$76.00</span> <span>USD$100.00</span></a></li>
                        <li @click.prevent="priceFilter('100', '100000')" :class="{active:filters.startPrice.includes('100')}"><a href="javascript:void(0)"><span>USD$101.00</span> <span>and above</span></a></li>
                    </ul>
                </div>
                <li class="filter_value_text" data-toggle="collapse_slide" data-target="#Value">Value
                    <span class="down"><i class="lni lni-chevron-down"></i></span>
                    <span class="up"><i class="lni lni-chevron-up"></i></span>
                </li>
                <div class="f_bottom f_bottom_value" id="Value">
                    <ul v-if="allValues && allValues.length > 0">
                        <li v-for="(value, valueIndex) in allValues" :key="'mobAllValues_' + valueIndex" @click.prevent="valueFilter(value.id)" :class="{active:submitForm.values.includes(value.id)}">
                            <a href="javascript:void(0)">
                                <span><i :class="value.icon"></i></span>
                                <span> {{value.name}}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </ul>
        </div>
        <div class="product_filter_mobile">
            <div data-toggle="collapse_slide" data-target="#mfilter" class="m_f_button">Refine Your Search </div>
            <div class="p_filter_content" id="mfilter">
                <div class="header_menu_inner mobile_nav">
                    <ul>
                        <div class="show_from_left" id="Designer1">
                            <ul class="mobile_submenu">
                                <li><a href="javascript:void(0)"> Refine By: Designer</a> <span class="back" @click.prevent="mobBackToTopMenu($event)">Back</span></li>
                                <li @click.prevent="brandFilter(null)"><a href="javascript:void(0)">No Specific Brand</a></li>
                                <li v-for="(brand, brandIndex) in allBrands" :key="'mobAllBrand_' + brandIndex" @click.prevent="brandFilter(brand.id)"><a href="javascript:void(0)">{{brand.name}}</a></li>
                            </ul>
                        </div>
                        <li class="has_child" data-toggle="collapse_m_nav" data-target="#Price1">
                            Price
                        </li>
                        <div class="show_from_left" id="Price1">
                            <ul class="mobile_submenu">
                                <li><a href="javascript:void(0)"> Refine By: Price</a> <span class="back" @click.prevent="mobBackToTopMenu($event)">Back</span></li>
                                <li @click.prevent="priceFilter(0, 25)"><a href="javascript:void(0)"><span>USD$0.00</span> <span>USD$25.00</span></a></li>
                                <li @click.prevent="priceFilter(26, 50)"><a href="javascript:void(0)"><span>USD$26.00</span> <span>USD$50.00</span></a></li>
                                <li @click.prevent="priceFilter(51, 75)"><a href="javascript:void(0)"><span>USD$51.00</span> <span>USD$75.00</span></a></li>
                                <li @click.prevent="priceFilter(76, 100)"><a href="javascript:void(0)"><span>USD$76.00</span> <span>USD$100.00</span></a></li>
                                <li @click.prevent="priceFilter(100, 100000)"><a href="javascript:void(0)"><span>USD$101.00</span> <span>and above</span></a></li>
                            </ul>
                        </div>
                        <li class="has_child" data-toggle="collapse_m_nav" data-target="#Value1">Value</li>
                        <div class="show_from_left" id="Value1">
                            <ul class="mobile_submenu">
                                <li><a href="javascript:void(0)"> Refine By: Value</a> <span class="back" @click.prevent="mobBackToTopMenu($event)">Back</span></li>
                                <li v-for="(value, valueIndex) in allValues" :key="'mobAllValues_' + valueIndex" @click.prevent="valueFilter(value.id)">
                                    <a href="javascript:void(0)">
                                        <span><i :class="value.icon"></i></span>
                                        <span> {{value.name}}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <li  @click.prevent="clearFilter"> Clear Filter </li>
                    </ul>
                </div>
                <span class="close_h_menu p_filter_close"  @click.prevent="mobCloseMenu">close</span>
            </div>
        </div>
        <div class="product_content">
            <div class="product_wrap">
                <div class="product_heading" ref="productHeading" :style="breadcrumbsCss" v-if="breadcrumbsShow">
                    <h2 v-if="breadCrumbs">
                        <span v-if="breadCrumbs.current">{{breadCrumbs.current.name | capitalize}}</span>
                        <span v-if="breadCrumbs.sub_category">
                            <router-link :to="`/${breadCrumbs.parent.slug}/${breadCrumbs.sub_category.slug}`">{{breadCrumbs.sub_category.name | capitalize}} / </router-link>
                        </span>
                        <span v-if="breadCrumbs.parent">
                            <router-link :to="`/${breadCrumbs.parent.slug}`">{{breadCrumbs.parent.name | capitalize}}</router-link>
                        </span>
                    </h2>
                </div>
                <h3 v-if="products.length == 0 && preloaderShow" class="not_found">
                    No Result Found
                </h3>
                <div class="product_content_wrap">
                    <productComponent v-for="(product, productKey) in products" :key="'product_' + productKey"  :product="product" :defaultImage="defaultImage"></productComponent>
                </div>
                <productPagination v-if="paginatedProducts" :paginateData="paginatedProducts" @paginate="chagePage" ></productPagination>
            </div>
        </div>
    </section>
    <!-- ============================
        END PRODUCT SECTION
    ============================== -->
</template>
<script>
    import ecommerceStore from './ecommerceStore'
    import productComponent from './components/Product'
    import productPagination from './components/Pagination'
    export default {
        name:'productList',
        components: {
            productComponent: productComponent,
            productPagination: productPagination,
        },
        data(){
            return{
                breadcrumbsWidth: null,
                breadcrumbsShow: false,
                preloaderShow: false,
                filters: {
                    startPrice: this.$route.query.startPrice ? this.$route.query.startPrice.split(",") : [],
                    endPrice: this.$route.query.endPrice ? this.$route.query.endPrice.split(",") : [],
                },
                routeForm:{
                    search_text: this.$route.query ? this.$route.query.s : null,
                    startPrice: this.$route.query.startPrice ? this.$route.query.startPrice : null,
                    endPrice: this.$route.query.endPrice ? this.$route.query.endPrice : null,
                    page: this.$route.params ? this.$route.params.page : null,
                    colors:[],
                    designers:[],
                    values:[],
                },
                submitForm:{
                    parent: this.$route.params ? this.$route.params.parent : null,
                    second: this.$route.params ? this.$route.params.sub : null,
                    third: this.$route.params ? this.$route.params.third : null,
                    PerPage: 40,
                    page: this.$route.params ? this.$route.params.page : 1,
                    sorting: 1,
                    size: null,
                    color: this.$route.query ? this.$route.query.color : null,
                    search_text:this.$route.query ? this.$route.query.s : null,
                    order_by:'sorting',
                    sort_by:'asc',
                    price:[],
                    startPrice: this.$route.query.startPrice ? this.$route.query.startPrice : null,
                    endPrice: this.$route.query.endPrice ? this.$route.query.endPrice : null,
                    //minprice:null,
                    //maxprice:null,
                    colors: this.$route.query && this.$route.query.colors && this.$route.query.colors.length? JSON.parse(this.$route.query.colors): [],
                    designers: this.$route.query && this.$route.query.designers && this.$route.query.designers.length? JSON.parse(this.$route.query.designers): [],
                    values: this.$route.query && this.$route.query.values && this.$route.query.values.length? JSON.parse(this.$route.query.values): [],
                }
            }
        },
        metaInfo(){
            return {
                title: this.breadCrumbs && this.breadCrumbs.metas? this.breadCrumbs.metas.title : null
            }
        },
        beforeCreate() {
            if (!(this.$store && this.$store.state && this.$store.state['ecommerceStore'])) {
                this.$store.registerModule("ecommerceStore", ecommerceStore);
            }
            this.breadcrumbsShow = false;
        },
        mounted(){
            $(document).on('click', '[data-toggle="collapse_m_nav"]', function(e) {
                var mNavId = $(this).data('target');
                $(mNavId).addClass('open_h_menu');
            });
            this.productPreloads();
        },
        updated() {
            $('.submenu , .f_bottom').each(function() {
                var dropdown = $(this);
                dropdown.slideUp();
            });
            this.breadcrumbsWidth = this.$refs.productHeading ? this.$refs.productHeading.clientWidth : 0;
            this.breadcrumbsShow = true;
        },
        filters: {
            capitalize: function (value) {
                if (!value) return ''
                return value.toLowerCase();
                // return value.charAt(0).toLowerCase() + value.slice(1)
            }
        },
        created() {
            // console.log(this.$route.query.s)
        },
        watch: {
            successMessage(successMessage) {
                if (successMessage) {
                    this.$swal({
                        icon: 'success',
                        title: successMessage
                    })
                    this.$store.commit('ecommerceStore/setSuccessMessage', '');
                }
            },
            errorMessage(errorMessage) {
                if (errorMessage) {
                    this.$swal({
                        icon: 'error',
                        title: errorMessage
                    })
                    this.$store.commit('ecommerceStore/setErrorMessage', '');
                }
            },
            $route(to,from, next){
                this.submitForm.parent = this.$route.params ? this.$route.params.parent : null;
                this.submitForm.second = this.$route.params ? this.$route.params.sub : null;
                this.submitForm.third = this.$route.params ? this.$route.params.third : null;
                this.submitForm.search_text = this.$route.query ? this.$route.query.s : null;

                if (to.path !== from.path)
                    this.resetFilters();

                this.productPreloads();
            }
        },
        computed:{
            successMessage() {
                return this.$store.getters['ecommerceStore/getSuccessMessage'];
            },
            errorMessage() {
                return this.$store.getters['ecommerceStore/getErrorMessage'];
            },
            breadCrumbs:{
                get: function () {
                    return this.$store.getters['ecommerceStore/getBreadCrumbs']
                },
                set: function (breadCrumbs = null) {
                    this.$store.commit('ecommerceStore/setBreadCrumbs', breadCrumbs);
                }
            },
            defaultImage:{
                get: function () {
                    return this.$store.getters['ecommerceStore/getDefaultImage']
                },
                set: function (defaultImage = null) {
                    this.$store.commit('ecommerceStore/setDefaultImage', defaultImage);
                }
            },
            products:{
                get: function () {
                    return this.$store.getters['ecommerceStore/getProducts']
                },
                set: function (products = null) {
                    this.$store.commit('ecommerceStore/setProducts', products);
                }
            },
            paginatedProducts() {
                return this.$store.getters['ecommerceStore/getPaginatedProducts']
            },
            allValues() {
                return this.$store.getters['ecommerceStore/getAllValues'];
            },
            allBrands() {
                return this.$store.getters['ecommerceStore/getAllBrands'];
            },
            breadcrumbsCss() {
                if($(window).width() >= 1024) {
                    return {
                        'top' : `${this.breadcrumbsWidth}px`,
                    };
                } else {
                    return {
                        'top' : 'auto',
                    }
                }
            },
            productAreaCss() {
                if($(window).width() >= 1024) {
                    return {
                        'min-height' : `${this.breadcrumbsWidth + 60}px`,
                    };
                } else {
                    return {
                        'min-height' : 'auto',
                    }
                }
            }
        },
        methods:{
            mobBackToTopMenu(e) {
                $(e.target).closest('.show_from_left').removeClass('open_h_menu');
            },
            mobCloseMenu() {
                $('.show_from_left , .show_from_right').removeClass('open_h_menu');
                $('.menu').removeClass('open');
                $('.p_filter_content').slideUp();
            },
            chagePage(page = 1){
                this.$Progress.start()
                this.submitForm.page = page;
                this.routeForm.page = page;
                this.newRoute();
            },
            async productPreloads(){
                this.$Progress.start()
                this.submitForm.page = this.$route.query ? this.$route.query.page : 1;
                this.$store.dispatch('ecommerceStore/allValues');
                this.$store.dispatch('ecommerceStore/allBrands');
                $("#productListPreloader").fadeIn();
                await this.$store.dispatch('ecommerceStore/products',  this.submitForm)
                .then((response)=>{
                    this.preloaderShow = true;
                    $("#productListPreloader").fadeOut("slow");
                })
                .catch((error) => {
                })
                this.mobCloseMenu();
            },
            priceFilter(startPrice, endPrice){
                this.$Progress.start()

                if (this.filters.startPrice.includes(startPrice)) {
                    this.filters.startPrice.splice(this.filters.startPrice.indexOf(startPrice), 1);
                } else {
                    this.filters.startPrice.push(startPrice)
                }

                if(this.filters.endPrice.includes(endPrice)) {
                    this.filters.endPrice.splice(this.filters.endPrice.indexOf(endPrice), 1);
                }else {
                    this.filters.endPrice.push(endPrice)
                }
                //
                //this.submitForm.minprice =  this.submitForm.startPrice && this.submitForm.startPrice.length? Math.min(...this.submitForm.startPrice) : null;
                //this.submitForm.maxprice =  this.submitForm.endPrice && this.submitForm.endPrice.length ? Math.max(...this.submitForm.endPrice) : null;


                //this.routeForm.startPrice = startPrice
                //this.routeForm.endPrice = endPrice

                this.newRoute();
                this.mobCloseMenu();
            },

            filterColor(color){
                this.$Progress.start()
                let colors =  this.$route.query.colors && this.$route.query.colors.length? JSON.parse(this.$route.query.colors): [] ;
                if(colors.includes(color))
                    this.submitForm.colors.splice(this.submitForm.colors.indexOf(color),1);
                else
                    this.submitForm.colors.push(color)

                this.newRoute();
                this.mobCloseMenu();
            },
            valueFilter(value){
                console.log(value)
                this.$Progress.start()
                let values =  this.$route.query.values && this.$route.query.values.length? JSON.parse(this.$route.query.values): [] ;
                if(values.includes(value))
                    this.submitForm.values.splice(this.submitForm.values.indexOf(value),1);
                else
                    this.submitForm.values.push(value)
                this.newRoute();
                this.mobCloseMenu();
            },
            brandFilter(designer){
                this.$Progress.start()
                let designers =  this.$route.query.designers && this.$route.query.designers.length? JSON.parse(this.$route.query.designers): [] ;
                if(designers.includes(designer))
                    this.submitForm.designers.splice(this.submitForm.designers.indexOf(designer),1);
                else
                    this.submitForm.designers.push(designer)
                this.newRoute();
                this.mobCloseMenu();
            },
            resetFilters() {
                this.routeForm.designers = [];
                this.routeForm.values = [];
                this.routeForm.startPrice = null;
                this.routeForm.endPrice = null;
                this.routeForm.colors = [];
                this.submitForm.startPrice = null;
                this.submitForm.endPrice = null;
                this.filters.startPrice = [];
                this.filters.endPrice = [];


                this.submitForm.page = null;
            },
            clearFilter(){
                this.$Progress.start()
                this.resetFilters();

                if(this.$route.params && this.$route.params.sub)
                    this.$router.push({name: 'second-category', params: { parent: this.$route.params.parent, sub: this.$route.params.sub}})
                else
                    this.$router.push({name: 'parent-category', params: { parent: this.$route.params.parent}})
                this.mobCloseMenu();
                this.$Progress.finish()
            },
            newRoute(){
                for (const [key, value] of Object.entries(this.routeForm)) {
                    if (value == null)
                        delete this.routeForm[key];
                }

                //Colors filter
                if(this.submitForm.colors.length > 0)
                    this.routeForm.colors =  JSON.stringify(this.submitForm.colors);
                else
                    delete this.routeForm['colors']

                //Designer filter
                if(this.submitForm.designers.length > 0)
                    this.routeForm.designers =  JSON.stringify(this.submitForm.designers);
                else
                    delete this.routeForm['designers']

                //Values filter
                if(this.submitForm.values.length > 0)
                    this.routeForm.values = JSON.stringify(this.submitForm.values);
               else
                    delete this.routeForm['values']

                //Start Price filter
                if (this.filters.startPrice.length) {
                    this.routeForm.startPrice = this.filters.startPrice.join(',');
                    this.submitForm.startPrice = this.filters.startPrice.join(',');
                } else {
                    this.submitForm.startPrice = null;
                    delete this.routeForm['startPrice']
                }

                //End Price filter
                if(this.filters.endPrice.length) {
                    this.routeForm.endPrice = this.filters.endPrice.join(',');
                    this.submitForm.endPrice = this.filters.endPrice.join(',');
                } else {
                    this.submitForm.endPrice = null;
                    delete this.routeForm['endPrice']
                }


                if(this.$route.params && this.$route.params.third)
                    this.$router.push({name: 'third-category', params: { parent: this.$route.params.parent, sub: this.$route.params.sub, third: this.$route.params.third}, query:this.routeForm})
                else if(this.$route.params && this.$route.params.sub)
                    this.$router.push({name: 'second-category', params: { parent: this.$route.params.parent, sub: this.$route.params.sub}, query:this.routeForm})
                else
                    this.$router.push({name: 'parent-category', params: { parent: this.$route.params.parent}, query:this.routeForm})
                //this.productPreloads()
            },
        },
    }
</script>

