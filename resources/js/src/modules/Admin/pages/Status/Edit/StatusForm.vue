<template>
    <div>
        <Form tag="form" ref="myForm" @submit="onSubmit" :validation-schema="validation">
            <div class="form-group row">
                <div class="form-group col-md-6">
                    <label for="firstName">Название</label>
                    <Field name="name" type="text" class="form-control" placeholder="Название"  v-model="form.name"></Field>
                    <ErrorMessage name="firstName" class="text-danger" />
                </div>
                <div v-show="recordId === ''" class="form-group col-md-6" >
                    <label for="alias">Alias</label>
                    <Field name="alias" type="text" class="form-control" placeholder="Alias"  v-model="form.alias"></Field>
                    <ErrorMessage name="lastName" class="text-danger" />
                </div>
            </div>

            <div class="modal-body">
                <div class="form-group row">
                    <div class="form-group col-md-6">
                        <label for="email"><strong>Тип</strong></label>
                        <Field class="form-select" name="type" v-model="form.type" as="select">
                            <template v-for="type in typeOptions">
                                <option :value="type.key">{{ type.value }}</option>
                            </template>
                        </Field>
                        <ErrorMessage name="type" class="text-danger" />
                    </div>
                </div>
            </div>

            <div v-if="!isLoading" class="text-center mt-2">
                <button type="submit" class="btn app-btn-primary"> {{recordId === '' ? 'Создать' : 'Редактировать'}}</button>
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
import {ErrorMessage, Field, Form} from "vee-validate";
import * as yup from "yup";
import axios from "axios";
import {StatusEnum} from "../enum/StatusEnum";

export default defineComponent({
    name: "StatusForm",
    components: {
        Form,
        Field,
        ErrorMessage
    },
    props: {
        recordId: {
            type: String,
            required: true,
        },
        type: {
            type: String,
            required: true,
        },
    },
    data() {
        return {
            isLoading: false,
            form: {
                id: '',
                name: '',
                alias: '',
                type: '',
            },
            typeOptions: [
                {key: StatusEnum.DEFECT, value: "Повернення"},
                {key: StatusEnum.STATUS, value: "Замовлення"}
            ],
            validation: yup.object().shape({
                name:  yup.string().required('Поле обзательное').min(2, 'Минимальное количество символов 2'),
                alias: yup.string().required('Поле обзательное').min(2, 'Минимальное количество символов 2'),
                type: yup.string().required('Поле обзательное'),
            }),
        }
    },
    created() {
        console.log(this.type);
        if (this.recordId !== '') {
            this.getStatus();
        }
        this.form.type = this.type
    },
    methods: {
        getStatus(): void {
            axios.get(`/api/v1/status/${this.type}/${this.recordId}`).then((response) => {
                console.log(response);
                if (response.status === 200) {
                    const status = response.data.data.status;
                    this.form.name = status.name;
                    this.form.alias = status.alias;
                    this.form.type = status.type;
                }
            }).catch((error) => {
                console.error(error);
                alert("Ошбка сервера, перегрузите страницу или обратитесь в тех поддержку.");
            })
        },
        onSubmit(values, actions): void {
            if (this.recordId === '') {
                this.create(actions);
            } else {
                this.update(actions);
            }
        },
        create(actions): void {
            this.isLoading = true;
            axios.post('/api/v1/status', this.form).then(async (response) => {
                this.responseHandle(response, actions, 201);
            }).catch((error) => {
                this.errorHandle(error);
            });
        },
        update(actions): void {
            this.isLoading = true;
            this.form.id = this.recordId;
            axios.put('/api/v1/status', this.form).then(async (response) => {
                this.responseHandle(response, actions, 200);
            }).catch((error) => {
                this.errorHandle(error);
            });
        },
        errorHandle(error): void {
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
                this.$router.push({path: `/status/${this.form.type}`});
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
