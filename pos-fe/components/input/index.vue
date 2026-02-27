<template>
    <div v-if="topDesc && float">
        {{ topDesc }}
    </div>
    <InputNative
        v-if="!float && !group"
        v-bind="{ ...props, ...$attrs }"
        v-model="model"
    />

    <InputFloat v-if="float" v-model="model" v-bind="{ ...props, ...$attrs }" />

    <InputGroup v-if="group" v-model="model" v-bind="{ ...props, ...$attrs }">
        <template v-if="$slots.default" #default>
            <slot name="default" />
        </template>
        <template v-if="$slots.prepend" #prepend>
            <slot name="prepend" />
        </template>
        <template v-if="$slots.append" #append>
            <slot name="append" />
        </template>
    </InputGroup>
</template>

<script lang="ts" setup>
import type { Form } from "~/types/form";

const props = withDefaults(defineProps<Form>(), {
    type: "text",
    isValid: undefined,
});

const model = defineModel();
</script>
