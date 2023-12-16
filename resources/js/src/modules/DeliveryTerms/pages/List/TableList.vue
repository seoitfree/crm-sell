<template>
    <div class="tab-pane fade show active" id="orders-all" role="tabpanel" aria-labelledby="orders-all-tab">
        <div class="app-card app-card-orders-table shadow-sm mb-5">
            <div class="app-card-body">
                <div class="table-responsive">
                    <table class="table app-table-hover mb-0 text-left">
                        <HeadTable
                            :headColumns="headColumns"
                            :sortData="sortData"
                            @clickSort="refreshRecords"
                        />

                        <tbody>
                            <tr>
                               <td class="cell">#15346</td>
                            </tr>
                        </tbody>

                    </table>
                </div><!--//table-responsive-->

            </div><!--//app-card-body-->
        </div><!--//app-card-->

<!--        <Pagination-->
<!--            :pagination="pagination"-->
<!--            @clickPagination="refreshRecords"-->
<!--        />-->
    </div>
</template>

<script lang="ts">

import {defineAsyncComponent, defineComponent} from "vue";
import {PaginationType} from "../../../../common/components/Table/Type/PaginationType";
import {SortData} from "../../../../common/components/Table/Type/SortData";
import {HeadColumn} from "../../../../common/components/Table/Type/HeadColumn";

const HeadTable = defineAsyncComponent(() => import("../../../../common/components/Table/HeadTable.vue"));
const Pagination = defineAsyncComponent(() => import('../../../../common/components/Table/Pagination.vue'));

export default defineComponent({
    name: "TableList",
    components: {
        HeadTable,
        Pagination
    },
    data() {
        return {
            headColumns: [
                {name: 'test', translate: 'test', sort: true}
            ] as HeadColumn[],
            pagination: {} as PaginationType,
            sortData: {
                sortField: 'number',
                sortDir: 'desc',
            } as SortData,
            filter: {}
        }
    },
    created() {

    },
    methods: {
        refreshRecords(): void {

        }
    }
});

</script>

<style scoped>

</style>
