
import {RouteNamesEnum} from "./RouteNamesEnum";
import {RolesEnum} from "../modules/Auth/enum/roles.enum";
const Login = () => import("@/js/src/pages/Login/Login.vue");
const Main = () => import("@/js/src/pages/Main/Main.vue");
const Docs = () => import("@/js/src/pages/Docs/Docs.vue");
const DeliveryTermsList = () => import("../modules/DeliveryTerms/pages/List/DeliveryTermsList.vue");


const routes = [
    {
        path: '/login',
        name: RouteNamesEnum.USER_LOGIN,
        component: Login,
    },
    {
        path: '/',
        name: RouteNamesEnum.MAIN,
        component: Main,
        meta: {
            sidepanel: 'main',
        },
    },
    {
        path: '/docs',
        name: RouteNamesEnum.DOCS,
        component: Docs,
        meta: {
            accessScopes: [RolesEnum.ADMIN],
            sidepanel: 'main',
        },
    },
    {
        path: '/delivery-terms',
        component: DeliveryTermsList,
        meta: {
            sidepanel: 'main',
        },
    },
    {
        path: '/admin',
        component: Docs,
        meta: {
            accessScopes: [RolesEnum.ADMIN],
            sidepanel: 'admin'
        },
    },
    {
        path: '/users',
        component: () => import("../modules/Admin/pages/Users/List/UsersList.vue"),
        meta: {
            accessScopes: [RolesEnum.ADMIN],
            sidepanel: 'admin',
        },
    },
    {
        path: '/access-error',
        name: RouteNamesEnum.ACCESS_ERROR,
        meta: {
            sidepanel: 'main',
        },
        component: () => import("@/js/src/modules/Auth/pages/AccessError.vue"),
    },
    {
        path: '/:catchAll(.*)',
        component: () => import("@/js/src/modules/Auth/pages/PageNotFound.vue"),
    },
];

export default routes;
