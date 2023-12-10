
import {RouteNamesEnum} from "./RouteNamesEnum";
import {RolesEnum} from "../modules/Auth/enum/roles.enum";
const Login = () => import("@/js/src/pages/Login/Login.vue");
const Main = () => import("@/js/src/pages/Main/Main.vue");
const Docs = () => import("@/js/src/pages/Docs/Docs.vue");

const routes = [
    {
        path: '/login',
        name: 'user.login',
        component: Login,
    },
    {
        path: '/',
        name: 'main',
        component: Main,
    },
    {
        path: '/docs',
        name: 'docs',
        component: Docs,
        meta: {
            accessScopes: [RolesEnum.ADMIN],
        },
    },
    {
        path: '/access-error',
        name: RouteNamesEnum.ACCESS_ERROR,
        component: () => import("@/js/src/modules/Auth/pages/AccessError.vue"),
    },
];

export default routes;
