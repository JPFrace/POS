<template>
    <div class="input-group">
        <span v-if="$slots.prepend" class="input-group-text">
            <slot name="prepend" />
        </span>
        <slot name="default" />
        <InputNative
            v-if="!$slots.default"
            v-bind="{ ...props, ...$attrs }"
            v-model="model"
        />
        <span v-if="$slots.append" class="input-group-text">
            <slot name="append" />
        </span>
    </div>
</template>

<script lang="ts" setup>
import type { Form } from "~/types/form";

const props = withDefaults(defineProps<Form>(), {
    type: "text",
    isValid: undefined,
});

const model = defineModel<unknown>();

const solid = computed(() => (props.solid ? "input-group-solid" : ""));

const size = computed(() => (props.size ? "input-group-" + props.size : ""));
</script>
