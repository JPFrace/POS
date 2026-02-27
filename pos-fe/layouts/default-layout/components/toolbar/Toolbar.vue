<template>
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <!--begin::Toolbar container-->
        <div
            id="kt_app_toolbar_container"
            class="app-container flex flex-col-reverse gap-y-2 items-start md:items-center justify-start md:justify-between md:flex-row"
            :class="{
                'container-fluid': toolbarWidthFluid,
                'container-xxl': !toolbarWidthFluid,
            }"
        >
            <!--begin::Actions-->
            <div class="flex items-center justify-start gap-2 toolbar-left">
                <slot name="left" />
            </div>
            <!--end::Actions-->
            <!--begin::Actions-->
            <div class="d-flex align-items-center gap-2 toolbar-right">
                <slot name="right" />
            </div>
            <!--end::Actions-->
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--end::Toolbar-->
</template>

<script lang="ts" setup>
import { toolbarWidthFluid } from "~/layouts/default-layout/config/helper";
</script>
