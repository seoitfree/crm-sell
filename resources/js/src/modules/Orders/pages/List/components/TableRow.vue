<template>
    <tr>
        <td class="cell">{{ item.manager }} </td>
        <td class="cell">{{ getLocalDateTime(item.order_date) }}</td>
        <td class="cell">
            <TextInlineEdit :value="item.order_number"
                            :entityId="item.id"
                            :urlEdit="`order`"
                            :max="50"
                            :required="true"
                            @update="updateInline"
            />
        </td>
        <td class="cell">
            <TextInlineEdit :value="item.vendor_code"
                            :entityId="item.id"
                            :max="50"
                            :urlEdit="`order`"
                            :required="true"
                            @update="updateInline"
            />
        </td>
        <td class="cell">
            <TextInlineEdit :value="item.goods_name"
                            :entityId="item.id"
                            :max="150"
                            :urlEdit="`order`"
                            :required="true"
                            @update="updateInline"
            />
        </td>
        <td class="cell">
            <TextAreaInlineEdit :value="item.manager_comment"
                                :entityId="item.id"
                                :max="1000"
                                :urlEdit="`order`"
                                :required="true"
                                @update="updateInline"
            />
        </td>
        <td class="cell">
            <DecimalInlineEdit :value="item.sell_price"
                               :entityId="item.id"
                               :urlEdit="`order`"
                               @update="updateInline"
            />
        </td>
        <td class="cell">
            <StatusInlineEdit :value="item.status"
                              :selected="item.status_alias"
                              :entityId="item.id"
                              @update="updateOption"
            />
        </td>
        <td class="cell">
            <IntegerInlineEdit :value="item.amount_in_order_paid"
                               :entityId="item.id"
                               :urlEdit="`order`"
                               @update="updateInline"
            />
        </td>
        <td class="cell">
            <DecimalInlineEdit :value="item.cost"
                               :entityId="item.id"
                               :urlEdit="`order`"
                               @update="updateInline"
            />
        </td>
        <td class="cell">
            <div class="row g-2 align-items-center">
                <div class="col-auto">
                    <a class="btn-sm app-btn-secondary" href="#" @click="addShipments(item.id)"> Приход </a>
                </div>
                <div class="col-auto">
                    <a class="btn-sm app-btn-secondary" href="#" @click="showShipments(item.id)"> Історія </a>
                </div>
            </div>
        </td>
        <td class="cell">{{ item.shipments_amount }}</td>
        <td class="cell">{{ item.remainder }}</td>
        <td class="cell">
            <ProviderInlineEdit :value="item.provider_start"
                                :selected="item.provider_start_id"
                                :entityId="item.id"
                                @update="updateOption"
            />
        </td>
        <td class="cell">
            <DateInlineEdite :value="item.date_check"
                             :entityId="item.id"
                             :urlEdit="`order`"
                             @update="updateInline"
            />
        </td>

        <td class="cell">
            <TextAreaInlineEdit :value="item.comment"
                                :entityId="item.id"
                                :max="1000"
                                :urlEdit="`order`"
                                :required="true"
                                @update="updateInline"
            />
        </td>

        <td class="cell">
            <DefectInlineEdit :value="item.defect"
                              :selected="item.defect_alias"
                              :entityId="item.id"
                              @update="updateOption"
            />
        </td>

        <td class="cell">
            <TextInlineEdit :value="item.comfy_code"
                            :entityId="item.id"
                            :max="50"
                            :urlEdit="`order`"
                            :required="true"
                            @update="updateInline"
            />
        </td>
        <td class="cell">
            <TextInlineEdit :value="item.comfy_goods_name"
                            :entityId="item.id"
                            :max="150"
                            :urlEdit="`order`"
                            :required="true"
                            @update="updateInline"
            />
        </td>
        <td class="cell">
            <TextInlineEdit :value="item.comfy_brand"
                            :entityId="item.id"
                            :max="50"
                            :urlEdit="`order`"
                            :required="true"
                            @update="updateInline"
            />
        </td>
        <td class="cell">
            <TextInlineEdit :value="item.comfy_brand"
                            :entityId="item.id"
                            :max="100"
                            :urlEdit="`order`"
                            :required="true"
                            @update="updateInline"
            />
        </td>
        <td class="cell">
            <DecimalInlineEdit :value="item.comfy_price"
                               :entityId="item.id"
                               :urlEdit="`order`"
                               @update="updateInline"
            />
        </td>

        <td class="cell">
            <router-link class="btn-sm app-btn-secondary" :to="`/delivery-order/edit/${item.id}`">
                Edit
            </router-link>
        </td>
    </tr>
</template>

<script lang="ts">
import {defineComponent, defineAsyncComponent, PropType} from "vue";
import {InlineEdit, InlineOptionEdit} from "./InlineEdit/Types/InlineEdit";
import {OrderType} from "./Type/OrderType";
import {getLocalDateTime} from "../../../../../common/helpers/DateTime";

const TextInlineEdit = defineAsyncComponent(() => import('@/js/src/modules/Orders/pages/List/components/InlineEdit/TextInlineEdit.vue'));
const TextAreaInlineEdit = defineAsyncComponent(() => import('@/js/src/modules/Orders/pages/List/components/InlineEdit/TextAreaInlineEdit.vue'));
const DecimalInlineEdit = defineAsyncComponent(() => import('@/js/src/modules/Orders/pages/List/components/InlineEdit/DecimalInlineEdit.vue'));
const IntegerInlineEdit = defineAsyncComponent(() => import('@/js/src/modules/Orders/pages/List/components/InlineEdit/IntegerInlineEdit.vue'));
const StatusInlineEdit = defineAsyncComponent(() => import('@/js/src/modules/Orders/pages/List/components/InlineEdit/StatusInlineEdit.vue'));
const ProviderInlineEdit = defineAsyncComponent(() => import('@/js/src/modules/Orders/pages/List/components/InlineEdit/ProviderInlineEdit.vue'));
const DefectInlineEdit = defineAsyncComponent(() => import('@/js/src/modules/Orders/pages/List/components/InlineEdit/DefectInlineEdit.vue'));
const DateInlineEdite = defineAsyncComponent(() => import('@/js/src/modules/Orders/pages/List/components/InlineEdit/DateInlineEdite.vue'));


export default defineComponent({
    name: "TableRow",
    components: {
        TextInlineEdit,
        TextAreaInlineEdit,
        DecimalInlineEdit,
        IntegerInlineEdit,
        StatusInlineEdit,
        ProviderInlineEdit,
        DefectInlineEdit,
        DateInlineEdite
    },
    props: {
        value: {
            type: Object as PropType<OrderType>,
            required: true,
        },
    },
    data() {
        return {
            item: {} as OrderType
        };
    },
    mounted() {
        this.item = this.value;
        console.log(this.item.id);
    },
    methods: {
        getLocalDateTime(date: string): string {
            return getLocalDateTime(date);
        },
        addShipments(id) {
            this.$emit('addShipments', id);
        },
        showShipments(id) {
            this.$emit('showShipments', id);
        },
        updateInline(dto: InlineEdit): void {
            this.item[dto.field] = dto.value;
            this.$emit('updateInline', dto);
        },
        updateOption(dto: InlineOptionEdit): void {
            this.item[dto.field] = dto.label;
            if (dto.field === 'defect') {
                this.item[`${dto.field}_id`] = dto.value;
            } else {
                this.item[`${dto.field}_alias`] = dto.value;
            }
        }
    }
});

</script>

<style scoped>

</style>
