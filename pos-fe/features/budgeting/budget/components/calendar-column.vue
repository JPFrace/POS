<template>
    <li
        class="flex items-center justify-between gap-y-3 gap-x-4 pl-6 pr-12 py-2"
    >
        <div class="w-2/3 text-start">Name</div>
        <div class="w-1/3">Range</div>
    </li>

    <el-option
        v-for="item in data"
        :key="item.id"
        :label="item.label"
        :value="item"
        class="flex items-center justify-start gap-y-3 gap-x-4"
        v-bind="$attrs"
    >
        <template #default>
           <div class="flex gap-x-2">
                <span class="text-blue-400 font-bold">{{ item.year }}</span>
            </div>
            <div class="flex-1 text-end">
                <div>{{ item.label }}</div>
            </div>
        </template>
    </el-option>
</template>

<script lang="ts" setup>
import type { Option } from "~/types/form";

interface Props {
    data?: Option[];
}

const props = withDefaults(defineProps<Props>(), {
    data: [],
});
</script>
