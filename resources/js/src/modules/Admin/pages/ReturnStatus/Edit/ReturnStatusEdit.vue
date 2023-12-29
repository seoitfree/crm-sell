<template>
    <body class="app">
    <Header/>
    <div class="app-wrapper">
        <div class="app-content pt-3 p-md-3 p-lg-4">
            <div class="container-xl">
                <div class="row g-3 mb-4 align-items-center justify-content-between">
                    <div class="col-auto">
                        <h1 class="app-page-title mb-0">Новий статус поверненя</h1>
                    </div>
                </div>

                <ReturnStatusForm :userId="userId"/>
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
const ReturnStatusForm = defineAsyncComponent(() => import('@/js/src/modules/Admin/pages/ReturnStatus/Edit/ReturnStatusForm.vue'));

export default defineComponent({
    name: "ReturnStatusEdit",
    components: {
        Header,
        Footer,
        ReturnStatusForm
    },
    data() {
        return {
            userId: this.$route.params?.recordId ?? '',
        }
    }
});
</script>

<style scoped>

</style>
