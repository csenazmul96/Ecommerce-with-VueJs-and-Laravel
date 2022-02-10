<template>
    <!-- ============================
        START MENU SECTION
    ============================== -->
    <div class="show_from_left" id="menu">
        <div class="header_menu_inner mobile_nav">
            <ul>
                <template v-if="categories.length > 0" >
                    <template v-for="(category, categoryIndex) in categories">
                        <template v-if="category.subCategories && category.subCategories.length > 0">
                            <li class="has_child" data-toggle="collapse_m_nav" :data-target="'#mobSubCategory_'+category.id" :key="'mobCategoryIndex_'+categoryIndex" @click.prevent="mobExpandNextMenu($event)">
                                <a href="javascript:void(0)">{{category.name | capitalize}}</a>
                            </li>
                            <div class="show_from_left" :id="'mobSubCategory_'+category.id" :key="'mobSubCategory_'+categoryIndex">
                                <ul class="mobile_submenu">
                                    <li>
                                        <router-link :to="{ name: 'parent-category', params: { parent: category.slug }}">{{category.name | capitalize}}</router-link>
                                        <span class="back" @click.prevent="mobBackToTopMenu($event)">Back</span>
                                    </li>
                                    <template v-for="(subCategory, subCategoryIndex) in category.subCategories">
                                        <template v-if="subCategory.thirdcategory && subCategory.thirdcategory.length > 0">
                                            <li class="has_child"  v-bind:class="{'custom_has_child': subCategoryIndex == 0 }" data-toggle="collapse_m_nav" :data-target="'#mobThirdCategory_'+subCategory.id" :key="'mobSubCategoryIndex_'+subCategoryIndex" @click.prevent="mobExpandNextMenu($event)">
                                                <router-link :to="{ name: 'second-category', params: { parent: category.slug, sub:subCategory.slug }}">{{subCategory.name}}</router-link>

                                            </li>
                                            <div class="show_from_left" :id="'mobThirdCategory_'+subCategory.id" :key="'mobSubCategory_'+subCategoryIndex">
                                                <ul class="mobile_submenu">
                                                    <li v-for="(thirdCategory, thirdCategoryIndex) in subCategory.thirdcategory" :key="'mobThirdCategoryIndex_'+thirdCategoryIndex">
                                                        <router-link :to="{ name: 'third-category', params: { parent: category.slug, sub:subCategory.slug, third:thirdCategory.slug }}">{{thirdCategory.name}}</router-link>
                                                        <span class="back" v-if="thirdCategoryIndex == 0" @click.prevent="mobBackToTopMenu($event)">Back</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </template>
                                        <template v-else>
                                            <li :key="'mobSubCategoryIndex_'+subCategoryIndex">
                                                <router-link :to="{ name: 'second-category', params: { parent: category.slug, sub:subCategory.slug }}">{{subCategory.name | capitalize}}</router-link>

                                            </li>
                                        </template>
                                    </template>
                                </ul>
                            </div>

                        </template>
                    </template>
                </template>
                <li><router-link :to="{ name: 'blogs' }">Blog</router-link></li>
            </ul>
        </div>
        <div class="close_h_menu" @click.prevent="mobCloseMenu">
            <span>Close</span>
        </div>
    </div>

</template>
<script>
    import defaultStore from '../../defaultStore'
    export default {
        name:'headerMobileMenuComponent',
        beforeCreate() {
            if (!(this.$store && this.$store.state && this.$store.state['defaultStore'])) {
                this.$store.registerModule("defaultStore", defaultStore);
            }
        },
        mounted(){
            this.menuPreloads();
        // window.addEventListener('resize',  this.headerCommonMargin);
        },
        created(){
             // setTimeout(() => {
                // var common_margin = $('.main_header_mobile').outerHeight();
                // $('.show_from_left , .show_from_righy').css({
                //     top : `${common_margin}px`
                // });
                // $('.close_h_menu').css({
                //     bottom : `${common_margin}px`
                // });
            // }, 2000);
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
        //     headerCommonMargin() {
        //         setTimeout(() => {
        //             var common_margin = $('.main_header_mobile').outerHeight();
        //             $('.show_from_left , .show_from_righy').css({
        //                 top : `${common_margin}px`,
        //                 'height': `calc(100% - ${common_margin}px)`
        //             });
        //         }, 3000);
        // },
            menuPreloads(){
                // this.$store.dispatch('defaultStore/defaultCategories');
            },
            mobBackToTopMenu(e) {
                $(e.target).closest('.show_from_left').removeClass('open_h_menu');
            },
            mobExpandNextMenu(e) {
                if ($(e.target).hasClass('back') == false) {
                    var mNavId = $(e.target).closest('li').data('target');
                    $(mNavId).addClass('open_h_menu');
                }
            },
            mobCloseMenu() {
                $('.show_from_left , .show_from_right').removeClass('open_h_menu');
                $('.menu').removeClass('open');
                $('.h_m_left ul li , .h_m_cart ul li').removeClass('active');
                $('body').removeClass('overflow_hidden');
            },
        },
    }
</script>
