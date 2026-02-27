<template>
    <!--begin::Alert-->
    <div
        :class="`alert d-flex align-items-center p-5 ${classNames(variant, bordered, dismissabled)}`"
    >
        <!--begin::Icon-->
        <i
            v-if="!$slots.icon"
            :class="`ki-duotone ki-shield-tick fs-2hx me-4 ${classNames(iconColor)}`"
            ><span class="path1" /><span class="path2" />
        </i>
        <slot name="icon" />
        <!--end::Icon-->

        <!--begin::Wrapper-->
        <div class="d-flex flex-column">
            <!--begin::Title-->
            <h4 v-if="!$slots.title" class="mb-1">{{ title }}</h4>
            <slot name="title" />
            <!--end::Title-->

            <!--begin::Content-->
            <span v-if="!$slots.content">{{ content }}</span>
            <slot name="content" />
            <!--end::Content-->
        </div>
        <!--end::Wrapper-->
    </div>
    <!--end::Alert-->
</template>

<script lang="ts" setup>
interface Props {
    variant?: "primary" | "success" | "warning" | "danger";
    bordered?: boolean;
    dismissabled?: boolean;
    title?: string | null;
    content?: string | null;
    solid?: boolean;
    light?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    variant: "primary",
    bordered: false,
    dismissabled: false,
    title: null,
    content: null,
});

const variant = computed(() => {
    if (props.solid) {
        return "bg-" + props.variant;
    } else if (props.light) {
        return "bg-light-" + props.variant;
    }

    return "alert-" + props.variant;
});

const bordered = computed(() => (props.bordered ? "border" : ""));

const dismissabled = computed(() =>
    props.dismissabled ? "alert-dismissible" : ""
);

const iconColor = computed(() => {
    let color = "";
    if (props.solid) {
        color = "text-white";
    } else {
        color = "text-" + props.variant;
    }

    return color;
});
</script>
