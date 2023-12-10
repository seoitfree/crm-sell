
import type { RouteLocationNormalized } from "vue-router";
import {initAuth} from "../../modules/Auth/auth.service";
import {RouteNamesEnum} from "../RouteNamesEnum";

export function accessGuardMiddleware(to: RouteLocationNormalized, from, next) {
    const { accessScopes } = to.meta;
    if (!accessScopes) {
        return next();
    }
    const permission = initAuth();
    if (permission.hasRoles(accessScopes)) {
        return next();
    }
    return next({
        name: RouteNamesEnum.ACCESS_ERROR
    })
}
