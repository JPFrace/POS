import { ref } from "vue";
import { defineStore } from "pinia";

export interface User {
    email: string;
    password: string;
}

export const useAuthStore = defineStore("auth", () => {
    const checkout = useCheckoutStore();
    const user = ref<User>({} as User);
    const { login: loginAs, logout: logoutAs } = useSanctumAuth();

    function purgeAuth() {
        logoutAs();
    }

    function login(credentials: User) {
        return loginAs(credentials);
    }

    function logout() {
        purgeAuth();
        checkout.reset();
        navigateTo("/auth/sign-in");
    }

    return {
        user,
        login,
        logout,
    };
});
