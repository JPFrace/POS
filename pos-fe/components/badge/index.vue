<template>
    <span :class="`badge ${classNames(type, variant, outline)}`">
        <span v-if="!$slots.default"> {{ label }}</span>
        <slot />
    </span>
</template>

<script lang="ts" setup>
interface Props {
    type?: "light" | "square" | "circle" | "outline";
    variant?:
        | "primary"
        | "secondary"
        | "success"
        | "info"
        | "warning"
        | "danger"
        | "dark"
        | null;
    label?: string | number;
    outline?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    variant: "primary",
    bordered: false,
    dismissabled: false,
    outline: false,
});

const variant = computed(() =>
    props.type == "light"
        ? "badge-light-" + props.variant
        : "badge-" + props.variant
);

const type = computed(() =>
    props.type != "light" ? "badge-" + props.type : ""
);

const outline = computed(() => (props.outline ? "badge-outline" : ""));
</script>
