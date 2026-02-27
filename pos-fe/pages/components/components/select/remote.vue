<template>
    <Select
        v-model:data="data"
        v-model:selected="selected"
        url="/api/test"
        loading
        multiple
        remote
    />
    <span class="mt-2">Result: {{ selected }}</span>
</template>
<script lang="ts" setup>
import type { Option } from "~/types/form";

const data = defineModel<Option[]>("data", {
    default: [],
});
const selected = defineModel<Option[]>("selected", {
    default: null,
});
</script>
