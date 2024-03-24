<template>
    <div>
        <Form tag="form" ref="myForm" @submit="onSubmit" :validation-schema="validation">
            <div class="form-group row">
                <div class="form-group col-md-6">
                    <label for="numberOrder">№ Замовлення</label>
                    <Field name="numberOrder" type="text" class="form-control"   v-model="form.numberOrder"></Field>
                    <ErrorMessage name="numberOrder" class="text-danger" />
                </div>
                <div class="form-group col-md-6">
                    <label for="providerStart">Постачальник (потенційний)</label>
                    <Field class="form-select" name="providerStart" v-model="form.providerStart" as="select">
                        <template v-for="provider in providerOptions">
                            <option :value="provider.key">{{ provider.value }}</option>
                        </template>
                    </Field>
                    <ErrorMessage name="providerStart" class="text-danger" />
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group col-md-6">
                    <label for="vendorCode">Артикул</label>
                    <Field name="vendorCode" type="text" class="form-control"   v-model="form.vendorCode"></Field>
                    <ErrorMessage name="vendorCode" class="text-danger" />
                </div>
                <div class="form-group col-md-6">
                    <label for="=sellPrice">Ціна</label>
                    <Field name="sellPrice" type="number" class="form-control"   v-model="form.sellPrice"></Field>
                    <ErrorMessage name="sellPrice" class="text-danger" />
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group col-md-6">
                    <label for="managerComment">Коментар/уточнення постачальника</label>
                    <Field name="managerComment" as="textarea"  class="form-control"  style="height: 5rem !important;" v-model="form.managerComment" cols="30" rows="10"></Field>
                    <ErrorMessage name="managerComment" class="text-danger" />
                </div>
                <div class="form-group col-md-6">
                    <label for="=goodsName">Товар</label>
                    <Field name="goodsName" type="text" class="form-control"   v-model="form.goodsName"></Field>
                    <ErrorMessage name="goodsName" class="text-danger" />
                </div>
            </div>


            <div class="form-group row">
                <div class="form-group col-md-6">
                    <label for="amountInOrder">К-ть в замовленні</label>
                    <Field name="amountInOrder" type="number" class="form-control"   v-model="form.amountInOrder"></Field>
                    <ErrorMessage name="amountInOrder" class="text-danger" />
                </div>

                <div class="form-group col-md-6">
                    <label for="comfyCode">Код номенклатуры</label>
                    <Field name="comfyCode" type="text" class="form-control"   v-model="form.comfyCode"></Field>
                    <ErrorMessage name="comfyCode" class="text-danger" />
                </div>
            </div>


            <div class="form-group row">
                <div class="form-group col-md-6">
                    <label for="comfyGoodsName">Наименование продукта</label>
                    <Field name="comfyGoodsName" type="text" class="form-control"   v-model="form.comfyGoodsName"></Field>
                    <ErrorMessage name="comfyGoodsName" class="text-danger" />
                </div>

                <div class="form-group col-md-6">
                    <label for="comfyBrand">Наименование бренда</label>
                    <Field name="comfyBrand" type="text" class="form-control"   v-model="form.comfyBrand"></Field>
                    <ErrorMessage name="comfyBrand" class="text-danger" />
                </div>
            </div>


            <div class="form-group row">
                <div class="form-group col-md-6">
                    <label for="comfyCategory">Наименование категории</label>
                    <Field name="comfyCategory" type="text" class="form-control"   v-model="form.comfyCategory"></Field>
                    <ErrorMessage name="comfyCategory" class="text-danger" />
                </div>

                <div class="form-group col-md-6">
                    <label for="comfyPrice">Цена/значение</label>
                    <Field name="comfyPrice" type="number" min="0" class="form-control"   v-model="form.comfyPrice"></Field>
                    <ErrorMessage name="comfyPrice" class="text-danger" />
                </div>
            </div>


            <div v-if="!isLoading" class="text-center mt-2">
                <button type="submit" class="btn app-btn-primary">Додати</button>
            </div>
            <div v-if="isLoading" class="d-flex justify-content-center mt-2">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </Form>
    </div>
</template>

<script lang="ts">
import {defineComponent} from "vue";
import { Form, Field, ErrorMessage, useForm } from 'vee-validate';
import * as yup from "yup";
import {Option} from "../../../../common/Types/Option";
import axios from "axios";

export default defineComponent({
    name: "CreateForm",
    components: {
        Form,
        Field,
        ErrorMessage
    },
    data() {
        return {
            isLoading: false,
            form: {
                numberOrder: '',
                vendorCode: '',
                sellPrice: '',
                managerComment: '',
                goodsName: '',
                providerStart: '',
                amountInOrder: 0,
                comfyCode: '',
                comfyGoodsName: '',
                comfyBrand: '',
                comfyCategory: '',
                comfyPrice: '',
            },
            validation: yup.object().shape({
                numberOrder: yup.string().trim().required('Поле обзательное').max(50, 'Максимальное количество символов 50'),
                vendorCode: yup.string().trim().required('Поле обзательное').max(50, 'Максимальное количество символов 100'),
                sellPrice:  yup.number()
                    .transform((value) => (isNaN(value) ? undefined : value))
                    .required('Поле обзательное')
                    .positive('Поле должно быть больше 0')
                    .test('is-decimal', 'Должно иметь два знака после запятой', (value) => {
                        if (value) {
                            return /^\d+(\.\d{1,2})?$/.test(value.toString());
                        }
                        return true;
                    }),
                managerComment: yup.string().trim().required('Поле обзательное').max(1000, 'Максимальное количество символов 1000'),
                goodsName: yup.string().trim().required('Поле обзательное').max(150, 'Максимальное количество символов 150'),
                providerStart: yup.string().trim().required('Поле обзательное'),
                amountInOrder: yup.number()
                    .transform((value) => (isNaN(value) ? undefined : value))
                    .required('Поле обзательное').positive('Поле должно быть больше 0').integer('Поле должно быть целочисельное'),
                comfyCode: yup.string().trim().required('Поле обзательное').max(50, 'Максимальное количество символов 50'),
                comfyGoodsName: yup.string().trim().required('Поле обзательное').max(150, 'Максимальное количество символов 150'),
                comfyBrand: yup.string().trim().required('Поле обзательное').max(50, 'Максимальное количество символов 50'),
                comfyCategory: yup.string().trim().required('Поле обзательное').max(100, 'Максимальное количество символов 100'),
                comfyPrice: yup.number()
                    .transform((value) => (isNaN(value) ? undefined : value))
                    .required('Поле обзательное')
                    .positive('Поле должно быть больше 0')
                    .test('is-decimal', 'Должно иметь два знака после запятой', (value) => {
                        if (value) {
                            return /^\d+(\.\d{1,2})?$/.test(value.toString());
                        }
                        return true;
                    }),
            }),
            providerOptions: [] as Option[],
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
        onSubmit(values, actions) {
            this.create(actions);
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
                        actions.setFieldError(item.field, item.message);
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
        }
    }
});
</script>

<style scoped>

</style>
