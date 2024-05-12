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
                                    <a class="btn app-btn-secondary">CSV</a>
                                </div>

                                <div class="col-auto">
                                    <a class="btn app-btn-secondary" @click="switchFilter()">Filter</a>
                                </div>
                            </div>
                        </div><!--//table-utilities-->
                    </div>
                </div><!--//row-->

                <OrdersTable :key="ordersTableKey"
                             :filterParams="filterParams"
                />
                <template v-if="isFilter">
                    <Filter
                        :filterParams="filterParams"
                        @closeButton="switchFilter"
                        @initFilter="initFilter"
                    />
                </template>
            </div>
        </div>
    </div>

    <Footer/>
    </body>
</template>

<script lang="ts">

import {defineAsyncComponent, defineComponent} from "vue";
import {FilterType} from "./Types/FilterType";
const Header = defineAsyncComponent(() => import('@/js/src/common/components/Header/Header.vue'));
const Footer = defineAsyncComponent(() => import('@/js/src/common/components/Footer/Footer.vue'));
const OrdersTable = defineAsyncComponent(() => import('@/js/src/modules/Orders/pages/List/OrdersTable.vue'));
const Filter = defineAsyncComponent(() => import('@/js/src/modules/Orders/pages/List/Filter.vue'));

export default defineComponent({
    name: "OrdersList",
    components: {
        Header,
        Footer,
        OrdersTable,
        Filter
    },
    data() {
        return {
            ordersTableKey: 0,
            filterParams: {
                order_date_from: '',
                order_date_to: '',
                vendor_code: '',
                goods_name: '',
                sell_price_from: 0,
                sell_price_to: 0,
                amount_in_order_paid_from: 0,
                amount_in_order_paid_to: 0,
                cost_from: 0,
                cost_to: 0,
                defect: '',
                provider_start: '',
                manager: '',
                status: '',
                comfy_code: '',
                comfy_goods_name: '',
                comfy_brand: '',
                comfy_category: '',
                comfy_price_from: 0,
                comfy_price_to: 0
            } as FilterType,
            isFilter: false,
        };
    },
    methods: {
        CSV(): void {

        },
        switchFilter(): void {
            this.isFilter = !this.isFilter;
        },
        initFilter(filterData: FilterType): void {
            this.filterParams = filterData;
        }
    }
});

</script>

<style scoped>

</style>
