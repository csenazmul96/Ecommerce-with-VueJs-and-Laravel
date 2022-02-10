<template>
    <!-- ============================
        START SEARCH SECTION
    ============================== -->
    <div class="header_search h_o_dropdown" id="search">
        <div class="header_search_form">
            <div class="header_search_form_inner">
                <input type="text" class="form-control" ref="searchinput" @keyup="searchInSite($event)" v-model="searchString">

                <button class="btn"><i class="lni lni-search-alt"></i></button>
                <span @click.prevent="hideSearchBar">close</span>
            </div>
        </div>
        <p class="search_text" v-if="searchLoading" >Search In progress.... <i class="fa fa-spinner fa-spin"></i></p>
        <p class="search_text" v-if="!showSearchResult && searchString.length == 0" >PLEASE TYPE AT LEAST 3  CHARACTERS FOR SEARCH SUGGESTIONS.</p>
        <p class="search_text" v-if="!showSearchResult && searchString.length > 0 && searchString.length <= 3">PLEASE TYPE AT LEAST {{3 - searchString.length}} MORE CHARACTERS FOR SEARCH SUGGESTIONS.</p>
        <p class="search_text" v-if="!searchLoading && showSearchResult && searchedItems && searchedItems.length == 0">
            Nothing to show in search result
        </p>

        <!-- This portion for only products -->
        <div class="header_search_details" v-if="showSearchResult && (searchedItems && searchedItems.length > 0)">
            <div class="h_s_details_inner_full_width">
                <p>Product Search</p>
                <ul>
                    <li v-for="(searchedItem, searchedItemIndex) in searchedItems" :key="'deskSearchedItemIndex_'+searchedItemIndex">
                        <router-link :to="{ name: 'single-product', params: { parent: searchedItem.slug }}">
                            <img v-lazy="{ src: searchedItem.images.length ? (searchedItem.images[0].thumbs_img) : ('/' + defultImage.value) }" :alt="'Hologram Product ' + searchedItem.style_no" class="img-fluid">
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
                </ul>
                <router-link class="btn_common" :to="{ name: 'search', query: { s: searchString } }">
                    View All Product
                </router-link>
            </div>
        </div>
    </div>
    <!-- ============================
        END SEARCH SECTION
    ============================== -->
</template>
<script>
import defaultStore from '../../defaultStore'
import mixins from "../../../helpers/mixins";
export default {
    mixins: [mixins],
    name:'headerSearchComponent',
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
        hideSearchBar(){
            $(".header_area").removeClass('has_scroll')
            $('.header_search').hide();
        },
        searchInSite(event){
            event = (event) ? event : window.event;
            this.searchString = event.target.value;
            if (event.keyCode === 13 && this.searchString.length >= 3) {
                this.$router.push('/search?s=' + this.searchString);
            }
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
        changeModeEdition(){
            console.log('emit')
        }
    },

}
</script>
