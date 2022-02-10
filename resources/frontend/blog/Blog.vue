<template>
    <div>
        <section class="breadcrumbs_area">
            <div class="hl_blog_heading" v-if="banner && banner.list">
                <img  :src="banner.list.image" alt="" class="img-fluid">
            </div>
            <div class="container-fluid bredcrumbs_inner">
                <div class="row">
                    <div class="col-sm-12">
                        <nav aria-label="breadcrumb ">
                            <ol class="breadcrumb ">
                                <li class="breadcrumb-item"><router-link target="_blank" :to="{ name: 'home'}" > <i class="fa fa-home" aria-hidden="true"></i></router-link>
                                </li>
                                <template v-if="currentCategory">
                                    <li class="breadcrumb-item " aria-current="page"><router-link target="_blank" :to="{ name: 'blogs'}" > Blog</router-link></li>
                                    <li class="breadcrumb-item active"> {{currentCategory.name}}</li>
                                </template>
                                <template v-else>
                                    <li class="breadcrumb-item active" aria-current="page">Blog</li>
                                </template>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </section>
        <section class="blog_post_area blog_index_area">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-10 order-lg-2">
                        <div class="blog_post_left">
                            <template v-if="allblogs && allblogs.data &&allblogs.data.length> 0">
                                <template v-for="(blog,index) in allblogs.data" >
                                    <!-- <div v-if="index==0" :key="'blog'+index">
                                        <router-link target="_blank" :to="{ name: 'blog-single', params: { slug: blog.slug }}"><img :src="blog.image" :alt="blog.image_alt" class="img-fluid w-100"></router-link>
                                        <h2><router-link target="_blank" :to="{ name: 'blog-single', params: { slug: blog.slug }}">{{blog.title}}</router-link> </h2>
                                        <p>By <span>shophologram</span> on <span>{{ blog.created_at | DateFormat("MMMM DD, YYYY")}}</span></p>
                                        <div v-if="blog.description" >{{blog.description}}</div>
                                        <div class="read_more">
                                            <router-link target="_blank" :to="{ name: 'blog-single', params: { slug: blog.slug }}">Read More <i class="lni lni-arrow-right"></i></router-link>
                                            <div class="blog_comment">
                                                <router-link target="_blank" :to="{ name: 'blog-single', params: { slug: blog.slug }}" v-if="blog.comments && blog.comments.length > 0"><i class="fas fa-comments"></i> {{blog.comments.length}}</router-link>
                                                <router-link target="_blank" :to="{ name: 'blog-single', params: { slug: blog.slug }}" v-else><i class="fas fa-comments"></i> 0</router-link>
                                                <router-link target="_blank" :to="{ name: 'blog-single', params: { slug: blog.slug }}"><i class="fa fa-eye" aria-hidden="true"></i> {{blog.view}}</router-link>
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="row blog_index_wrapper" :key="'blog'+index">
                                        <div class="b_i_wrap">
                                            <div class="blog_index_img">
                                                <div class="img">

                                                <router-link target="_blank" :to="{ name: 'blog-single', params: { slug: blog.slug }}"><img :src="blog.thumb" :alt="blog.image_alt" class="img-fluid"></router-link>
                                                </div>
                                            </div>
                                            <div class="blog_index_content">
                                                <p class="b_author">By <span>shophologram</span> on <span> {{ blog.created_at | DateFormat("MMMM DD, YYYY")}}</span></p>
                                                <h1> <router-link target="_blank" :to="{ name: 'blog-single', params: { slug: blog.slug }}">{{blog.title}}</router-link></h1>
                                                <div v-if="blog.description" >{{blog.description}}</div>
                                                <div class="read_more">
                                                    <router-link target="_blank" :to="{ name: 'blog-single', params: { slug: blog.slug }}">Read More <i class="lni lni-arrow-right"></i></router-link>
                                                    <div class="blog_comment">
                                                        <router-link target="_blank" :to="{ name: 'blog-single', params: { slug: blog.slug }}" v-if="blog.comments && blog.comments.length > 0"><i class="fas fa-comments"></i> {{blog.comments.length}}</router-link>
                                                        <router-link target="_blank" :to="{ name: 'blog-single', params: { slug: blog.slug }}" v-else><i class="fas fa-comments"></i> 0</router-link>
                                                        <router-link target="_blank" :to="{ name: 'blog-single', params: { slug: blog.slug }}"><i class="fa fa-eye" aria-hidden="true"></i> {{blog.view}}</router-link>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </template>
                            <template v-if="allblogs && allblogs.data &&allblogs.data.length === 0">
                                <div class="row text-center mt_20"><div class="col-md-12">No Result Found</div></div>
                            </template>
                            <productPagination v-if="paginatedBlogs" :paginateData="paginatedBlogs"   @paginate="chagePage" ></productPagination>
                        </div>
                    </div>
                    <Sidebar @searchKey="searchBlog"></Sidebar>
                </div>
            </div>
        </section>
    </div>

</template>
<script>
import Sidebar from  './Sidebar'
import mixin from '../helpers/mixins'
import validate from 'validate.js'
import productPagination from './Pagination'
export default {
    name:'blog',
    mixins: [mixin],
    data(){
        return{
            allblogs:[],
            paginatedBlogs:null,
            currentCategory:null,
            banner:[],
        }
    },
    components:{
        Sidebar:Sidebar,
        productPagination: productPagination,
    },
    metaInfo(){
        return {
            title: "Blog - Hologram",
        }
    },
    mounted(){
        this.loadBlog();
    },
    watch: {
        $route(to,from){
            this.loadBlog();
        }
    },
    created() {
        axios.get('/api/v1/get/blog/banner').then((response) => this.banner = response.data);
    },
    methods:{
        async loadBlog(){
            let url = '/api/v1/blogs';
            let slug ='';
            let s = '';
            let pagenumber = '';
            if(this.$route.params.slug){ slug = '/'+this.$route.params.slug; }
            if(this.$route.query.s){ s = '?s='+this.$route.query.s; }
            if(this.$route.query.page){ pagenumber = '?page='+this.$route.query.page }
            if(this.$route.query.page && this.$route.query.s){s = '?s='+this.$route.query.s;pagenumber = '&page='+this.$route.query.page}
            url = url+slug+s+pagenumber;
            axios.get(url)
                .then((response) => {
                    this.allblogs = response.data.blogs
                    this.paginatedBlogs = response.data.blogs
                    this.currentCategory = response.data.category;
                })
                .catch((error) => {

                })
                .finally(() => {
                });
        },
        searchBlog(data){
            console.log(data)
        },

        chagePage(page = 1){
            let url = '/blogs';
            let slug ='';
            let s = '';
            let pagenumber = '';
            if(this.$route.params.slug){ slug = '/'+this.$route.params.slug;  }
            if(this.$route.query.s){ s = '?s='+this.$route.query.s; }
            if(page){ pagenumber = '?page='+page }
            if(page && this.$route.query.s){s = '?s='+this.$route.query.s; pagenumber = '&page='+page}
            url = url+slug+s+pagenumber;
            this.$router.push(url).catch(()=>{});
        },
    },
}
</script>
