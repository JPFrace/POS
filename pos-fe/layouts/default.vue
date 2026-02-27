<template>
    <div>
        <!--begin::App-->
        <div id="kt_app_root" class="d-flex flex-column flex-root app-root">
            <!--begin::Page-->
            <div
                id="kt_app_page"
                class="app-page flex-column flex-column-fluid"
            >
                <KTHeader />
                <!--begin::Wrapper-->
                <div
                    id="kt_app_wrapper"
                    class="app-wrapper flex-column flex-row-fluid"
                >
                    <KTSidebar />
                    <!--begin::Main-->
                    <div
                        id="kt_app_main"
                        class="app-main flex-column flex-row-fluid"
                        :class="{ 'dashboard-bg': route.meta.isDashboard }"
                    >
                        <!--begin::Content wrapper-->
                        <div class="d-flex flex-column flex-column-fluid">
                            <!--Show toolbar only if route meta 'showToolbar' is not set to false-->
                            <KTToolbar
                                :class="{
                                    'd-none': route.meta.showToolbar === false,
                                }"
                                class="mb-8"
                            >
                                <template #left>
                                    <slot name="toolbarLeft" />
                                </template>
                                <template #right>
                                    <slot name="toolbarRight" />
                                </template>
                            </KTToolbar>

                            <KTContent>
                                <slot />
                            </KTContent>
                        </div>
                        <!--end::Content wrapper-->
                        <KTFooter />
                    </div>
                    <!--end:::Main-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Page-->
            <KTDrawers />
            <KTScrollTop />
            <KTModals />
        </div>
        <!--end::App-->
    </div>
</template>

<script lang="ts" setup>
import {
    defineComponent,
    nextTick,
    onBeforeMount,
    onMounted,
    watch,
} from "vue";
import KTHeader from "~/layouts/default-layout/components/header/Header.vue";
import KTSidebar from "~/layouts/default-layout/components/sidebar/Sidebar.vue";
import KTContent from "~/layouts/default-layout/components/content/Content.vue";
import KTToolbar from "~/layouts/default-layout/components/toolbar/Toolbar.vue";
import KTFooter from "~/layouts/default-layout/components/footer/Footer.vue";
import KTDrawers from "~/layouts/default-layout/components/drawers/Drawers.vue";
import KTModals from "~/layouts/default-layout/components/modals/Modals.vue";
import KTScrollTop from "~/layouts/default-layout/components/extras/ScrollTop.vue";
import { useRoute } from "vue-router";
import { reinitializeComponents } from "~/core/plugins/keenthemes";
import LayoutService from "~/core/services/LayoutService";

const route = useRoute();

onBeforeMount(() => {
    LayoutService.init();
});

onMounted(() => {
    nextTick(() => {
        reinitializeComponents();
    });
});

watch(
    () => route.path,
    () => {
        nextTick(() => {
            reinitializeComponents();
        });
    },
);
</script>
