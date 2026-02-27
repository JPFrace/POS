<template>
    <i
        :class="`ki-${currentIconType} ki-${props.iconName}${props.iconClass ? ' ' + props.iconClass : ''}`"
        @click="handleClick"
        role="button"
        aria-label="Share on social media"
    >
        <template v-if="icons[props.iconName] && currentIconType === 'duotone'">
            <span
                v-for="i in icons[props.iconName]"
                :key="i"
                :class="`path${i}`"
            />
        </template>
    </i>
</template>

<script setup lang="ts">
import { computed } from "vue";
import icons from "./icons.json";
import { useConfigStore } from "~/stores/config";

const store = useConfigStore();

const props = defineProps({
    iconName: { type: String, default: "", required: true },
    iconType: {
        type: String,
        default: "",
        required: false,
    },
    iconClass: { type: String, default: "", required: false },
    shareUrl: { type: String, default: "", required: true },
});

const currentIconType = computed(() => {
    return props.iconType ? props.iconType : store.config.general.iconsType;
});
onMounted(() => {
    try {
        console.log("Props on load:", props); // Log the entire props object
    } catch (error) {
        console.error("Error logging props:", error);
    }
});

watch(
    () => props,
    (newProps) => {
        console.log("Updated props:", newProps);
    },
    { deep: true } // This ensures that nested properties are also tracked
);

// const handleClick = () => {
//     share({
//         url: props?.shareUrl,
//         networks: [props?.iconName], // Assuming iconName corresponds to a valid network
//     });
// };
</script>
