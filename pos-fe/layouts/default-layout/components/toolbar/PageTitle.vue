<template>
    <!--begin::Page title-->
    <div
        v-if="pageTitleDisplay"
        :class="`page-title d-flex flex-${pageTitleDirection} justify-content-center flex-wrap me-3`"
    >
        <template v-if="pageTitle">
            <!--begin::Title-->
            <h1
                class="d-flex flex-column justify-content-center my-0 text-gray-900 page-heading fw-bold fs-3"
            >
                {{ pageTitle }}
            </h1>
            <!--end::Title-->

            <span
                v-if="
                    pageTitleDirection === 'row' && pageTitleBreadcrumbDisplay
                "
                class="mx-3 border-gray-200 border-start h-20px"
            />

            <!--begin::Breadcrumb-->
            <ul
                v-if="breadcrumbs && pageTitleBreadcrumbDisplay"
                class="my-0 pt-1 breadcrumb breadcrumb-separatorless fw-semibold fs-7"
            >
                <!--begin::Item-->
                <li class="text-muted breadcrumb-item">
                    <NuxtLink to="/" class="text-hover-primary text-muted"
                        >Home</NuxtLink
                    >
                </li>
                <!--end::Item-->
                <template v-for="(item, i) in breadcrumbs" :key="i">
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bg-gray-500 w-5px h-2px bullet" />
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="text-muted breadcrumb-item">{{ item }}</li>
                    <!--end::Item-->
                </template>
            </ul>
            <!--end::Breadcrumb-->
        </template>
    </div>
    <div v-else class="align-items-stretch" />
    <!--end::Page title-->
</template>

<script lang="ts">
import { computed, defineComponent, onMounted } from "vue";
import {
    pageTitleBreadcrumbDisplay,
    pageTitleDirection,
    pageTitleDisplay,
} from "~/layouts/default-layout/config/helper";
import { useRoute } from "vue-router";

export default defineComponent({
    name: "LayoutPageTitle",
    components: {},
    setup() {
        const route = useRoute();

        const pageTitle = computed(() => {
            const path = route.path.replace(
                Object.keys(route.params).map((key) => "/" + route.params[key]),
                ""
            );

            const lastSegment = path.split("/").filter(Boolean).slice(-1)[0] || "";

            return lastSegment
                .replace(/-/g, " ")
                .split(" ")
                .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
                .join(" ");
        });


            const breadcrumbs = computed(() => {
                return route.path
                    .split("/")
                    .filter(Boolean)
                    .map(segment =>
                        segment
                            .replace(/-/g, " ")
                            .split(" ")
                            .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
                            .join(" ")
                    );
            });


        onMounted(() => {
            console.log(pageTitleDisplay.value);
        });

        return {
            pageTitle,
            breadcrumbs,
            pageTitleDisplay,
            pageTitleBreadcrumbDisplay,
            pageTitleDirection,
        };
    },
});
</script>
