<template>
    <KTMessengerDrawer />
    <KTActivityDrawer />
    <KTToolButtons />
    <KTHelpDrawer />
    <KTCustomizeDrawer />
</template>

<script lang="ts">
import { defineComponent } from "vue";
import KTMessengerDrawer from "~/layouts/default-layout/components/extras/MessengerDrawer.vue";
import KTActivityDrawer from "~/layouts/default-layout/components/drawers/ActivityDrawer.vue";
import KTHelpDrawer from "~/layouts/default-layout/components/extras/HelpDrawer.vue";
import KTToolButtons from "~/layouts/default-layout/components/extras/ToolButtons.vue";
import KTCustomizeDrawer from "../extras/CustomizeDrawer.vue";
export default defineComponent({
    name: "GlobalDrawers",
    components: {
        KTMessengerDrawer,
        KTActivityDrawer,
        KTHelpDrawer,
        KTToolButtons,
        KTCustomizeDrawer,
    },
});
</script>
