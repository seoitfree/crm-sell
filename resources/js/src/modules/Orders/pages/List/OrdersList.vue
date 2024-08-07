<template>
    <body class="app">
    <Header/>
    <div class="app-wrapper">
        <div class="app-content pt-3 p-md-3 p-lg-4">
            <div class="container-xl">
                <div class="row g-3 mb-4 align-items-center justify-content-between">
                    <div class="col-auto">
                        <h1 class="app-page-title mb-0">Замовлення поставки</h1>
                    </div>

                    <div class="col-auto">
                        <div class="page-utilities">
                            <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
                                <div class="col-auto">
                                    <router-link class="btn app-btn-secondary" :to="`/order/add`">
                                        Додати
                                    </router-link>
                                </div>

                                <div class="col-auto">
                                    <a class="btn app-btn-secondary" @click="switchFilter()">Filter</a>
                                </div>
                            </div>
                        </div><!--//table-utilities-->
                    </div>
                </div><!--//row-->

                <div v-if="isLoading" class="d-flex justify-content-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                <OrdersTable
                    v-else
                    :key="ordersTableKey"
                    :sortDataProp="sortData"
                    :filterParams="filterParams"
                    :paginationProp="pagination"
                    :recordsProp="records"
                    @clickSort="clickSort"
                    @refreshRecords="refreshRecords"
                />
                <Filter
                    v-if="isFilter"
                    :filterParams="filterParams"
                    @closeButton="switchFilter"
                    @initFilter="initFilter"
                />
            </div>
        </div>
    </div>

    <Footer/>
    </body>
</template>

<script lang="ts">

import {defineAsyncComponent, defineComponent} from "vue";
import {FilterType} from "./Types/FilterType";
import axios from "axios";
import {OrderType} from "./components/Type/OrderType";
import {SortData} from "../../../../common/components/Table/Type/SortData";
import pagination from "../../../../common/components/Table/mixins/Pagination";

const Header = defineAsyncComponent(() => import('@/js/src/common/components/Header/Header.vue'));
const Footer = defineAsyncComponent(() => import('@/js/src/common/components/Footer/Footer.vue'));
const OrdersTable = defineAsyncComponent(() => import('@/js/src/modules/Orders/pages/List/OrdersTable.vue'));
const Filter = defineAsyncComponent(() => import('@/js/src/modules/Orders/pages/List/Filter.vue'));

export default defineComponent({
    name: "OrdersList",
    mixins: [pagination],
    components: {
        Header,
        Footer,
        OrdersTable,
        Filter
    },
    data() {
        return {
            isLoading: false,
            ordersTableKey: 0,
            filterParams: {
                order_date_from: '',
                order_date_to: '',
                vendor_code: '',
                goods_name: '',
                defect: '',
                provider_start: '',
                manager: '',
                status: [],
                date_check_from: '',
                date_check_to: '',
                comment: '',
                order_number: '',
                remainder: false,
            } as FilterType,
            isFilter: false,
            sortData: {
                sortField: 'created_at',
                sortDir: 'desc',
            } as SortData,
            records: [] as OrderType[],
        };
    },
    created() {
        this.getData();
    },
    methods: {
        getData(): void {
            this.isLoading = true;
            const params =  {
                pageNumber: this.pagination.pages.current_page,
                sortDir: this.sortData.sortDir,
                sortField: this.sortData.sortField,
                filterParams: this.filterParams
            };
            axios.post('/api/v1/orders', params, {
                headers: {'Content-Type': 'application/json'}
            }).then((response) => {
                if (response.status === 200) {
                    const result = response.data.data;
                    this.pagination = result.pagination;
                    this.records = result.records;
                } else {
                    alert("Ошбка сервера, перегрузите страницу или обратитесь в тех поддержку.");
                }
                this.isLoading = false;
            }).catch(() => {
                this.isLoading = false;
            });
        },
        clickSort(sortData: SortData): void {
            this.sortData = sortData;
            this.getData();
        },
        refreshRecords(page: number): void {
            this.pagination.pages.current_page = page;
            this.getData();
        },
        switchFilter(): void {
            this.isFilter = !this.isFilter;
        },
        initFilter(filterData: FilterType): void {
            this.filterParams = filterData;
            this.getData();
        }
    }
});

</script>

<style scoped>

</style>
