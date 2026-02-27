<template>
    <button
        :disabled="disabled"
        :class="`btn ${classNames(
            ...variant,
            outline,
            dashed,
            hover,
            ...iconColor,
            ...textColor,
            ...active
        )}`"
    >
        <KTIcon
            v-if="icon || loading"
            :icon-name="icon"
            class="fs-1"
            :class="{
                'animate-spin': loading,
            }"
        />

        <slot name="icon" />

        <slot />
        {{ !$slots.default && label ? label : "" }}
    </button>
</template>

<script lang="ts" setup>
import { computed } from "vue";
import { colors } from "~/core/helpers/system";

interface Colors {
    color?:
        | "light"
        | "primary"
        | "secondary"
        | "success"
        | "info"
        | "warning"
        | "danger"
        | "dark"
        | string;
    active?:
        | "light"
        | "primary"
        | "secondary"
        | "success"
        | "info"
        | "warning"
        | "danger"
        | "dark"
        | string;
}

interface Props {
    variant?:
        | "light"
        | "primary"
        | "secondary"
        | "success"
        | "info"
        | "warning"
        | "danger"
        | "dark";
    outline?: boolean;
    dashed?: boolean;
    active?: boolean;
    light?: boolean;
    hover?: boolean;
    rotate?: boolean;
    rotateEnd?: boolean;
    elevate?: boolean;
    elevateDown?: boolean;
    scale?: boolean;
    icon?: string;
    textColor?: Colors;
    iconColor?: Colors;
    loading?: boolean;
    loadingText?: string;
    label?: string;
    disabled?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    variant: "primary",
    disabled: false,
});

const outline = computed(() => (props.outline ? "btn-outline" : ""));

const dashed = computed(() => (props.dashed ? "btn-outline-dashed" : ""));

const variant = computed(() => {
    const classes: string[] = [];
    if (!outline.value && props.light) {
        classes.push("btn-light-" + props.variant);
    } else if (outline.value) {
        classes.push("btn-outline-" + props.variant);
    } else {
        classes.push("btn-" + props.variant);
    }

    return classes;
});

const hover = computed(() => {
    if (props.hover) {
        if (props.elevate) {
            return "hover-elevate-up";
        } else if (props.elevateDown) {
            return "hover-elevate-down";
        } else if (props.scale) {
            return "hover-scale";
        } else if (props.rotate) {
            return "hover-rotate-start";
        } else if (props.rotateEnd) {
            return "hover-rotate-end";
        }
    }

    return "";
});

const iconColor = computed(() => {
    const classes: string[] = [];
    if (props.iconColor?.color) {
        classes.push(
            colors.includes(props.iconColor.color)
                ? "btn-icon-" + props.iconColor.color
                : props.iconColor.color
        );
    }
    if (props.iconColor?.active) {
        classes.push("btn-active-icon-" + props.iconColor.active);
    }

    return classes;
});

const textColor = computed(() => {
    const classes: string[] = [];
    if (props.textColor?.color) {
        classes.push(
            colors.includes(props.textColor.color)
                ? "btn-color-" + props.textColor.color
                : props.textColor.color
        );
    }
    if (props.textColor?.active) {
        classes.push("btn-active-" + props.textColor.active);
    }

    return classes;
});

const active = computed(() => {
    const classes: string[] = [];
    if (props.active) {
        if (props.light) {
            classes.push("btn-active-light-" + props.variant);
        } else {
            classes.push("btn-active-" + props.variant);
        }
    }

    return classes;
});

const icon = computed(() => (props.loading ? "loading" : props.icon));
</script>

<style scoped>
button:disabled {
    cursor: not-allowed;
    pointer-events: auto;
}
</style>
