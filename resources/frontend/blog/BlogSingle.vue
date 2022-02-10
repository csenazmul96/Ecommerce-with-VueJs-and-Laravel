<template>
    <div>
        <section class="breadcrumbs_area">
            <div class="hl_blog_heading">
                <img v-if="banner && banner.single" :src="banner.single.image" alt="" class="img-fluid">
            </div>
            <div class="container-fluid  bredcrumbs_inner">
                <div class="row">
                    <div class="col-sm-12">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb ">
                                <li class="breadcrumb-item"><router-link :to="{ name: 'home'}" > <i class="fa fa-home" aria-hidden="true"></i></router-link>
                                </li>
                                <li class="breadcrumb-item" aria-current="page"><router-link :to="{ name: 'blogs'}" > Blog</router-link></li>
                                <template v-if="bloginfo && bloginfo.categories">
                                    <li class="breadcrumb-item"><router-link :to="'/blogs/'+bloginfo.categories.slug"> {{bloginfo.categories.name | capitalize}}</router-link></li>
                                    <li class="breadcrumb-item active" aria-current="page" v-if="bloginfo && bloginfo.title">{{bloginfo.title | capitalize}}</li>
                                </template>
                                <template v-else>
                                    <li class="breadcrumb-item active" aria-current="page" v-if="bloginfo && bloginfo.title">{{bloginfo.title | capitalize}}</li>
                                </template>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </section>
        <section class="blog_post_area blog_index_area blog_single_page">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-10 order-lg-2">
                        <div class="blog_post_left" v-if="bloginfo">
                            <h1 class="mt_0">{{bloginfo.title}}</h1>
                            <p>By <span>shophologram</span> on <span>{{ bloginfo.created_at | DateFormat("MMMM DD, YYYY")}}</span></p>
                            <img  v-if="bloginfo && bloginfo.image" :src="bloginfo.image" class="img-fluid w-100 mt_0" :alt="bloginfo.image_alt">
                            <div class="blog_comment">
                                <a href="#"><i class="fas fa-comments"></i> {{comments ? comments.length : null}}</a>
                                <a href="#"><i class="fa fa-eye" aria-hidden="true"></i> {{bloginfo.view}}</a>
                            </div>
                            <div v-if="bloginfo.description" v-html="bloginfo.description"></div>

                            <div class="posts_tags" v-if="tags && tags.length>0">
                                <router-link class="bg-info text-light mr_15" v-for="(tag, index) in tags" :key="'tag_'+index" :to="'/blogs?s='+tag" > {{'#'+tag}} </router-link>
                            </div>
                            <hr>

                            <!-- <div class="blog_pagination">
                                <router-link v-if="this.nextblog !=null" class="prev_post" :to="{ name: 'blog-single', params: { slug: nextblog.slug }}">
                                    <p><i class="fas fa-long-arrow-alt-left"></i> PREVIOUS POST</p>
                                    <h2>{{nextblog.title}}</h2>
                                </router-link>
                                <router-link v-if="this.previousblog !=null" class="prev_post next_post" :to="{ name: 'blog-single', params: { slug: previousblog.slug }}">
                                    <p> NEXT POST <i class="fas fa-long-arrow-alt-right"></i> </p>
                                    <h2>{{previousblog.title}}</h2>
                                </router-link>
                            </div> -->
                            <div class="comment_box">
                                <h1>Leave a Comment</h1>
                                <div class="all_comment" v-if="comments && comments.length > 0">
                                    <template v-if="comments && comments.length > 0">
                                        <div class="all_comment_inner" v-for="(parentcomment,index) in comments" :key="'parent_'+index">
                                            <div class="parent_comment">
                                                <h2 v-if="parentcomment.name !=null">{{parentcomment.name}}</h2>
                                                <p v-if="parentcomment.created_at !=null" class="time">{{ parentcomment.created_at | DateFormat("MMM D,YYYY")}}</p>
                                                <div class="comment_text" v-html="parentcomment.comment"> </div>
                                                <a href="#test1" @click="showForm(parentcomment.id,0)"><i class="fa fa-reply" aria-hidden="true"></i> Reply</a>
                                            </div>
                                            <template v-if="parentcomment && parentcomment.replay.length > 0">
                                                <div class="reply_comment" v-for="(replay,index) in parentcomment.replay" :key="'replay_'+index">
                                                    <h2 v-if="replay.name !=null">{{replay.name}}</h2>
                                                    <p class="time">{{ replay.created_at | DateFormat("MMM D,YYYY")}}</p>
                                                    <div class="comment_text" v-html="replay.comment"> </div>
                                                </div>
                                            </template>
                                        </div>
                                        <br>
                                    </template>

                                </div>
                                <template v-if="showCommentForm">
                                    <div class="form-row" id="test1">
                                        <div class="form-group col-md-6">
                                            <label>Name</label>
                                            <input type="name" class="form-control" placeholder="Name" v-model="form.name">
                                            <span class="text-danger" v-if="formError && formError.name">{{formError.name[0]}}</span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Email</label>
                                            <input type="email" class="form-control" placeholder="Email" v-model="form.email">
                                            <span class="text-danger" v-if="formError && formError.email">{{formError.email[0]}}</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <vue-editor v-model="form.comment"></vue-editor>
                                        <span class="text-danger" v-if="formError && formError.comment">{{formError.comment[0]}}</span>
                                    </div>
                                    <div class="blog_submit">
                                        <button class="btn" @click="submitComment()">Submit</button>
                                    </div>
                                </template>
                                <a href="javascript:void(0)" v-else class="btn_common width_200p d_flex_center" @click="showForm(0,0)">Create Comment</a>
                            </div>
                        </div>
                    </div>
                    <Sidebar></Sidebar>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
import Sidebar from  './Sidebar'
import Axios from 'axios';
import mixins from '../helpers/mixins'
import { VueEditor } from "vue2-editor";
export default {
    name:'blog-single',
    mixins: [mixins],
    data(){
        return {
            bloginfo:null,
            comments:[],
            tags:[],
            banner:[],
            nextblog:[],
            previousblog:[],
            showCommentForm:false,
            form:{
                "blog_id":null,
                "name":null,
                "email":null,
                "comment":null,
                "comment_parent_id": 0,
                "comment_replay_id":0,
            },
            formError:[],
        }
    },
    metaInfo(){
        return {
            title: this.bloginfo ? this.bloginfo.meta_title : null
        }
    },
    components:{
        Sidebar:Sidebar,
        VueEditor
    },
    async beforeCreate() {

    },

    created() {
        axios.get('/api/v1/get/blog/banner').then((response) => this.banner = response.data);
    },
    filters: {
        capitalize: function (value) {
            if (!value) return ''
            return value.toLowerCase();
            // return value.charAt(0).toLowerCase() + value.slice(1)
        }
    },
    mounted() {
        this.productPreloads();
        let recaptchaScript = document.createElement('script')
        recaptchaScript.setAttribute('src', 'https://cdnjs.cloudflare.com/ajax/libs/froala-editor/3.1.1/js/froala_editor.pkgd.min.js')
        document.head.appendChild(recaptchaScript)
    },
    watch: {
        $route(to,from){
            this.productPreloads();
        },
    },
    methods:{
        async productPreloads(){
            let slug = this.$route.params.slug
            axios.get('/api/v1/blog-details/'+slug)
                .then((responsce)=>{
                    if(responsce.data === 'not found'){
                        this.$router.push({name:'home'});
                    }else {
                        this.bloginfo = responsce.data.data.blog
                        this.tags = responsce.data.data.tags
                        this.comments = responsce.data.data.comments
                        this.nextblog = responsce.data.data.nextblog
                        this.previousblog = responsce.data.data.previousblog
                    }
                })
                .catch((err)=>{
                    console.log(err)
                })

        },
        setDefault(){
            this.form = {
                "blog_id":null,
                "name":null,
                "email":null,
                "comment":null,
                "comment_parent_id": 0,
                "comment_replay_id":0,
            }
        },
        showForm(parent,repalyparent){
            this.setDefault()
            this.form.comment_parent_id = parent
            this.form.comment_replay_id = repalyparent
            this.showCommentForm = true;
        },
        async submitComment(){
            this.form.blog_id = this.bloginfo.id
            axios.post('/api/v1/blog/comment-post',this.form)
                .then((responsce)=>{
                    if(responsce.data.success == true){
                        this.comments = responsce.data.comments;
                        this.setDefault();
                        this.showCommentForm = false;
                        this.formError=[];
                    }
                    if(responsce.data.success == false){
                        this.formError = responsce.data.errors
                    }

                })
                .catch((err)=>{
                    console.log(err)
                })

        },
    }
}
</script>
