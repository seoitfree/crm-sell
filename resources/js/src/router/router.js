import { createRouter, createWebHistory } from 'vue-router';
import routes from './routes.js';

const router = createRouter({
    history: createWebHistory(),
    linkActiveClass: 'active',
    routes,
});

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('x_xsrf_token'); //TODO next step install Pinia store for comfort work;

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


