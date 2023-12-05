
import {useUserStore} from "../../stores/UserStore";
import {initAuth} from "../../modules/Users/Services/Auth/auth.service";

async function initUser(): Promise<void> {
    const userStore = useUserStore();
    if (userStore.isAuthenticated === null || userStore.getUserInfo !== null) {
        return;
    }
    await initAuth().init();
}


export {initUser};
