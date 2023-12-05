
import {useUserStore} from "../../stores/UserStore";


//TODO refactoring add type;
export function userLoginMiddleware(to, from, next): any {
    const userStore = useUserStore();
    const token = userStore.isAuthenticated;

    notAuth(token, to, next);

    if (token && to.name === 'user.login') {
        return next({
            name: 'main'
        });
    }
    next();
}

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
