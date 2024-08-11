
<template>
    <Form tag="form" ref='form' class="auth-form login-form" v-slot="{ handleSubmit }">
        <div class="email mb-3 text-center">
            <label class="sr-only" for="signin-email">Email</label>
            <Field name="signin-email" type="signin-email" class="form-control signin-email" placeholder="Email" :rules="validation.email" v-slot="{ errors }" v-model="form.email"></Field>
            <ErrorMessage name="signin-email" class="text-danger" />
            <span class="text-danger" v-if="errorServerValidation !== ''">{{ errorServerValidation }}</span>
        </div>

        <div class="password mb-3 text-center">

            <label class="sr-only" for="signin-password">Password</label>
            <Field id="signin-password" name="signin-password" type="password" class="form-control signin-password" placeholder="Password" :rules="validation.password" v-slot="{ errors }" v-model="form.password"></Field>
            <ErrorMessage name="signin-password" class="text-danger" />

            <div class="extra mt-3 row justify-content-between">
                <div class="col-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="RememberPassword">
                        <label class="form-check-label" for="RememberPassword">
                            Remember me
                        </label>
                    </div>
                </div>
                <div class="col-6">
                    <div class="forgot-password text-end">
                        <a href="reset-password.html">Forgot password?</a>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="!processing" class="text-center">
            <button type="button" class="btn app-btn-primary w-100 theme-btn mx-auto" @click="handleSubmit($event, onSubmit)">Log In</button>
        </div>
        <button v-if="processing" class="btn btn-primary text-center" type="button" disabled>
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            <span class="visually-hidden">Loading...</span>
        </button>

    </Form>
</template>

<script lang="ts">

import { defineComponent, ref } from 'vue';
import axios from "axios";
import { useUserStore } from "../../stores/UserStore";
import {UserInfo} from "../../types/userInfo";
import { Form, Field, ErrorMessage} from 'vee-validate';
import * as yup from 'yup';
import {initAuth} from "../../modules/Auth/auth.service";
const userStore = useUserStore();

export default defineComponent({
    name: "LoginForm",
    components: {
        Form,
        Field,
        ErrorMessage
    },
    data() {
        return {
            processing: false,
            errorServerValidation: '',
            form: {
                email: '' as string,
                password: '' as string,
            },
            validation: {
                email:  yup.string().required('Поле обзательное').email('Некоректный формат.').min(8, 'Минимальное количество символов 8'),
                password: yup.string().required('Поле обзательное').min(8, 'Минимальное количество символов 8'),
            }
        }
    },
    methods: {
        async onSubmit(values): void {
            this.processing = true;
            this.errorServerValidation = '';
            axios.get('/sanctum/csrf-cookie').then((response) => {
                this.login();
            }).catch(() => {
                this.processing = false;
            });
        },
        getXsrfToken(): string {
            return this.getCookie('XSRF-TOKEN');
        },
        getCookie(name) : string {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
            return null;
        },
        login(): void {
            axios.post('/login', this.form).then((response) => {
                if (response.status === 422) {
                    this.errorServerValidation = 'Некоректный email или пароль.'
                    this.processing = false;
                } else {
                    this.getUserData(this.getXsrfToken());
                }
            }).catch((error) => {
                this.processing = false;
            });
        },
        getUserData(token: string): void {
            initAuth().init();
            userStore.login(token);
            this.$router.push({name: 'main'});
        },
    }
});

</script>

<style scoped>

</style>
