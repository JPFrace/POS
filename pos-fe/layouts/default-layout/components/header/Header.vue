<template>
    <!--begin::Header-->
    <div
        v-if="headerDisplay"
        id="kt_app_header"
        class="app-header"
        data-kt-sticky="true"
        data-kt-sticky-activate="{default: true, lg: true}"
        data-kt-sticky-name="app-header-minimize"
        data-kt-sticky-offset="{default: '200px', lg: '0'}"
        data-kt-sticky-animation="false"
    >
        <!--begin::Header container-->
        <div
            class="app-container d-flex align-items-stretch justify-content-between"
            :class="{
                'container-fluid': headerWidthFluid,
                'container-xxl': !headerWidthFluid,
            }"
        >
            <div
                v-if="layout === 'light-header' || layout === 'dark-header'"
                class="d-flex align-items-center flex-grow-1 flex-lg-grow-0 me-lg-15"
            >
                <NuxtLink to="/">
                    <img
                        v-if="
                            themeMode === 'light' && layout === 'light-header'
                        "
                        alt="Logo Title"
                        src="~/assets/media/logos/default.svg"
                        class="h-20px h-lg-30px app-sidebar-logo-default theme-light-show"
                    />
                    <img
                        v-if="
                            layout === 'dark-header' ||
                            (themeMode === 'dark' && layout === 'light-header')
                        "
                        alt="Logo dasd"
                        src="~/assets/media/logos/default-dark.svg"
                        class="h-20px h-lg-30px app-sidebar-logo-default"
                    />
                </NuxtLink>
            </div>
            <template v-else>
                <!--begin::sidebar mobile toggle-->
                <div
                    class="d-flex align-items-center d-lg-none ms-n3 me-1 me-md-2"
                >
                    <div
                        id="kt_app_sidebar_mobile_toggle"
                        class="btn btn-icon btn-active-color-primary w-35px h-35px"
                    >
                        <KTIcon
                            icon-name="abstract-14"
                            icon-class="fs-2 fs-md-1"
                        />
                    </div>
                </div>
                <!--end::sidebar mobile toggle-->
                <!--begin::Mobile logo-->
                <div
                    class="d-flex align-items-center flex-grow-1 flex-lg-grow-0"
                >
                    <NuxtLink to="/" class="d-lg-none">
                        <img
                            alt="Logo Title"
                            src="~/assets/media/logos/default-small.svg"
                            class="h-30px"
                        />
                    </NuxtLink>
                </div>
                <!--end::Mobile logo-->
            </template>
            <!--begin::Header wrapper-->
            <div
                id="kt_app_header_wrapper"
                class="d-flex align-items-stretch justify-content-between flex-lg-grow-1"
            >
                <KTPageTitle />
                <KTHeaderNavbar />
            </div>
            <!--end::Header wrapper-->
        </div>
        <!--end::Header container-->
    </div>
    <!--end::Header-->
</template>

<script lang="ts">
import { defineComponent } from "vue";
import KTPageTitle from "~/layouts/default-layout/components/toolbar/PageTitle.vue";
import KTMenu from "~/layouts/default-layout/components/header/menu/Menu.vue";
import KTHeaderNavbar from "./Navbar.vue";
import {
    sidebarEnabled,
    headerDisplay,
    headerWidthFluid,
    layout,
    themeMode,
    headerDesktopFixed,
    headerMobileFixed,
} from "~/layouts/default-layout/config/helper";

export default defineComponent({
    name: "LayoutHeader",
    components: {
        KTPageTitle,
        KTHeaderNavbar,
        KTMenu,
    },
    setup() {
        return {
            layout,
            headerWidthFluid,
            headerDisplay,
            themeMode,
            headerDesktopFixed,
            headerMobileFixed,
            sidebarEnabled,
        };
    },
});
</script>
