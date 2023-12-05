
import type { RouteLocationNormalized } from "vue-router";
import {initAuth} from "../../modules/Auth/auth.service";
import {RouteNamesEnum} from "../RouteNamesEnum";

export function accessGuardMiddleware(to: RouteLocationNormalized) {
    const { accessScopes } = to.meta;
    if (!accessScopes) {
        return;
    }
    const permission = initAuth()
    if (permission.hasRoles([accessScopes as string])) {
        return;
    }
    return { name: RouteNamesEnum.ACCESS_ERROR };
}
