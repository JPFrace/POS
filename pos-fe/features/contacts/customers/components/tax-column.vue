<template>
    <li class="flex items-center justify-between gap-y-3 pl-9 pr-12 py-2">
        <div class="w-2/3 text-start">Code</div>
        <div class="flex-1 text-start">Rate</div>
    </li>

    <el-option
        v-for="tax in data"
        :key="tax.value"
        :label="tax.label"
        :value="tax"
        v-bind="$attrs"
    >
        <div class="flex items-center justify-between px-3 py-1 gap-4">
            <span class="text-blue-400 font-bold w-2/3">{{ tax.code }}</span>
            <span>-</span>
            <span class="flex-1">{{ tax.rate }}</span>
        </div>
    </el-option>
</template>

<script lang="ts" setup>
import type { Option } from "~/types/form";

interface Props {
    data?: Option[];
}

withDefaults(defineProps<Props>(), {
    data: () => [] as Option[],
});
</script>
