<template>
    <Select v-model:data="data" v-model:selected="selected" />
    <span class="mt-2">Result: {{ selected }}</span>
</template>
<script lang="ts" setup>
import { ref } from "vue";
import type { Option } from "~/types/form";

const data = ref<Option[]>([
    {
        id: 1,
        label: "Label 1",
        value: "Value 1",
    },
    {
        id: 2,
        label: "Label 2",
        value: "Value 2",
    },
    {
        id: 3,
        label: "Label 3",
        value: "Value 3",
    },
]);
const selected = ref<Option[]>();
</script>
