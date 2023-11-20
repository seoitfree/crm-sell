
const Login = () => import("@/js/src/pages/Login/Login.vue");
const Main = () => import("@/js/src/pages/Main/Main.vue");

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
];

export default routes;
