<template>
    <div :class="[$style.custom__popup, 'modal fade show']" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Filter</h5>
                    <button v-if="!isLoading" type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closeButton">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                    <div class="modal-body" v-if="!isLoading">
                        <div class="form-group row">
                            <div class="form-group col-md-6">
                                <label for="comfyCategory">Менеджер</label>
                                <select class="form-select" name="value" v-model="filter.manager">
                                    <template v-for="item in managersOptions">
                                        <option :selected="item.key === filter.manager" :value="item.key">{{ item.value }}</option>
                                    </template>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="comfyCategory">Статус замовлення</label>
                                <select class="form-select" name="value" v-model="filter.status">
                                    <template v-for="item in statusOptions">
                                        <option :selected="item.key === filter.status" :value="item.key">{{ item.value }}</option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="form-group col-md-6">
                                <label for="comfyCategory">Дата від</label>
                                <input name="order_date_from" type="date" class="form-control" v-model="filter.order_date_from">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="order_date_to">Дата до</label>
                                <input name="order_date_to" type="date" class="form-control" v-model="filter.order_date_to">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="form-group col-md-6">
                                <label for="vendor_code">№ Замовлення</label>
                                <input name="vendor_code" type="text" class="form-control" v-model="filter.vendor_code">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="goods_name">Товар</label>
                                <input name="goods_name" type="text" class="form-control" v-model="filter.goods_name">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="form-group col-md-6">
                                <label for="sell_price_from">Ціна від</label>
                                <input name="sell_price_from" type="number" min="0" class="form-control" v-model="filter.sell_price_from">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="sell_price_to">Ціна до</label>
                                <input name="sell_price_to" type="number" min="0" class="form-control" v-model="filter.sell_price_to">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="form-group col-md-6">
                                <label for="amount_in_order_paid_from">К-ть оплачених від</label>
                                <input name="amount_in_order_paid_from" type="number" min="0" class="form-control" v-model="filter.amount_in_order_paid_from">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="amount_in_order_paid_to">К-ть оплачених до</label>
                                <input name="amount_in_order_paid_to" type="number" min="0" class="form-control" v-model="filter.amount_in_order_paid_to">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="form-group col-md-6">
                                <label for="cost_from">Ціна закупки від</label>
                                <input name="cost_from" type="number" min="0" class="form-control" v-model="filter.cost_from">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="cost_to">Ціна закупки до</label>
                                <input name="cost_to" type="number" min="0" class="form-control" v-model="filter.cost_to">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="form-group col-md-6">
                                <label>Постачальник</label>
                                <select class="form-select" name="value" v-model="filter.provider_start">
                                    <template v-for="item in providerOptions">
                                        <option :selected="item.key === filter.provider_start" :value="item.key">{{ item.value }}</option>
                                    </template>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Списаний</label>
                                <select class="form-select" name="value" v-model="filter.defect">
                                    <template v-for="item in defectsOptions">
                                        <option :selected="item.key === filter.defect" :value="item.key">{{ item.value }}</option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="form-group col-md-6">
                                <label for="comfy_code">Код номенклатуры</label>
                                <input name="comfy_code" type="text" class="form-control" v-model="filter.comfy_code">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="comfy_goods_name">Наименование продукта</label>
                                <input name="comfy_goods_name" type="text" class="form-control" v-model="filter.comfy_goods_name">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="form-group col-md-6">
                                <label for="comfy_brand">Наименование бренда</label>
                                <input name="comfy_brand" type="text" class="form-control" v-model="filter.comfy_brand">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="comfy_category">Наименование категории</label>
                                <input name="comfy_category" type="text" class="form-control" v-model="filter.comfy_category">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="form-group col-md-6">
                                <label for="comfy_price_from">Цена/значение від</label>
                                <input name="comfy_price_from" type="number" min="0" class="form-control" v-model="filter.comfy_price_from">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="comfy_price_to">Цена/значение до</label>
                                <input name="comfy_price_to" type="number" min="0" class="form-control" v-model="filter.comfy_price_to">
                            </div>
                        </div>
                    </div>
                <div class="modal-body" v-if="isLoading">
                    <div  class="d-flex justify-content-center mt-2">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
                    <div v-if="!isLoading" class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="initFilet()">Фильтровать</button>
                        <button type="submit" class="btn btn-primary">Добавить</button>
                    </div>

            </div>
        </div>
    </div>
</template>

<script lang="ts">
import {defineComponent, PropType} from "vue";
import {FilterType} from "./Types/FilterType";
import axios from "axios";
import {Option} from "../../../../common/Types/Option";

export default defineComponent({
    name: "Filter",
    props: {
        filterParams: {
            type: Object as PropType<FilterType>,
            required: true,
        }
    },
    data() {
        return {
            isLoading: false,
            filter: {
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
                comfy_brand: '',
                comfy_category: '',
                comfy_goods_name: '',
                comfy_price_from: 0,
                comfy_price_to: 0,
            } as FilterType,
            managersOptions: [] as Option[],
            providerOptions: [] as Option[],
            statusOptions: [] as Option[],
            defectsOptions: [] as Option[],
        }
    },
    created() {
        this.isLoading = true;
        console.log("Filter");
        this.filter = this.filterParams;
        const providers = axios.get('/api/v1/providers/all').then((response) => {
            if (response.status !== 200) {
                throw Error("Error");
            }
            return response.data;
        });
        const status = axios.get('/api/v1/status/all').then((response) => {
            if (response.status !== 200) {
                throw Error("Error");
            }
            return response.data;
        });
        const defects = axios.get('/api/v1/defects/all').then((response) => {
            if (response.status !== 200) {
                throw Error("Error");
            }
            return response.data;
        });
        const managers = axios.get('/api/v1/users/all').then((response) => {
            if (response.status !== 200) {
                throw Error("Error");
            }
            return response.data;
        });
        Promise.all([managers, defects, providers, status]).then(values => {
            this.managersOptions = values[0].data;
            this.defectsOptions = values[1].data;
            this.providerOptions = values[2].data;
            this.statusOptions = values[3].data;

            console.log(values);
            this.isLoading = false;
        }).catch(() => {
            alert("Ошбка сервера, перегрузите страницу или обратитесь в тех поддержку.");
        });
    },
    methods: {
        closeButton(): void {
            this.$emit('closeButton');
        },
        initFilet(): void {
            this.$emit('initFilet', this.filter);
        }
    }
});
</script>

<style module>
.custom__popup {
    display: block;
    background-color: rgba(0, 0, 0, 0.5);
}
</style>
