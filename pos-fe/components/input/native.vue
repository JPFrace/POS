<template>
    <label v-if="block && !$slots.label" :for="name" class="form-label"
        >{{ label }}
        <span v-if="topDesc" class="text-xs ml-2">({{ topDesc }})</span></label
    >

    <div v-if="$slots.label">
        <slot name="label" :label="label" />
    </div>

    <input
        v-model="model"
        :class="`form-control ${classNames(solid, transparent, valid, size)}`"
        v-bind="{ ...attachableProps, ...$attrs }"
    />
</template>
<script lang="ts" setup>
import type { Form } from "~/types/form";

const props = withDefaults(
    defineProps<
        Omit<Form, "solid" | "group" | "float" | "modelValue" | "size">
    >(),
    {
        type: "text",
        isValid: undefined,
    }
);

const attachableProps = computed(() => ({
    ...props,
    solid: null,
    group: null,
    transparent: null,
    float: null,
    modelValue: null,
    size: null,
    block: null,
}));

const model = defineModel<unknown>();

const solid = computed(() => (props.solid ? "form-control-solid" : ""));

const valid = computed(() =>
    props.isValid ? "is-valid" : props.isValid === false ? "is-invalid" : ""
);

const size = computed(() => {
    const sizes = {
        md: "py-4",
        lg: "py-6",
    };

    return sizes[props.size] ?? "";
});

const transparent = computed(() =>
    props.transparent ? "form-control-transparent" : ""
);

onMounted(() => {
    // console.log([K in keyof Form])
});
</script>
