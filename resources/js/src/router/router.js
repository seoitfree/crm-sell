import { createRouter, createWebHistory } from 'vue-router';
import routes from './routes.js';
import {useUserStore} from "../stores/UserStore";

const router = createRouter({
    history: createWebHistory(),
    linkActiveClass: 'active',
    routes,
});

router.beforeEach((to, from, next) => {
    const userStore = useUserStore();
    const token = userStore.isAuthenticated;

    notAuth(token, to, next);

    if (token && to.name === 'user.login') {
        return next({
            name: 'main'
        });
    }
    next();
});

function notAuth(token, to, next) {
    if (token) {
        return;
    }
    if (to.name !== 'user.login') {
        return next({
            name: 'user.login'
        });
    }
    return next();
}

export default router;


