<template>
    <!--begin::Menu-->
    <div
        class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold py-4 fs-6 w-275px"
        data-kt-menu="true"
    >
        <!--begin::Menu item-->
        <div class="menu-item px-3">
            <div class="menu-content d-flex align-items-center px-3">
                <!--begin::Avatar-->
                <div class="symbol symbol-50px me-5">
                    <img alt="Logo" :src="user?.file?.url || '~/assets/media/avatars/blank.png'" />
                </div>
                <!--end::Avatar-->

                <!--begin::Username-->
                <div class="d-flex flex-column">
                    <div class="fw-bold d-flex align-items-center fs-5">
                        {{ user?.name }}
                        <span
                            class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2"
                            >Pro</span
                        >
                    </div>
                    <a
                        href="#"
                        class="fw-semibold text-muted text-hover-primary fs-7"
                        >{{ user?.email }}</a
                    >
                </div>
                <!--end::Username-->
            </div>
        </div>
        <!--end::Menu item-->

        <!--begin::Menu separator-->
        <div class="separator my-2" />
        <!--end::Menu separator-->

        <!--begin::Menu item-->
        <div class="menu-item px-5">
            <NuxtLink to="/profile/overview" class="menu-link px-5">
                My Profile
            </NuxtLink>
        </div>
        <!--end::Menu item-->

        <!--begin::Menu separator-->
        <div class="separator my-2" />
        <!--end::Menu separator-->

        <!--begin::Menu item-->
        <!-- <div class="menu-item px-5 my-1">
            <NuxtLink to="/profile/overview" class="menu-link px-5">
                Account Settings
            </NuxtLink>
        </div> -->
        <!--end::Menu item-->

        <!--begin::Menu item-->
        <div class="menu-item px-5">
            <a class="menu-link px-5" @click="signOut()"> Sign Out </a>
        </div>
        <!--end::Menu item-->
    </div>
    <!--end::Menu-->
</template>

<script lang="ts" setup>
import { useI18n } from "vue-i18n";
import type { User } from "~/types/user";

const i18n = useI18n();
const { logout } = useSanctumAuth();
const user = useSanctumUser<User>();

i18n.locale.value = localStorage.getItem("lang")
    ? (localStorage.getItem("lang") as string)
    : "en";

const signOut = async () => {
    await logout();

    navigateTo("/auth/sign-in");
};
</script>
