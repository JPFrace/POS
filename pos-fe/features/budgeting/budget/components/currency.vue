<template>
    <div>
        <input
            v-model="model"
            :placeholder="placeholder"
            :class="`form-control ${classNames(valid)}`"
            v-currency
        />
    </div>
</template>
<script lang="ts" setup>
import type { Form } from "~/types/form";

const props = withDefaults(defineProps<Partial<Form>>(), {
    type: "text",
    isValid: undefined,
});

const model = defineModel<unknown>();

const placeholder = computed(() =>
    props.placeholder ? props.placeholder : props.title
);

const valid = computed(() =>
    props.isValid ? "is-valid" : props.isValid === false ? "is-invalid" : ""
);

const vCurrency = {
    beforeUpdate: (el: any, binding: any, vnode: any, prevNode: any) => {
        el.value = currencyFormat(el.value);
    },
};
</script>
