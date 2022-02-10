<template>
    <div class="header_nav">
        <ul v-if="categories">
            <template v-for="(category, i) in categories">
                <template v-if="category.subCategories.length || category.banners.length">
                    <li data-toggle="collapse_slide" :class="{active:$route.params.parent === category.slug}" :data-target="'#'+category.slug"><a href="JavaScript:void(0);">{{ category.name }}</a>
                    </li>
                    <div class="submenu" :id="category.slug">
                        <div class="container">
                            <div class="row">
                                <div class="col-3" v-if="category.subCategories.length">
                                    <div class="submenu_list">
                                        <h2>
                                            <router-link :to="{ name: 'parent-category', params: { parent: category.slug }}">{{category.name | capitalize}}</router-link>
                                        </h2>
                                        <ul>
                                            <li v-for="(sub, j) in category.subCategories" :key="'sub_cat'+j">
                                                <router-link :to="{ name: 'second-category', params: { parent: category.slug, sub:sub.slug }}">{{sub.name | capitalize}}
                                                </router-link>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <template v-if="category.banners.length">
                                    <div class="col-3" v-for="(banner, l) in category.banners" :key="'banner_'+l">
                                        <div class="submenu_list with_image">
                                            <router-link :to="{ name: 'second-category', params: { parent: category.slug, sub:banner.link }}" ><img :src="banner.image" alt="" class="img-fluid"></router-link>
                                            <h2> <router-link :to="{ name: 'second-category', params: { parent: category.slug, sub:banner.link }}">{{ banner.name }}</router-link></h2>
                                            <p>{{ banner.description }}</p>
                                        </div>
                                    </div>
                                </template>

                            </div>
                        </div>
                    </div>
                </template>
                <template v-else>
                    <li :class="{active:$route.params.parent === category.slug}"><router-link :to="{ name: 'parent-category', params: { parent: category.slug }}">{{ category.name | capitalize }}</router-link></li>
                </template>
            </template>
            <li :class="{active:$route.name === 'blogs'}"><router-link :to="{ name: 'blogs' }">Blog</router-link></li>
        </ul>
    </div>
</template>
<script>
    import defaultStore from '../../defaultStore'
    export default {
        name:'headerMenuComponent',
        data() {
            return {
                categoryList:[],
            }
        },
        beforeCreate() {
            if (!(this.$store && this.$store.state && this.$store.state['defaultStore'])) {
                this.$store.registerModule("defaultStore", defaultStore);
            }
        },
        mounted(){
            // this.hideAllSubmenu();
            this.menuPreloads();
        },
        filters: {
            capitalize: function (value) {
                if (!value) return ''
                value = value.toString()
                return value.replace(/(\w)(\w*)/g, function(g0,g1,g2){return g1.toUpperCase() + g2.toLowerCase();})
            },
        },
        computed: {
            categories:{
                get: function () {
                    return this.$store.getters['defaultStore/getCategories']
                },
                set: function (categories = []) {
                    this.$store.commit('defaultStore/setCategories', categories);
                }
            },
        },

        methods: {
            hideAllSubmenu(){
                $('.h_o_dropdown').each(function() {
                    var dropdown4 = $(this);
                    dropdown4.hide();
                });
            },
            menuPreloads(){
                // this.$store.dispatch('defaultStore/defaultCategories');
            },
        },
    }
</script>
