<template>
    <NuxtPage />
</template>

<script lang="ts">
import { defineComponent, nextTick, onBeforeMount, onMounted } from "vue";
import { RouterView } from "vue-router";
import { themeConfigValue } from "~/layouts/default-layout/config/helper";
import { initializeComponents } from "~/core/plugins/keenthemes";

export default defineComponent({
    name: "App",
    components: {
        RouterView,
    },
    setup() {
        const configStore = useConfigStore();
        const themeStore = useThemeStore();
        const bodyStore = useBodyStore();

        onBeforeMount(() => {
            /**
             * Overrides the layout config using saved data from localStorage
             * remove this to use static config (~/layouts/default-layout/config/DefaultLayoutConfig.ts)
             */
            configStore.overrideLayoutConfig();

            /**
             *  Sets a mode from configuration
             */
            themeStore.setThemeMode(themeConfigValue.value);
        });

        onMounted(() => {
            nextTick(() => {
                initializeComponents();

                bodyStore.removeBodyClassName("page-loading");
            });
        });
    },
});
</script>

<style>
/* .page-enter-active,
.page-leave-active {
    transition: all 0.3s;
}
.page-enter-from,
.page-leave-to {
    opacity: 0;
    filter: blur(1rem);
} */
</style>
