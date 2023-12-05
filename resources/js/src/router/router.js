
import { createRouter, createWebHistory } from 'vue-router';
import routes from './routes.js';
import {userLoginMiddleware} from "./middleware/userLoginMiddleware";
import {initUser} from "./middleware/initUserMiddleware";

const router = createRouter({
    history: createWebHistory(),
    linkActiveClass: 'active',
    routes,
});


router.beforeEach(userLoginMiddleware);
router.beforeEach(initUser);

export default router;


