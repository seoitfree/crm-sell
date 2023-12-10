
import {initAuth} from "../auth.service";

const permissionMixin =  {
    methods: {
        hasRoles(roles: string[]) {
            return initAuth().hasRoles(roles);
        },
        hasPermission(permission: string[]) {
            return initAuth().hasPermission(permission);
        }
    }
}

export default permissionMixin;
