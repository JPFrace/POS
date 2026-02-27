<template>
    <!--begin::Menu-->
    <div
        class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-muted menu-active-bg menu-state-primary fw-semibold py-4 fs-base w-175px"
        data-kt-menu="true"
        data-kt-element="theme-mode-menu"
    >
        <!--begin::Menu item-->
        <div class="menu-item px-3 my-0">
            <NuxtLink
                :to="path"
                :class="{ active: themeMode === 'light' }"
                class="menu-link px-3 py-2"
                @click="setMode()"
            >
                <span class="menu-icon" data-kt-element="icon">
                    <KTIcon icon-name="night-day" icon-class="fs-2" />
                </span>
                <span class="menu-title">Light</span>
            </NuxtLink>
        </div>
    </div>
    <!--end::Menu-->
</template>

<script lang="ts">
import { computed, defineComponent } from "vue";
import { useRoute } from "vue-router";

export default defineComponent({
    name: "KtThemeSwitcher",
    component: {},
    setup() {
        const storeTheme = useThemeStore();
        const storeConfig = useConfigStore();
        const route = useRoute();

        const themeMode = computed(() => storeTheme.mode);
        const path = computed(() => route.path);

        const setMode = () => {
            storeConfig.setLayoutConfigProperty("general.mode", "light");
            storeTheme.setThemeMode("light");
        };

        // Set light mode by default on mount
        onMounted(() => {
            setMode();
        });

        return {
            themeMode,
            setMode,
            path,
        };
    },
});
</script>
