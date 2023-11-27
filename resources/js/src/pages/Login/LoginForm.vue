
<template>
    <form class="auth-form login-form" >
        <div class="email mb-3">
            <label class="sr-only" for="signin-email">Email</label>
            <input id="signin-email" name="signin-email" type="email" class="form-control signin-email" placeholder="Email" v-model="form.email" required="required">
        </div><!--//form-group-->
        <div class="password mb-3">
            <label class="sr-only" for="signin-password">Password</label>
            <input id="signin-password" name="signin-password" type="password" class="form-control signin-password" placeholder="Password" v-model="form.password" required="required">
            <div class="extra mt-3 row justify-content-between">
                <div class="col-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="RememberPassword">
                        <label class="form-check-label" for="RememberPassword">
                            Remember me
                        </label>
                    </div>
                </div><!--//col-6-->
                <div class="col-6">
                    <div class="forgot-password text-end">
                        <a href="reset-password.html">Forgot password?</a>
                    </div>
                </div><!--//col-6-->
            </div><!--//extra-->
        </div><!--//form-group-->
        <div v-if="!processing" class="text-center">
            <button type="button" class="btn app-btn-primary w-100 theme-btn mx-auto" @click="logIn()">Log In</button>
        </div>
        <button v-if="processing" class="btn btn-primary text-center" type="button" disabled>
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            <span class="visually-hidden">Loading...</span>
        </button>
    </form>
</template>

<script lang="ts">

import { defineComponent, ref } from 'vue';
import axios from "axios";
import { useUserStore } from "../../stores/UserStore";
import {UserInfo} from "../../types/userInfo";

const userStore = useUserStore();

export default defineComponent({
    name: "LoginForm",
    data() {
        return {
            processing: false,
            form: {
                email: '' as string,
                password: '' as string,
            }
        }
    },
    methods: {
        logIn(): void {
            this.processing = true;
            axios.get('/sanctum/csrf-cookie').then(() => {
                this.login();
            }).catch(() => {
                this.processing = false;
            });
        },
        login(): void {
            axios.post('/login', this.form).then((response) => {
                this.getUserData(response.config.headers['X-XSRF-TOKEN']);
            }).catch(() => {
                this.processing = false;
            });
        },
        getUserData(token: string): void {
            axios.get('/api/user').then((response) => {
                userStore.login(token, response as UserInfo);
                this.$router.push({name: 'main'});
            }).catch(() => {
                this.processing = false;
            });
        },
    }
});

</script>

<style scoped>

</style>
