<template>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div
            id="kt_app_content_container"
            class="app-container min-h-[calc(100vh-180px)]"
            :class="{
                'container-fluid': contentWidthFluid,
                'container-xxl': !contentWidthFluid,
            }"
        >
            <slot />
        </div>
        <!--end::Content container-->
    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import { contentWidthFluid } from "~/layouts/default-layout/config/helper";
import { RouterView } from "vue-router";

export default defineComponent({
    name: "DefaultLayoutContent",
    components: {
        RouterView,
    },
    setup() {
        return {
            contentWidthFluid,
        };
    },
});
</script>
