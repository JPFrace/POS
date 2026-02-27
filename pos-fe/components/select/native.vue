<template>
    <select
        v-model="model"
        :class="`form-select ${classNames(solid, transparent)}`"
    >
        <slot />
    </select>
</template>

<script lang="ts" setup>
import { computed } from "vue";

interface Props {
    solid?: boolean;
    transparent?: boolean;
}

const props = withDefaults(defineProps<Props>(), {});

const model = defineModel();

const solid = computed(() => (props.solid ? "form-select-solid" : ""));
const transparent = computed(() =>
    props.transparent ? "form-select-transparent" : ""
);
</script>
