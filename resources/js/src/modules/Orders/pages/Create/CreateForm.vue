<template>
    <div>
        <form tag="form" ref="myForm">
            <div class="form-group row">
                <div class="form-group col-md-6">
                    <label for="numberOrder">№ Замовлення</label>
                    <input name="numberOrder" type="text" class="form-control"   v-model="form.numberOrder">
                    <span v-if="'numberOrder' in errors" role="alert" class="text-danger" >{{ errors.numberOrder }}</span>
                </div>
                <div class="form-group col-md-6">
                    <label for="providerStart">Постачальник (потенційний)</label>
                    <select class="form-select" name="providerStart" v-model="form.providerStart" >
                        <template v-for="provider in providerOptions">
                            <option :value="provider.key">{{ provider.value }}</option>
                        </template>
                    </select>
                    <span v-if="'providerStart' in errors" role="alert" class="text-danger" >{{ errors.providerStart }}</span>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group col-md-6">
                    <label for="vendorCode">Артикул</label>
                    <input name="vendorCode"  class="form-control" type="text" v-model="form.vendorCode" @input="searchByVendorCode">
                    <template v-if="vendorCodeList.length > 0">
                        <select class="form-select" v-model="vendorCodeValueComputed" size="5">
                            <template v-for="item in vendorCodeList">
                                <option :value="item.id">{{ item.vendor_code }}</option>
                            </template>
                        </select>
                    </template>
                </div>
                <div class="form-group col-md-6">
                    <label for="=goodsName">Товар</label>
                    <input name="goodsName" class="form-control" type="text" v-model="form.goodsName" @input="searchByGoodsName">
                    <template v-if="goodsNameList.length > 0">
                        <select class="form-select" v-model="goodsNameValueComputed" size="5">
                            <template v-for="item in goodsNameList">
                                <option :value="item.id">{{ item.name }}</option>
                            </template>
                        </select>
                    </template>
                </div>
                <span v-if="'goodsId' in errors" role="alert" class="text-danger" >{{ errors.goodsId }}</span>
            </div>

            <div class="form-group row">
                <div class="form-group col-md-6">
                    <label for="managerComment">Коментар/уточнення постачальника</label>
                    <textarea name="managerComment"  class="form-control"  style="height: 5rem !important;" v-model="form.managerComment" cols="30" rows="10"></textarea>
                    <span v-if="'managerComment' in errors" role="alert" class="text-danger" >{{ errors.managerComment }}</span>
                </div>
                <div class="form-group col-md-6">
                    <label for="=sellPrice">Ціна</label>
                    <input name="sellPrice" type="number" class="form-control"   v-model="form.sellPrice">
                    <span v-if="'sellPrice' in errors" role="alert" class="text-danger" >{{ errors.sellPrice }}</span>
                </div>
            </div>


            <div class="form-group row">
                <div class="form-group col-md-6">
                    <label for="amountInOrder">К-ть в замовленні</label>
                    <input name="amountInOrder" type="number" class="form-control" v-model="form.amountInOrder">
                    <span v-if="'amountInOrder' in errors" role="alert" class="text-danger" >{{ errors.amountInOrder }}</span>
                </div>

                <div class="form-group col-md-6">
                    <label for="comfyCode">Код номенклатуры</label>
                    <input name="comfyCode" type="text" class="form-control"   v-model="form.comfyCode">
                    <span v-if="'comfyCode' in errors" role="alert" class="text-danger" >{{ errors.comfyCode }}</span>
                </div>
            </div>


            <div class="form-group row">
                <div class="form-group col-md-6">
                    <label for="comfyGoodsName">Наименование продукта</label>
                    <input name="comfyGoodsName" type="text" class="form-control"   v-model="form.comfyGoodsName">
                    <span v-if="'comfyGoodsName' in errors" role="alert" class="text-danger" >{{ errors.comfyGoodsName }}</span>
                </div>

                <div class="form-group col-md-6">
                    <label for="comfyBrand">Наименование бренда</label>
                    <input name="comfyBrand" type="text" class="form-control"  v-model="form.comfyBrand">
                    <span v-if="'comfyBrand' in errors" role="alert" class="text-danger" >{{ errors.comfyGoodsName }}</span>
                </div>
            </div>


            <div class="form-group row">
                <div class="form-group col-md-6">
                    <label for="comfyCategory">Наименование категории</label>
                    <input name="comfyCategory" type="text" class="form-control" v-model="form.comfyCategory">
                    <span v-if="'comfyCategory' in errors" role="alert" class="text-danger" >{{ errors.comfyCategory }}</span>
                </div>

                <div class="form-group col-md-6">
                    <label for="comfyPrice">Цена/значение</label>
                    <input name="comfyPrice" type="number" min="0" class="form-control" v-model="form.comfyPrice">
                    <span v-if="'comfyPrice' in errors" role="alert" class="text-danger" >{{ errors.comfyPrice }}</span>
                </div>
            </div>


            <div v-if="!isLoading" class="text-center mt-2">
                <button type="submit" class="btn app-btn-primary" @click="onSubmit" >Додати</button>
            </div>
            <div v-if="isLoading" class="d-flex justify-content-center mt-2">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </form>
    </div>
</template>

<script lang="ts">
import {defineComponent} from "vue";
import * as yup from "yup";
import {Option} from "../../../../common/Types/Option";
import axios from "axios";

interface OptionGoods {
    id: string;
    vendor_code: string;
    name: string;
}

export default defineComponent({
    name: "CreateForm",
    data() {
        return {
            isLoading: false,
            vendorCodeList: [] as OptionGoods[],
            goodsNameList: [] as OptionGoods[],
            inputTimerVendorCode: 0,
            inputTimerGoodsName: 0,
            form: {
                numberOrder: '',
                vendorCode: '',
                vendorCodeValue: '',
                goodsName: '',
                goodsNameValue: '',
                sellPrice: '',
                managerComment: '',
                providerStart: '',
                amountInOrder: 0,
                comfyGoodsName: '',
                comfyCode: '',
                comfyBrand: '',
                comfyCategory: '',
                comfyPrice: '',
                goodsId: '',
            },
            validation: yup.object().shape({
                numberOrder: yup.string().trim().required('Поле обзательное').max(50, 'Максимальное количество символов 50'),
                sellPrice:  yup.number()
                    .transform((value) => (isNaN(value) ? undefined : value))
                    .required('Поле обзательное')
                    .positive('Поле должно быть больше 0')
                    .test('is-decimal', 'Должно иметь два знака после запятой', (value) => {
                        return (value) ? /^\d+(\.\d{1,2})?$/.test(value.toString()) : true;
                    }),
                managerComment: yup.string().trim().required('Поле обзательное').max(1000, 'Максимальное количество символов 1000'),
                goodsId: yup.string().trim().required('Не был выбран коректно товар.'),
                providerStart: yup.string().trim().required('Поле обзательное'),
                amountInOrder: yup.number()
                    .transform((value) => (isNaN(value) ? undefined : value))
                    .required('Поле обзательное')
                    .positive('Поле должно быть больше 0')
                    .integer('Поле должно быть целочисельное'),
                comfyCode: yup.string().trim().required('Поле обзательное').max(50, 'Максимальное количество символов 50'),
                comfyGoodsName: yup.string().trim().required('Поле обзательное').max(150, 'Максимальное количество символов 150'),
                comfyBrand: yup.string().trim().required('Поле обзательное').max(50, 'Максимальное количество символов 50'),
                comfyCategory: yup.string().trim().required('Поле обзательное').max(100, 'Максимальное количество символов 100'),
                comfyPrice: yup.number()
                    .transform((value) => (isNaN(value) ? undefined : value))
                    .required('Поле обзательное')
                    .positive('Поле должно быть больше 0')
                    .test('is-decimal', 'Должно иметь два знака после запятой', (value) => {
                        return (value) ? /^\d+(\.\d{1,2})?$/.test(value.toString()) : true;
                    }),
            }),
            errors: {},
            providerOptions: [] as Option[],
        }
    },
    computed: {
        vendorCodeValueComputed: {
            get() {
                return this.form.vendorCodeValue;
            },
            set(value): void {
                console.log("vendorCodeValueComputed");
                this.form.vendorCodeValue = value;
                const goods = this.vendorCodeList.find((item: OptionGoods) => {
                    return item.id = value;
                });
                this.form.goodsId = goods.id;
                console.log(this.form.goodsId);
                this.form.goodsName = goods.name;
                console.log(this.form.goodsId);
            }
        },
        goodsNameValueComputed: {
            get() {
                return this.form.goodsNameValue;
            },
            set(value): void {
                this.form.goodsNameValue = value;
                const goods = this.vendorCodeList.find((item: OptionGoods) => {
                    return item.id = value;
                });
                this.form.goodsId = goods.id;
                this.form.vendorCode = goods.vendor_code;
            }
        }
    },
    async created() {
        this.isLoading = true;
        try {
            await this.getProviders();
            this.isLoading = false;
        } catch (e) {
            alert("Ошбка сервера, перегрузите страницу или обратитесь в тех поддержку.");
            this.isLoading = false;
        }
    },
    methods: {
        onSubmit(e) {
            e.preventDefault();
            this.errors = {};
            const schema = this.validation;
            schema.validate(this.form, { abortEarly: false })
                .then(valid => this.create())
                .catch(errors => {
                    const errorsObject = {};
                    errors.inner.forEach(err => {
                        errorsObject[err.path] = err.message;
                    });
                    this.errors = errorsObject;
                });
        },
        async getProviders(): Promise<void> {
            return axios.get('/api/v1/providers/all').then((response) => {
                if (response.status !== 200) {
                    throw Error("Error");
                }
                this.providerOptions = response.data.data;
            });
        },
        async create(actions): void {
            this.isLoading = true;
            axios.post('/api/v1/order', this.form).then(async (response) => {
                if (response.status === 422) {
                    response.data.errors.forEach((item) => {
                        this.errors[item.field] = item.message;
                    })
                    this.isLoading = false;
                    return;
                }
                if (response.status === 201) {
                    this.$router.push({name: 'orders'});
                } else {
                    alert(response.data.errors[0]);
                    this.isLoading = false;
                }
            }).catch((error) => {
                console.error(error)
                alert("Ошбка сервера, перегрузите страницу или обратитесь в тех поддержку.");
                this.isLoading = false;
            });
        },
        searchByVendorCode(event) {
            clearTimeout(this.inputTimerVendorCode);
            this.inputTimerVendorCode = setTimeout(() => {
                if (event.target.value !== '') {
                    axios.get('/api/v1/goods/vendor_code/' + event.target.value).then((response) => {
                        console.log(response);
                        if (response.status !== 200) {
                            throw Error("Error");
                        }
                        console.log(response.data.data);
                        this.vendorCodeList = response.data.data.records;
                        this.goodsNameList = [];
                    }).catch((error) => {
                        console.error(error);
                        alert("Ошбка сервера, перегрузите страницу или обратитесь в тех поддержку.");
                    })
                }

            }, 500);
        },
        searchByGoodsName(event) {
            clearTimeout(this.inputTimerGoodsName);
            this.inputTimerGoodsName = setTimeout(() => {
                if (event.target.value !== '') {
                    axios.get('/api/v1/goods/goods_name/' + event.target.value).then((response) => {
                        if (response.status !== 200) {
                            throw Error("Error");
                        }
                        this.goodsNameList = response.data.data.records;
                        this.vendorCodeList = [];
                    }).catch((error) => {
                        console.error(error);
                        alert("Ошбка сервера, перегрузите страницу или обратитесь в тех поддержку.");
                    })
                }
            }, 500);
        },
    }
});
</script>

<style scoped>

</style>
