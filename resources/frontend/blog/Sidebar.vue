<template>
<div class="col-lg-2 order-lg-1">
    <div class="blog_sidebar">
        <div class="blog_search_inner">
            <input type="text" placeholder="Search here" class="form-control" @keyup="blogSearch($event)" v-model="searchString">
            <input type="submit" value="">
        </div>
        <h2>CATEGORIES</h2>
        <ul class="blog_category">
            <template v-if="Contents && Contents.categories && Contents.categories.length > 0">
                <li v-for="(category,index) in Contents.categories" :key="'cat_'+index">
                    <router-link :to="'/blogs/'+category.slug">{{category.name | capitalize}} <span>({{category.total}})</span></router-link>
                </li>
            </template>
        </ul>
        <h2>Popular posts</h2>
        <template v-if="Contents && Contents.topitems && Contents.topitems.length > 0">
            <div class="popular_post_inner" v-for="(post, index) in Contents.topitems" :key="'post_'+index">
                <router-link :to="{ name: 'blog-single', params: { slug: post.slug }}"><img :src="post.thumb" :alt="post.image_alt" width="70px"></router-link>
                <h1><router-link :to="{ name: 'blog-single', params: { slug: post.slug }}">{{post.title | capitalize}}</router-link></h1>
                <p>{{ post.created_at | DateFormat("MMM DD, YYYY")}}</p>
            </div>
        </template>


        <h2 v-if="Contents && Contents.archivePost && Contents.archivePost.length > 0">Recent Posts</h2>
        <ul class="recent_post">
            <template v-if="Contents && Contents.archivePost && Contents.archivePost.length > 0">
                <li v-for="(blog, index) in Contents.archivePost" :key="'recent_'+index">
                    <router-link :to="{ name: 'blog-single', params: { slug: blog.slug }}"><i class="fas fa-file-alt"></i> {{stringCrop(blog.title,20) | capitalize}}</router-link>
                </li>
            </template>
        </ul>
    </div>
</div>
</template>
<script>
export default {
    name:'Sidebar',
    data(){
        return{
            searchString: '',
            Contents:null,
        }
    },
    mounted(){
        this.sidebarContent();
    },
    watch:{
    },
    filters: {
        capitalize: function (value) {
            if (!value) return ''
            return value.toLowerCase();
        }
    },
    methods:{
        sidebarContent(){
            axios.get('/api/v1/blogs/sidebar')
            .then((response) => {
                this.Contents = response.data.content
            })
            .catch((error) => {
            })
            .finally(() => {

            });
        },
        blogSearch(event){
            event = (event) ? event : window.event;
            this.searchString = event.target.value;
            this.$router.push({name:'blogs', query:{s:this.searchString}})
        },
        blogCategory(slug){
            if(slug){
                // this.$router.push('/blogs/'+slug);
            }
        },
        stringCrop(text,maxLength){
            return text.substring(0, maxLength) + "...";
        }

    },
}
</script>
