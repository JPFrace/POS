import MainMenuConfig from "~/layouts/default-layout/config/MainMenuConfig";
import type { User } from "~/types/user";

export default defineNuxtRouteMiddleware((to) => {
    const user = useSanctumUser<User>();

    const metaPermission = to.meta.permission as string;

    if (!user.value) {
        return;
    }

    // If no permission given then grant access
    if (!metaPermission) {
        return true;
    }

    if (metaPermission && canAccess(user.value, [metaPermission])) {
        return true;
    }

    return abortNavigation();
});
