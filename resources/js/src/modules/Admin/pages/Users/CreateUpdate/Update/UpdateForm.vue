<template>
    <div>
        <Form tag="form" ref="myForm" @submit="onSubmit" :validation-schema="validation">
            <div class="form-group row">
                <div class="form-group col-md-6">
                    <label for="firstName">Имя</label>
                    <Field name="firstName" type="text" class="form-control" placeholder="Имя"  v-model="form.firstName"></Field>
                    <ErrorMessage name="firstName" class="text-danger" />
                </div>
                <div class="form-group col-md-6">
                    <label for="lastName">Фамилия</label>
                    <Field name="lastName" type="text" class="form-control" placeholder="Фамилия"  v-model="form.lastName"></Field>
                    <ErrorMessage name="lastName" class="text-danger" />
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group col-md-6">
                    <label for="email">Email</label>
                    <Field name="email" type="email" class="form-control" placeholder="Email" v-model="form.email"></Field>
                    <ErrorMessage name="email" class="text-danger" />
                </div>

                <div class="form-group col-md-6">
                    <Field name="switchResetPassword" class="form-check-input" type="checkbox" id="switch-reset-password" :value="true" :unchecked-value="false" v-model="form.switchResetPassword"></Field>
                    <label class="form-check-label" for="flexSwitchCheckDefault">Сбросить пароль пользователю</label>
                </div>
            </div>


            <div class="form-group row" v-if="form.switchResetPassword">
                <div class="form-group col-md-6">
                    <label for="password">Пароль</label>
                    <Field name="password" type="password" ref="password" class="form-control" placeholder="Пароль" v-model="form.password"></Field>
                    <ErrorMessage name="password" class="text-danger" />
                </div>
                <div class="form-group col-md-6">
                    <label for="confirmPassword">Подвердите пароль</label>
                    <Field name="confirmPassword" type="password" class="form-control" placeholder="Подвердите пароль" v-model="form.confirmPassword"></Field>
                    <ErrorMessage name="confirmPassword" class="text-danger" />
                </div>
            </div>

            <div class="form-group row" v-if="hasRoles([rolesEnum.ADMIN])">
                <div class="form-group col-md-6">
                    <label for="email">Статус</label>
                    <Field class="form-select" name="roles" v-model="form.status" as="select">
                        <template v-for="status in statusEnum">
                            <option :value="status.key">{{ status.value }}</option>
                        </template>
                    </Field>
                    <ErrorMessage name="roles" class="text-danger" />
                </div>
            </div>

            <div v-if="!isLoading" class="text-center mt-2">
                <button type="submit" class="btn app-btn-primary">Редактировать</button>
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

import permissionMixin from "../../../../../Auth/mixin/permissionMixin";
import {defineComponent} from "vue";
import {ErrorMessage, Field, Form} from "vee-validate";
import * as yup from "yup";
import axios from "axios";

export default defineComponent({
    name: "UpdateForm",
    mixins: [permissionMixin],
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
                switchResetPassword: false,
                entityId: '',
                firstName: '',
                lastName: '',
                email: '',
                password: '',
                confirmPassword: '',
                status: 0,
            },
            statusEnum: [
                {key: '0', value: 'Активный'},
                {key: '1', value: 'Уволен'}
            ],
            validation: yup.object().shape({
                switchResetPassword: yup.boolean(),
                firstName:  yup.string().required('Поле обзательное').min(2, 'Минимальное количество символов 2'),
                lastName: yup.string().required('Поле обзательное').min(2, 'Минимальное количество символов 2'),
                email:  yup.string().required('Поле обзательное').email('Некоректный формат.').min(8, 'Минимальное количество символов 8'),
                password: yup.string()
                    .when(['switchResetPassword'], {
                        is: (switchResetPassword) => switchResetPassword,
                        then: (schema) => yup.string().required('Поле обзательное').min(8, 'Минимальное количество символов 8'),
                        otherwise: (schema) => yup.string(),
                }),
                confirmPassword: yup.string()
                    .when(['switchResetPassword'], {
                        is: (switchResetPassword) => switchResetPassword,
                        then: (schema) => yup.string()
                            .required('Поле обзательное')
                            .oneOf([yup.ref('password'), null], 'Пароли должны совпадать'),
                        otherwise: (schema) => yup.string(),
                    }),
            }),
        }
    },
    created() {
        this.getUser()
    },
    mounted() {

    },
    methods: {
        getUser(): void {
            axios.get('/api/v1/user/' + this.userId).then((response) => {
                if (response.status === 200) {
                    const user = response.data.data.user;
                    this.form.entityId = this.userId;
                    this.form.firstName = user.first_name;
                    this.form.lastName = user.last_name;
                    this.form.email = user.email;
                    this.form.status = user.status;
                }
            }).catch((error) => {
                console.error(error);
                alert("Ошбка сервера, перегрузите страницу или обратитесь в тех поддержку.");
            })
        },
        onSubmit(values, actions): void {
            this.update(actions);
        },
        update(actions): void {
            this.isLoading = true;
            axios.put('/api/v1/user', this.form).then(async (response) => {
                if (response.status === 422) {
                    response.data.errors.forEach((item) => {
                        actions.setFieldError(item.field, item.message);
                    })
                    this.isLoading = false;
                } else {
                    this.$router.push({name: 'users-list'});
                }
            }).catch((error) => {
                console.error(error)
                alert("Ошбка сервера, перегрузите страницу или обратитесь в тех поддержку.");
                this.isLoading = false;
            });
        },
    }
});

</script>

<style scoped>

</style>
