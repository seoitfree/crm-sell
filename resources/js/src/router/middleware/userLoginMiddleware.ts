
import {useUserStore} from "../../stores/UserStore";
import {RouteNamesEnum} from "../RouteNamesEnum";


//TODO refactoring add type;
export function userLoginMiddleware(to, from, next): any {
    const userStore = useUserStore();
    const token = userStore.isAuthenticated;

    notAuth(token, to, next);

    if (token && to.name === RouteNamesEnum.USER_LOGIN) {
        return next({
            name: RouteNamesEnum.MAIN
        });
    }
    next();
}

function notAuth(token, to, next) {
    if (token) {
        return;
    }
    if (to.name !== RouteNamesEnum.USER_LOGIN) {
        return next({
            name: RouteNamesEnum.USER_LOGIN
        });
    }
    return next();
}
