<template>
    <Select
        v-model:data="data"
        v-model:selected="selected"
        url="/api/test"
        remote
        loading
        multiple
        group
    />
    <span class="mt-2">Result: {{ selected }}</span>
</template>
<script lang="ts" setup>
import { ref } from "vue";
import type { Option } from "~/types/form";

const data = ref<Option[]>([]);
const selected = ref<Option[]>();
</script>
