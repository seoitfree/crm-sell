<template>
    <thead>
        <tr>
            <template v-for="column in headColumns">
                <th class="cell" :style="[column.sort ? 'cursor: pointer' : '']" @click="sort(column.name)">
                    <div :style="column.hasOwnProperty('width') ? column.width : ``">
                        {{ column.translate }}
                        <img v-if="column.sort" class="logo-icon me-2" :src="sortIcon(column.name)" alt="logo">
                    </div>
                </th>
            </template>
        </tr>
    </thead>
</template>

<script lang="ts">

import {defineComponent, PropType, ref} from 'vue';
import {SortData} from "./Type/SortData";
import {HeadColumn} from "./Type/HeadColumn";
import sort from '@/assets/images/sort/sort.png';
import sortDown from '@/assets/images/sort/sort-down.png';
import sortUp from '@/assets/images/sort/sort-up.png';

export default defineComponent({ //down up
    name: "HeadTable",
    props: {
        headColumns: {
            type: Array as PropType<HeadColumn[]>,
            required: true,
        },
        sortData: {
            type: Object as PropType<SortData>,
            required: true,
        }
    },
    data() {
        return {
            sortImagePath: sort,
            sortDownImagePath: sortDown ,
            sortUpImagePath: sortUp,
        }
    },
    methods: {
        sortIcon(fieldName: string): string {
            if (fieldName !== this.sortData.sortField) {
                return this.sortImagePath;
            }
            return this.sortData.sortDir === 'desc' ? this.sortDownImagePath : this.sortUpImagePath;
        },
        sort(fieldName: string): string {

        }
    }
});

</script>

<style scoped>

</style>
