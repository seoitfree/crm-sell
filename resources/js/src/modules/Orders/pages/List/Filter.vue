<template>
    <div :class="[$style.custom__popup, 'modal fade show']" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Filter</h5>
                    <button v-if="!isLoading" type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closeButton">
                        <span>&times;</span>
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
                            <select multiple class="form-select" name="value" v-model="filter.status">
                                <template v-for="item in statusOptions">
                                    <option :selected="filter.status.includes(item.key)" :value="item.key">{{ item.value }}</option>
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
                            <label for="comfyCategory">Дата Чеку від</label>
                            <input name="order_date_from" type="date" class="form-control" v-model="filter.date_check_from">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="order_date_to">Дата Чеку до</label>
                            <input name="order_date_to" type="date" class="form-control" v-model="filter.date_check_to">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="form-group col-md-6">
                            <label for="vendor_code">Артикул</label>
                            <input name="vendor_code" type="text" class="form-control" v-model="filter.vendor_code">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="goods_name">Товар</label>
                            <input name="goods_name" type="text" class="form-control" v-model="filter.goods_name">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="form-group col-md-6">
                            <label>Постачальник</label>
                            <select class="form-select" name="provider_start" v-model="filter.provider_start">
                                <template v-for="item in providerOptions">
                                    <option :selected="item.key === filter.provider_start" :value="item.key">{{ item.value }}</option>
                                </template>
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Списаний</label>
                            <select class="form-select" name="defect" v-model="filter.defect">
                                <template v-for="item in defectsOptions">
                                    <option :selected="item.key === filter.defect" :value="item.key">{{ item.value }}</option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="form-group col-md-6">
                            <label for="comment">Коментар</label>
                            <input name="comment" type="text" class="form-control" v-model="filter.comment">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="order_number">№ Замовлення</label>
                            <input name="order_number" type="text" class="form-control" v-model="filter.order_number">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="form-group col-md-6">
                            <label for="remainder">Залишок</label>
                            <input type="checkbox" id="remainder" name="deprecated" style="margin-left: 5px;" v-model="filter.remainder" @change="changeDeprecated">
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
                    <button type="button" class="btn btn-primary" data-dismiss="modal" @click="initFilter">Фильтровать</button>
                    <button type="submit" class="btn btn-secondary" @click="clearFilter">Очистить</button>
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
            managersOptions: [] as Option[],
            providerOptions: [] as Option[],
            statusOptions: [] as Option[],
            defectsOptions: [] as Option[],
        }
    },
    created() {
        this.isLoading = true;
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
            this.managersOptions = this.addAll(values[0].data);
            this.defectsOptions = this.addAll(values[1].data);
            this.providerOptions = this.addAll(values[2].data);
            this.statusOptions = this.addAll(values[3].data);

            this.isLoading = false;
        }).catch((e) => {
            console.error(e);
            alert("Ошбка сервера, перегрузите страницу или обратитесь в тех поддержку.");
        });
    },
    methods: {
        addAll(options: Option[]): Option[] {
            options.unshift({key: 'all', value: 'Все'} as Option);
            return options;
        },
        closeButton(): void {
            this.$emit('closeButton');
        },
        initFilter(): void {
            this.$emit('initFilter', this.filter);
        },
        changeDeprecated() {
            this.form.deprecated = !this.form.deprecated;
        },
        clearFilter(): void {
            this.filter = {
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
                remainder: false,
            };
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
