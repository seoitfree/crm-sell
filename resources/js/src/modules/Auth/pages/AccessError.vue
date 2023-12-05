<template>
    <body class="app">
    <Header/>

    <div class="app-wrapper">
        <div class="app-content pt-3 p-md-3 p-lg-4">
            <div class="container-xl">
                <div class="row g-3 mb-4 align-items-center justify-content-between">
                    <div class="col-auto">
                        <h1 class="app-page-title mb-0">AccessError</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <Footer/>
    </body>
</template>

<script>
import {defineComponent} from "vue";

export default defineComponent({
    name: "AccessError"
});
</script>

<style scoped>

</style>
