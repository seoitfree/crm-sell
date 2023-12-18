<template>
    <div>
        <div v-if="isLoading" class="d-flex justify-content-center">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <Form v-if="!isLoading" tag="form" @submit="onSubmit" :validation-schema="validation">
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
                    <label for="email">Роли</label>
                    <Field class="form-select" name="roles" v-model="form.roles" as="select" multiple>
                        <template v-for="role in rolesEnum">
                            <option :value="role.key">{{ role.value }}</option>
                        </template>
                    </Field>
                    <ErrorMessage name="roles" class="text-danger" />
                </div>
            </div>

            <div class="form-group row">
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

            <div class="text-center mt-2">
                <button type="submit" class="btn app-btn-primary">Создать</button>
            </div>
        </Form>
    </div>

</template>

<script lang="ts">
import {defineComponent} from "vue";
import * as yup from "yup";
import { Form, Field, ErrorMessage } from 'vee-validate';
import axios from "axios";


interface Options {
    key: string;
    value: string;
}

export default defineComponent({
    name: "CreateUpdateForm",
    components: {
        Form,
        Field,
        ErrorMessage
    },
    data() {
        return {
            isLoading: false,
            form: {
                entityId: '',
                firstName: '',
                lastName: '',
                email: '',
                password: '',
                confirmPassword: '',
                roles: [] as string[],
                status: 0,
            },
            validation: yup.object().shape({
                firstName:  yup.string().required('Поле обзательное').min(2, 'Минимальное количество символов 2'),
                lastName: yup.string().required('Поле обзательное').min(2, 'Минимальное количество символов 2'),
                email:  yup.string().required('Поле обзательное').email('Некоректный формат.').min(8, 'Минимальное количество символов 8'),
                password: yup.string().required('Поле обзательное').min(8, 'Минимальное количество символов 8'),
                confirmPassword: yup.string()
                    .oneOf([yup.ref('password'), null], 'Пароли должны совпадать')
                    .required('Поле обзательное'),
                roles: yup.array().min(1, 'Выберите хотя бы одну роль'),
            }),
            rolesEnum: [] as Options[],
        }
    },
    async created() {
        if (this.form.entityId === '') {
            this.isLoading = true;
            try {
                await this.getRoles();
                this.isLoading = false;
            } catch (e) {
                alert("Ошбка сервера, перегрузите страницу или обратитесь в тех поддержку.");
                this.isLoading = false;
            }
        }
    },
    methods: {
       onSubmit(values, actions) {
            console.log("onSubmit");
           // console.log(actions);
           //  return;
            if (this.form.entityId === '') {
                this.create(actions);
            } else {
                this.update(actions);
            }
        },
        create(actions): void {
            this.isLoading = true;
            axios.post('/api/v1/user', this.form).then((response) => {
                if (response.status === 422) {
                    console.log(response);
                    //actions.setFieldError('email', 'this email is already taken');
                    this.isLoading = false;
                } else {
                    this.$router.push({name: 'users-list'});
                }
            }).catch((error) => {
                this.isLoading = false;
            });
        },
        update(actions): void {

        },
        async getRoles(): Promise<void> {
            return axios.get('/api/v1/roles').then((response) => {
                if (response.status !== 200) {
                    throw Error("Error");
                }
                this.rolesEnum = response.data.data;
            });
        }
    }
});

</script>

<style scoped>

</style>
