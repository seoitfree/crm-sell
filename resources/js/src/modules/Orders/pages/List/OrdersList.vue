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
                                    <router-link class="btn app-btn-secondary" :to="`/delivery-order/create`">
                                        CSV
                                    </router-link>
                                </div>
                            </div>
                        </div><!--//table-utilities-->
                    </div>

                </div><!--//row-->

                <OrdersTable />
            </div>
        </div>
    </div>

    <Footer/>
    </body>
</template>

<script lang="ts">

import {defineAsyncComponent, defineComponent} from "vue";
const Header = defineAsyncComponent(() => import('@/js/src/common/components/Header/Header.vue'));
const Footer = defineAsyncComponent(() => import('@/js/src/common/components/Footer/Footer.vue'));
const OrdersTable = defineAsyncComponent(() => import('@/js/src/modules/Orders/pages/List/OrdersTable.vue'));

export default defineComponent({
    name: "OrdersList",
    components: {
        Header,
        Footer,
        OrdersTable
    },
});

</script>

<style scoped>

</style>
