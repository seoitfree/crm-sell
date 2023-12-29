<template>
    <div>
        <Form tag="form" ref="myForm" @submit="onSubmit" :validation-schema="validation">
            <div class="form-group row">
                <div class="form-group col-md-6">
                    <label for="firstName">Название</label>
                    <Field name="name" type="text" class="form-control" placeholder="Название"  v-model="form.name"></Field>
                    <ErrorMessage name="firstName" class="text-danger" />
                </div>
                <div class="form-group col-md-6">
                    <label for="alias">Alias</label>
                    <Field name="alias" type="text" class="form-control" placeholder="Alias"  v-model="form.alias"></Field>
                    <ErrorMessage name="lastName" class="text-danger" />
                </div>
            </div>

            <div v-if="!isLoading" class="text-center mt-2">
                <button type="submit" class="btn app-btn-primary">Создать</button>
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
import * as yup from "yup";
import { Form, Field, ErrorMessage } from 'vee-validate';

import axios from "axios";

export default defineComponent({
    name: "OrderStatusForm",
    components: {
        Form,
        Field,
        ErrorMessage
    },
    props: {
        userId: {
            type: String,
            required: true,
        },
    },
    data() {
        return {
            isLoading: false,
            form: {
                name: '',
                alias: '',
            },
            validation: yup.object().shape({
                name:  yup.string().required('Поле обзательное').min(2, 'Минимальное количество символов 2'),
                alias: yup.string().required('Поле обзательное').min(2, 'Минимальное количество символов 2'),
            }),
        }
    },
    created() {
        if (this.userId !== '') {
            this.getStatus();
        }
    },
    methods: {
        getStatus(): void {
            axios.get('/api/v1/order-status/' + this.userId).then((response) => {
                if (response.status === 200) {
                    const status = response.data.data.status;
                    this.form.name = status.name;
                    this.form.alias = status.alias;
                }
            }).catch((error) => {
                console.error(error);
                alert("Ошбка сервера, перегрузите страницу или обратитесь в тех поддержку.");
            })
        },
        onSubmit(values, actions): void {
            if (this.userId === '') {
                this.create(actions);
            } else {
                this.update(actions);
            }
        },
        create(actions): void {
            this.isLoading = true;
            axios.post('/api/v1/order-status', this.form).then(async (response) => {
                this.responseHandle(response, actions, 201);
            }).catch((error) => {
                this.errorHandle(error);
            });
        },
        update(actions): void {
            this.isLoading = true;
            axios.put('/api/v1/order-status', this.form).then(async (response) => {
                this.responseHandle(response, actions, 200);
            }).catch((error) => {
                this.errorHandle(error);
            });
        },
        errorHandle(error): void {
            console.error(error)
            alert("Ошбка сервера, перегрузите страницу или обратитесь в тех поддержку.");
            this.isLoading = false;
        },
        responseHandle(response, actions, successStatus): void {
            if (response.status === 422) {
                response.data.errors.forEach((item) => {
                    actions.setFieldError(item.field, item.message);
                })
                this.isLoading = false;
                return;
            }
            if (response.status === successStatus) {
                this.$router.push({name: 'order-status'});
            } else {
                alert(response.data.errors[0]);
                this.isLoading = false;
            }
        }
    }
});
</script>

<style scoped>

</style>
