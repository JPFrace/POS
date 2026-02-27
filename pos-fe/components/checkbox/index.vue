<template>
    <div
        :class="`form-check form-check-custom ${classNames(solid, size, variant)}`"
    >
        <input
            :id="id"
            v-model="model"
            class="form-check-input"
            type="checkbox"
            :name="name"
            v-bind="$attrs"
        />
        <label
            v-if="!$slots.default"
            class="form-check-label"
            :for="id"
            :class="{
                'underline underline-offset-4 decoration-danger':
                    isValid === false,
            }"
        >
            {{ label }}
        </label>
        <slot />
    </div>
</template>

<script lang="ts" setup>
import { computed } from "vue";

interface Props {
    label: string | number;
    value: any;
    id?: any;
    name: string;
    "v-model": unknown;
    solid?: boolean;
    variant: "success" | "danger" | "warning";
    size: "sm" | "lg" | number;
    isValid: Boolean | null;
}

const props = defineProps<Partial<Props>>();

const solid = computed(() => (props.solid ? "form-check-solid" : ""));

const size = computed(() => (props.size ? "form-check-" + props.size : ""));

const variant = computed(() =>
    props.variant ? "form-check-" + props.variant : ""
);

const model = defineModel<any>();
</script>
