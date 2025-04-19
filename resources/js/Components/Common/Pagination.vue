<script setup>
    import { ref, onMounted, watch } from 'vue'
    const { filters, showFilters } = defineProps(['filters','showFilters'])
    const page_no = ref([])

    onMounted(() => {
        setPagination();
    })

    watch(
        () => filters.page_no,
        () => {
            setPagination();
        }
    )

    function setPagination() {
        var pages = [];
        if(filters.page_no == 1){
            pages.push(1);    
            pages.push(2);
            if(filters.max_page > 2) pages.push(3);    
        } else {
            if(filters.max_page == filters.page_no && filters.max_page > 2){
                pages.push(filters.page_no - 2);
            }
            pages.push(filters.page_no - 1);    
            pages.push(filters.page_no);
            if(filters.max_page != filters.page_no){
                pages.push(filters.page_no + 1);
            }
        }
        page_no.value = pages
    }
    
</script>

<template>
    <div class="row">
        <div class="col-6" >
            <div class="total-count" v-if="filters.max_page > 0">Showing <span>{{filters.max_per_page*(filters.page_no-1) + 1}} - {{filters.max_per_page*filters.page_no < filters.total ? filters.max_per_page*filters.page_no : filters.total}}</span> of <span>{{filters.total}}</span></div>
        </div>
        <div class="col-6" style="text-align: right;">
            <button class="btn fl-btn" @click="$emit('toggleFilters')" :class="{'open' : filters.show}" v-if="showFilters">Filter</button>
            <ul class="pagination" v-if="filters.max_page > 1">
                <li class="page-item">
                    <a class="page-link" href="javascript:;" @click="$emit('SetPage',1)"> << </a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="javascript:;" @click="$emit('SetPage',filters.page_no - 1)"> < </a>
                </li>
                <li class="page-item" v-for="page_no in page_no">
                    <a class="page-link" href="javascript:;" @click="$emit('SetPage',page_no)" :class="{'active' : page_no == filters.page_no}">{{page_no}}</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="javascript:;" @click="$emit('SetPage',filters.page_no + 1)"> > </a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="javascript:;" @click="$emit('SetPage',filters.max_page)"> >> </a>
                </li>
            </ul>
        </div>
    </div>
</template>