
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
        <div class="text-center">
            <button type="button" class="btn app-btn-primary w-100 theme-btn mx-auto" @click="logIn()">Log In</button>
        </div>
    </form>
</template>

<script lang="ts">

import { defineComponent, ref } from 'vue';
import axios from "axios";

export default defineComponent({
    name: "LoginForm",
    data() {
        return {
            form: {
                email: '' as string,
                password: '' as string,
            }
        }
    },
    methods: {
       logIn(): void {
           axios.get('/sanctum/csrf-cookie').then(() => {
                axios.post('/login', this.form).then((response) => {
                    //console.log(response);

                    axios.get('/api/user').then((response) => {
                        console.log('/api/user');
                        console.log(response);
                    });
                });
            });

           // axios.get('/api/v1/user').then((response) => {
           //     console.log('/api/v1/user');
           //     console.log(response);
           // });
        }
    }
});

</script>

<style scoped>

</style>
