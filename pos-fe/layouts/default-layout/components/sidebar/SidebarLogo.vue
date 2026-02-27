<template>
    <!--begin::Logo-->
    <div id="kt_app_sidebar_logo" class="app-sidebar-logo px-6">
        <!--begin::Logo image-->
        <NuxtLink to="/">
            <span
                v-if="themeMode === 'light' && layout === 'light-sidebar'"
                class="h-25px text-3xl text-black"
                >{{ title }}</span
            >
            <span
                v-if="
                    layout === 'dark-sidebar' ||
                    (themeMode === 'dark' && layout === 'dark-sidebar')
                "
                class="h-25px text-3xl text-white"
                >{{ title }}</span
            >
        </NuxtLink>
        <!--end::Logo image-->
        <SidebarUserPosition />
        <!--begin::Sidebar toggle-->
        <div
            v-if="sidebarToggleDisplay"
            id="kt_app_sidebar_toggle"
            ref="toggleRef"
            class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute top-50 start-100 translate-middle rotate"
            data-kt-toggle="true"
            data-kt-toggle-state="active"
            data-kt-toggle-target="body"
            data-kt-toggle-name="app-sidebar-minimize"
        >
            <KTIcon
                icon-name="black-left-line"
                icon-class="fs-3 rotate-180 ms-1"
            />
        </div>
        <!--end::Sidebar toggle-->
    </div>
    <!--end::Logo-->
</template>

<script setup lang="ts">
import { onMounted, ref } from "vue";
import { ToggleComponent } from "~/assets/ts/components";
import {
    layout,
    sidebarToggleDisplay,
    themeMode,
} from "~/layouts/default-layout/config/helper";

import SidebarUserPosition from "~/layouts/default-layout/components/sidebar/SidebarUserPosition.vue";

interface IProps {
    sidebarRef: HTMLElement | null;
}

const title = ref(import.meta.env.VITE_APP_NAME);

const props = defineProps<IProps>();

const toggleRef = ref<HTMLFormElement | null>(null);

onMounted(() => {
    setTimeout(() => {
        const toggleObj = ToggleComponent.getInstance(
            toggleRef.value!,
        ) as ToggleComponent | null;

        if (toggleObj === null) {
            return;
        }

        // Add a class to prevent sidebar hover effect after toggle click
        toggleObj.on("kt.toggle.change", function () {
            // Set animation state
            props.sidebarRef?.classList.add("animating");

            // Wait till animation finishes
            setTimeout(function () {
                // Remove animation state
                props.sidebarRef?.classList.remove("animating");
            }, 300);
        });
    }, 1);
});
</script>
