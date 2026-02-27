<template>
    <!-- Header Row -->
    <li
        class="grid grid-cols-[1fr_2fr_2fr] gap-4 pl-9 pr-12 py-2 font-semibold"
    >
        <div>SKU</div>
        <div>Name</div>
        <div>Chart of Account</div>
    </li>

    <el-option
        v-for="product in props.data"
        :key="String(product.value ?? product.id ?? '')"
        :label="product.label"
        :value="product"
        v-bind="$attrs"
    >
        <div class="grid grid-cols-[1fr_2fr_2fr] gap-4 px-3 py-1">
            <span class="text-blue-400 font-bold">{{ product.sku }}</span>
            <span>{{ product.name }}</span>
            <span>{{ product.income_account?.name ?? "None" }}</span>
        </div>
    </el-option>
</template>

<script lang="ts" setup>
import type { Option } from "~/types/form";

interface ProductOption extends Option {
    sku?: string;
    name?: string | null;
    income_account?: {
        name?: string;
    };
}

interface Props {
    data?: ProductOption[];
}

const props = withDefaults(defineProps<Props>(), {
    data: () => [] as ProductOption[],
});
</script>
