<template>
    <div
        class="flex flex-col lg:flex-row-reverse h-screen overflow-hidden p-8 bg-[#F2F3F4] gap-8"
    >
        <!-- TEXT -->

        <!-- IMAGE -->
        <div
            class="relative w-full lg:w-[45%] h-full bg-cover bg-center rounded-[20px]"
            :style="`background-image: url('https://sscw.priisms.online/general/getThemePhoto?tid=1&field=login_bg')`"
        >
            <div
                class="absolute inset-0 bg-gradient-to-r from-[#0A61AA]/60 to-[#121E43]/60 rounded-[20px]"
            />

            <!-- TEXT CONTENT -->
            <div class="absolute inset-0 flex items-end justify-center pb-20">
                <div class="text-left">
                    <p
                        class="text-white text-[clamp(48px,4vw,88px)] leading-tight font-urbanist font-semibold whitespace-nowrap"
                    >
                        Excellence thru
                    </p>
                    <p
                        class="text-[#00D1FF] text-[clamp(48px,4vw,88px)] font-black leading-[clamp(56px,4.5vw,96px)] font-urbanist"
                    >
                        Security
                    </p>
                </div>
            </div>
        </div>

        <!-- FORM -->
        <div
            class="w-full lg:w-[55%] flex items-center justify-center py-20 lg:py-100"
        >
            <div class="w-full max-w-[520px]">
                <slot />
            </div>
        </div>
    </div>
</template>
<script lang="ts">
import { getAssetPath } from "@/core/helpers/assets";
import { defineComponent, onMounted } from "vue";
import LayoutService from "@/core/services/LayoutService";

export default defineComponent({
    name: "AuthLayout",
    components: {},
    setup() {
        const store = useBodyStore();

        onMounted(() => {
            LayoutService.emptyElementClassesAndAttributes(document.body);

            store.addBodyClassname("app-blank");
            store.addBodyClassname("bg-body");
        });

        return {
            getAssetPath,
        };
    },
});
</script>

<style scoped>
@media (min-width: 992px) {
    .auth-layout__left {
        flex: 0 0 45%;
        max-width: 45%;
    }
    .auth-layout__right {
        flex: 0 0 55%;
        max-width: 55%;
    }
}
</style>
