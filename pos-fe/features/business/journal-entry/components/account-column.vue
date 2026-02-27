<template>
    <li class="px-4">
        <NewChartAccount />
    </li>
    <li
        class="flex items-center justify-between gap-y-3 gap-x-4 pl-6 pr-12 py-2"
    >
        <div class="w-2/3 text-start">Account</div>
        <div class="w-2/3 text-end">Department</div>
        <div class="flex-1 text-end">Type</div>
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
                <span class="text-blue-400 font-bold"> {{ item.code }}</span>
                <span>- {{ item.label }}</span>
            </div>
            <div class="flex-1 text-end">
                {{ item.department?.name ?? "None" }}
            </div>
            <div class="text-end">
                {{ item.type }}
            </div>
        </template>
    </el-option>
</template>

<script lang="ts" setup>
import type { Option } from "~/types/form";
import NewChartAccount from "../actions/new-chart-account.vue";

interface Props {
    data?: Option[];
}

const props = withDefaults(defineProps<Props>(), {
    data: [],
});
</script>
