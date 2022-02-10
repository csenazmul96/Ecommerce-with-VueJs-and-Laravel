<template>
    <div class="show_from_left" id="msearch">
        <div class="header_menu_inner">
            <div class="header_search">
                <div class="header_search_form">
                    <div class="header_search_form_inner">
                        <input type="text" ref="searchinput" class="form-control" @keyup="searchInSite($event)">
                        <button class="btn"><i class="lni lni-search-alt"></i></button>
                        <span @click.prevent="hideSearchBar">close</span>
                    </div>
                </div>
                <p class="search_text" v-if="searchLoading" >Search In progress.... <i class="fa fa-spinner fa-spin"></i></p>
                <p class="search_text" v-if="!showSearchResult && searchString.length == 0" >PLEASE TYPE AT LEAST 3 CHARACTERS FOR SEARCH SUGGESTIONS.</p>
                <p class="search_text" v-if="!showSearchResult && searchString.length > 0 && searchString.length <= 3">PLEASE TYPE AT LEAST {{3 - searchString.length}} MORE CHARACTERS FOR SEARCH SUGGESTIONS.</p>

                <p class="search_text" v-if="!searchLoading && showSearchResult && searchedItems && searchedItems.length == 0">
                    Nothing to show in search result
                </p>

                <div class="h_s_details_inner_full_width" v-if="showSearchResult && (searchedItems && searchedItems.length > 0)">
                    <p>Product Search</p>
                    <ul v-bind:style="{'height': (searchedItems && searchedItems.length > 2) ? '300px': 'auto'}">
                        <template v-for="(searchedItem, searchedItemIndex) in searchedItems">
                        <li :key="'deskSearchedItemIndex_'+searchedItemIndex" v-if="searchedItemIndex < 4">
                            <router-link :to="{ name: 'single-product', params: { parent: searchedItem.slug }}">
                                <img v-lazy="{ src: searchedItem.images.length ? (searchedItem.images[0].thumbs_img) : ('/' + defultImage.value)}" :alt="'Hologram Product ' + searchedItem.style_no" class="img-fluid">
                            </router-link>
                            <div class="a_r_text">
                                <h3>
                                    <router-link :to="{ name: 'single-product', params: { parent: searchedItem.slug }}">
                                        {{searchedItem && searchedItem.name ? searchedItem.name : 'No Specific Name'}}
                                    </router-link>
                                </h3>
                                <p>
                                    <span>USD${{searchedItem.price | round(2)}}</span>
                                </p>
                            </div>
                        </li>
                        </template>
                    </ul>
                    <router-link class="btn_common" :to="{ name: 'search', query: { s: searchString } }">
                        View All Product
                    </router-link>
                </div>
            </div>
        </div>
        <div class="close_h_menu" @click.prevent="mobCloseMenu">
            <span>Close</span>
        </div>
    </div>

</template>
<script>
    import defaultStore from '../../defaultStore'
    import mixins from "../../../helpers/mixins";
    export default {
        mixins: [mixins],
        name:'headerMobileSearchComponent',
        data() {
            return {
                searchString: '',
                showSearchResult: false,
                showCreateUserModal: false,
            }
        },
        beforeCreate() {
            if (!(this.$store && this.$store.state && this.$store.state['defaultStore'])) {
                this.$store.registerModule("defaultStore", defaultStore);
            }
        },
        filters: {
            doubleSlashFilter: function (value) {
                return value.replace("//", "/");
            },
            capitalize: function (value) {
                if (!value) return ''
                value = value.toString()
                return value.replace(/(\w)(\w*)/g, function(g0,g1,g2){return g1.toUpperCase() + g2.toLowerCase();})
            },
            round: function (value, decimals) {
                if(!value) {
                    value = 0;
                }

                if(!decimals) {
                    decimals = 0;
                }
                var value = Number(value);
                value = value.toFixed(decimals);

                return value;
            },
        },
        computed: {
            searchLoading() {
                return this.$store.getters['defaultStore/getSearchLoading']
            },
            defultImage() {
                return this.$store.getters['defaultStore/getDefaultImage']
            },
            searchedItems() {
                return this.$store.getters['defaultStore/getSearchedItems']
            },
            searchedCategories() {
                return this.$store.getters['defaultStore/getSearchedCategories']
            },
        },
        methods: {
            searchInSite(event){
                if (event.keyCode === 13) {
                    this.$router.push('/search?s=' + this.searchString);
                }
                event = (event) ? event : window.event;
                this.searchString = event.target.value;
                let formdata = {
                    search_text: this.searchString,
                }
                if(this.searchString.length >= 3) {
                    this.showSearchResult = true;
                    this.$store.dispatch('defaultStore/searchInSite', formdata);
                    return;
                }
                this.showSearchResult = false
            },
            mobCloseMenu() {
                $('.show_from_left , .show_from_right').removeClass('open_h_menu');
                $('.menu').removeClass('open');
                $('.h_m_left ul li , .h_m_cart ul li').removeClass('active');
                $('body').removeClass('overflow_hidden');
            },
            hideSearchBar(){
                $('.header_search').hide();
            },
        },
    }
</script>
