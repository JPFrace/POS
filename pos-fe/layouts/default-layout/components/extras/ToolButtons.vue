<template>
    <div
        class="engage-toolbar d-flex position-fixed px-5 fw-bold zindex-2 top-50 end-0 transform-90 mt-20 gap-2"
    >
        <!--begin::Help drawer toggle-->
        <button
            id="kt_help_toggle"
            class="engage-help-toggle btn btn-flex h-35px bg-body btn-color-gray-700 btn-active-color-gray-900 shadow-sm px-5 rounded-top-0"
            title="Learn & Get Inspired"
            data-bs-toggle="tooltip"
            data-bs-placement="left"
            data-bs-dismiss="click"
            data-bs-trigger="hover"
        >
            Help
        </button>
        <!--end::Help drawer toggle-->

        <!--begin::Purchase link-->
        <button
            id="kt_app_layout_builder_toggle"
            class="engage-help-toggle btn btn-flex h-35px bg-body btn-color-gray-700 btn-active-color-gray-900 shadow-sm px-5 rounded-top-0"
            title="Learn & Get Inspired"
            data-bs-toggle="tooltip"
            data-bs-placement="left"
            data-bs-dismiss="click"
            data-bs-trigger="hover"
        >
            <KTIcon icon-name="setting-4" icon-class="fs-4 me-1" /> Customize
        </button>
        <!--end::Purchase link-->
    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import KTCustomize from "./CustomizeDrawer.vue";

export default defineComponent({
    name: "KtToolbarButtons",
    components: { KTCustomize },
});
</script>
