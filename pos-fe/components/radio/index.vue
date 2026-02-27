<template>
    <div
        :class="`form-check form-check-custom  ${classNames(solid, variant, valid)}`"
    >
        <input
            v-model="model"
            class="form-check-input"
            type="radio"
            :id="id"
            :name="name"
            :value="value"
            v-bind="$attrs"
        />
        <label v-if="label && !$slots.label" class="form-check-label" :for="id">
            {{ label }}
        </label>

        <div v-if="$slots.label">
            <slot name="label" :label="label" :id="id" />
        </div>
    </div>
</template>

<script lang="ts" setup>
import { computed } from "vue";

interface Props {
    label?: string | number;
    value?: unknown;
    id?: string;
    name?: string;
    "v-model"?: unknown;
    solid?: boolean;
    variant?: "success" | "danger" | "warning";
    isValid: boolean;
}

const props = defineProps<Partial<Props>>();

const solid = computed(() => (props.solid ? "form-check-solid" : ""));

const variant = computed(() =>
    props.variant ? "form-check-" + props.variant : ""
);

const valid = computed(() =>
    props.isValid ? "is-valid" : props.isValid === false ? "is-invalid" : ""
);
const model = defineModel<unknown>();
</script>
