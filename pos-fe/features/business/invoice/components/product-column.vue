<template>
    <li class="px-4">
        <NewProduct />
    </li>
    <li class="flex items-center justify-between gap-y-3 pl-6 pr-12 py-2">
        <div class="w-2/3 text-start">Name</div>
        <div class="flex-1 text-start">Chart Account</div>
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
                <span>{{ item.label }}</span>
            </div>
            <div class="flex-1 text-end">
                {{ item.income_account?.name ?? "None" }}
            </div>
        </template>
    </el-option>
</template>

<script lang="ts" setup>
import type { Option } from "~/types/form";
import NewProduct from "../actions/new-product.vue";

interface Props {
    data?: Option[];
}

const props = withDefaults(defineProps<Props>(), {
    data: [],
});
</script>
