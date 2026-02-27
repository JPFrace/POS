<template>
    <!--begin::Navbar-->
    <div class="app-navbar flex-shrink-0">
        <!--begin::Navigation-->
        <NavigationMenu />
        <!--End::Navigation-->
        <!--begin::Config-->
        <div class="app-navbar-item align-items-stretch ms-1 ms-md-4">
            <SysConfig />
        </div>
        <!--End::config-->
        <!--begin::Search-->
        <div class="app-navbar-item align-items-stretch ms-1 ms-md-4">
            <KTSearch />
        </div>
        <!--end::Search-->
        <!--begin::Notifications-->
        <div class="app-navbar-item ms-1 ms-md-4">
            <!--begin::Menu- wrapper-->
            <div
                id="kt_menu_item_wow"
                class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px"
                data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                data-kt-menu-attach="parent"
                data-kt-menu-placement="bottom-end"
            >
                <KTIcon icon-name="notification-status" icon-class="fs-2" />
            </div>
            <KTNotificationMenu />
            <!--end::Menu wrapper-->
        </div>
        <!--end::Notifications-->
        <!--end::Chat-->
        <!--begin::Theme mode-->
        <!-- <div class="app-navbar-item ms-1 ms-md-3"> -->
        <!--begin::Menu toggle-->
        <!-- <a
                href="#"
                class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-30px h-30px w-md-40px h-md-40px"
                data-kt-menu-trigger="{default:'click', lg: 'hover'}"
                data-kt-menu-attach="parent"
                data-kt-menu-placement="bottom-end"
            >
                <KTIcon
                    v-if="themeMode === 'light'"
                    icon-name="night-day"
                    icon-class="fs-2"
                />
                <KTIcon v-else icon-name="moon" icon-class="fs-2" />
            </a> -->
        <!--begin::Menu toggle-->
        <!-- <KTThemeModeSwitcher />
        </div> -->
        <!--end::Theme mode-->
        <!--begin::User menu-->
        <div
            id="kt_header_user_menu_toggle"
            class="app-navbar-item ms-1 ms-md-4"
        >
            <!--begin::Menu wrapper-->
            <div
                class="cursor-pointer symbol symbol-35px"
                data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                data-kt-menu-attach="parent"
                data-kt-menu-placement="bottom-end"
            >
                <img :src="user_avatar" class="rounded-3" alt="user" />
            </div>
            <KTUserMenu />
            <!--end::Menu wrapper-->
        </div>
        <!--end::User menu-->
        <!--begin::Header menu toggle-->
        <div class="app-navbar-item d-lg-none ms-2 me-n2">
            <div
                id="kt_app_header_menu_toggle"
                class="btn btn-flex btn-icon btn-active-color-primary w-30px h-30px"
            >
                <KTIcon icon-name="element-4" icon-class="fs-2" />
            </div>
        </div>
        <!--end::Header menu toggle-->
    </div>
    <!--end::Navbar-->
</template>

<script lang="ts">
import { computed, defineComponent } from "vue";
import KTSearch from "~/layouts/default-layout/components/search/Search.vue";
import KTNotificationMenu from "~/layouts/default-layout/components/menus/NotificationsMenu.vue";
import KTUserMenu from "~/layouts/default-layout/components/menus/UserAccountMenu.vue";
import KTThemeModeSwitcher from "~/layouts/default-layout/components/theme-mode/ThemeModeSwitcher.vue";
import NavigationMenu from "~/layouts/default-layout/components/menus/Navigation.vue";
import SysConfig from "~/features/config/config.vue";
import { ThemeModeComponent } from "~/assets/ts/layout";

export default defineComponent({
    name: "HeaderNavbar",
    components: {
        SysConfig,
        KTSearch,
        KTNotificationMenu,
        KTUserMenu,
        KTThemeModeSwitcher,
        NavigationMenu,
    },
    setup() {
        const store = useThemeStore();

        const user = useSanctumUser();

        const user_avatar = computed(
            () => user.value?.file?.url || "~/assets/media/avatars/blank.png",
        );

        const themeMode = computed(() => {
            if (store.mode === "system") {
                return ThemeModeComponent.getSystemMode();
            }
            return store.mode;
        });

        return {
            themeMode,
            user_avatar,
        };
    },
});
</script>
