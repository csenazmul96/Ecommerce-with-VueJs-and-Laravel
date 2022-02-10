<template>
    <div class="pagination_global" v-if="paginateData.total > 0">
        <nav>
            <span>Jump to #</span>
            <input type="text" @keyup.enter="changePage(Number($event.target.value))" :value="paginateData.current_page">
        </nav>
        <nav>
            <span>Page {{ paginateData.current_page }} of {{ pagesNumber.length }}</span>
            <ul class="pagination">
                <li v-if="paginateData.current_page != 1" @click="changePage(Number(paginateData.current_page) - 1)">
                    <a><i class="lni lni-chevron-left"></i></a>
                </li>
                <li v-if="paginateData.current_page != paginateData.last_page" @click="changePage(Number(paginateData.current_page) + 1)">
                    <a><i class="lni lni-chevron-right"></i></a>
                </li>
            </ul>
        </nav>
    </div>

    <!--<nav aria-label="Page navigation example" v-if="paginateData.total > 0">
        <ul class="pagination">
            <li class="page-item" :class="{' disabled': paginateData.current_page == 1}">
                <template v-if="paginateData.current_page == 1">
                    <a class="page-link" href="javascript:void(0)">
                        Previous
                    </a>
                </template>
                <a class="page-link" href="javascript:void(0)" v-else @click="changePage(Number(paginateData.current_page) - 1)">
                    Previous
                </a>
            </li>
            <li class="page-item" v-for="page in pagesNumber" :class="{' active ' : (page == paginateData.current_page) || (pagesNumber.length == 1)}" :key="'pagination-' + page">
                <template v-if="(page == paginateData.current_page) || (pagesNumber.length == 1)">
                    <a class="page-link" href="javascript:void(0)">
                        {{ page }}
                    </a>
                </template>
                <a class="page-link" href="javascript:void(0)" v-else @click="changePage(page)">
                    {{ page }}
                </a>
            </li>
            <li class="page-item " :class="{' disabled': paginateData.current_page == paginateData.last_page}">
                <template v-if="paginateData.current_page ==( paginateData.last_page">
                    <a class="page-link" href="javascript:void0)">
                        Next
                    </a>
                </template>
                <a class="page-link" href="javascript:void(0)" v-else  @click="changePage(Number(paginateData.current_page) + 1)">
                    Next
                </a>
            </li>
        </ul>
    </nav>-->
</template>

<script>
export default {
    name: 'reviewPagination',
    props: {
        paginateData: {
            type: Object,
            required: true
        }
    },
    computed: {
        pagesNumber() {
            var pagesArray = [];

            var start = Math.max(1, this.paginateData.current_page - 1);
            var end = Math.min(this.paginateData.current_page + 1, this.paginateData.last_page);

            if (this.paginateData.current_page == 1) {
                start = 1;
                end = Math.min(3, this.paginateData.last_page);
            }
            if (this.paginateData.current_page == 2) {
                start = 1;
                end = Math.min(3, this.paginateData.last_page);
            }
            if (this.paginateData.current_page >= 2  && this.paginateData.current_page <= this.paginateData.last_page-1) {
                start = Number(this.paginateData.current_page) - 1;
                end = Number(this.paginateData.current_page) + 1;
            }
            if (this.paginateData.current_page == this.paginateData.last_page - 1) {
                start = Math.max((this.paginateData.last_page - 2), 1);
                end = this.paginateData.last_page;
            }
            if (this.paginateData.current_page >= this.paginateData.last_page) {
                start = Math.max((this.paginateData.last_page - 2), 1);
                end = this.paginateData.last_page;
            }
            start = 1;
            end = this.paginateData.last_page;
            for (var i = start; i <= end; i++) {
                pagesArray.push(i);
            }
            return pagesArray;
        },
    },
    methods: {
        changePage(page) {
            this.$emit('paginate', page);
        }
    }
}
</script>
