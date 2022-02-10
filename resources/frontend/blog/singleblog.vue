<template>
    <section>
        <div class="hl_blog_area">
            <div class="hl_blog_heading">
                <img src="/images/blog/banner-image.png" alt="" class="img-fluid">
                <h1>Blog</h1>
            </div>
            <div class="hl_blog_content">
                <div class="container blog_container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="hl_bredcrumbs">
                                <ul class="breadcrumb">
                                    <li><a href="#">Home</a></li>
                                    <li>Blog</li>
                                    <li>A Guide to using gel polish</li>
                                </ul>
                            </div>
                            <div class="blog_inner">
                                <h2>A Guide to using gel polish</h2>
                                <p class="fwb">HERE'S A GUIDE TO USING GEL POLISH AT HOME. ITS SO EASY THAT YOU COULD DO IT ON YOUR OWN</p>
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Adipisci ea cupiditate qui quas ratione commodi quo illum. Repudiandae facere cumque inventore voluptates ducimus. Rem, ad!</p>
                                <img src="/images/blog-image-square.png" alt="">
                                <ol>
                                    <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rerum, molestiae.</li>
                                    <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rerum, molestiae.</li>
                                    <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rerum, molestiae.</li>
                                    <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rerum, molestiae.</li>
                                    <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rerum, molestiae.</li>
                                    <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rerum, molestiae.</li>
                                    <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rerum, molestiae.</li>
                                    <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rerum, molestiae.</li>
                                    <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rerum, molestiae.</li>
                                    <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rerum, molestiae.</li>
                                    <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rerum, molestiae.</li>
                                    <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rerum, molestiae.</li>
                                    <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rerum, molestiae.</li>
                                    <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rerum, molestiae.</li>
                                    <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rerum, molestiae.</li>
                                    <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rerum, molestiae.</li>
                                    <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rerum, molestiae.</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
            bloginfo:[],
            comments:[],
            tags:[],
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
    components:{
        Sidebar:Sidebar,
        VueEditor
    },
    async beforeCreate() {

    },
    mounted() {
        this.productPreloads();
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
                    this.bloginfo = responsce.data.data.blog
                    this.tags = responsce.data.data.tags
                    this.comments = responsce.data.data.comments
                    this.nextblog = responsce.data.data.nextblog
                    this.previousblog = responsce.data.data.previousblog
                })
                .catch((err)=>{
                    // console.log(err)
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
