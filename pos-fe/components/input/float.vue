<template>
    <div class="form-floating" :class="parentClass">
        <InputNative
            v-bind="{ ...props, ...$attrs }"
            v-model="model"
            :placeholder="placeholder"
        />
        <label :for="id">{{ label }}</label>
    </div>
</template>
<script lang="ts" setup>
import type { Form } from "~/types/form";

const props = withDefaults(defineProps<Partial<Form>>(), {
    type: "text",
    isValid: undefined,
});

const model = defineModel<unknown>();

const label = computed(() => (props.label ? props.label : props.title));
const placeholder = computed(() =>
    props.placeholder ? props.placeholder : props.title
);
</script>
