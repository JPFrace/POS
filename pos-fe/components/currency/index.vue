<template>
    <div>
        <input
            v-model="model"
            type="text"
            :placeholder="placeholder"
            @blur="handleBlur"
            @change="onChange"
            :class="`form-control text-center ${classNames(valid)}`"
            v-bind="{ ...$attrs }"
        />
    </div>
</template>
<script lang="ts" setup>
import type { Form } from "~/types/form";
import { watch } from "vue";

const emits = defineEmits(["change"]);

const props = withDefaults(
    defineProps<Partial<Form> & { allowNegative?: boolean }>(),
    {
        type: "text",
        isValid: undefined,
        allowNegative: false,
    },
);

const model = defineModel<unknown>();

watch(model, (newVal: any, oldVal: any) => {
    const regex = props.allowNegative ? /[^\d.-]|(?<!^)-/g : /^0+|[^\d.]/g;
    let numVal = newVal ? newVal.toString().replace(regex, "") : ""; // Remove unwanted char in a number only field

    // Handle negative sign placement
    if (props.allowNegative && newVal && newVal.toString().includes("-")) {
        const hasNegative = newVal.toString().includes("-");
        const cleanVal = newVal.toString().replace(/-/g, "");
        if (hasNegative && !cleanVal.startsWith("-")) {
            numVal = "-" + cleanVal.replace(/[^\d.]/g, "");
        }
    }

    if (props.allowNegative && numVal === "-") {
        model.value = "-";
        return;
    }

    const numSplit = numVal.split("."); // Split the string into two using decimal point to determine if has decimal value

    if (numSplit.length == 1) {
        // First Condition determines if the input number HAS NO decimal point
        model.value = currencyFormat(numVal);
    } else if (numSplit.length == 2) {
        // Second Condition determines if the input number HAS decimal point
        model.value = numSplit[1] > 0 ? currencyFormat(numVal) : newVal;
    } else {
        // Third Condtion return the old value if detected any unwanted input
        model.value = oldVal;
    }
});

const handleBlur = () => {
    model.value = currencyFormat(model.value);
};

const onChange = () => {
    emits("change");
};

const placeholder = computed(() =>
    props.placeholder ? props.placeholder : props.title,
);

const valid = computed(() =>
    props.isValid ? "is-valid" : props.isValid === false ? "is-invalid" : "",
);
</script>
