<template>
    <!-- Header Row -->
    <li
        class="grid grid-cols-[1fr_2fr_2fr] gap-4 pl-9 pr-12 py-2 font-semibold sticky top-0 z-10 bg-white"
    >
        <div>Code</div>
        <div>Name</div>
        <div>Rate</div>
    </li>

    <el-option
        v-for="taxes in props.data"
        :key="String(taxes.label)"
        :label="taxes.label"
        :value="taxes"
        v-bind="$attrs"
    >
        <div class="grid grid-cols-[1fr_2fr_2fr] gap-4 px-3 py-1">
            <span>{{ taxes.code }}</span>
            <span>{{ taxes.name }}</span>
            <span>{{ taxes.rate }}</span>
        </div>
    </el-option>
</template>

<script lang="ts" setup>
import type { Option } from "~/types/form";

interface TaxesOption extends Option {
    code?: string | null;
    name?: string | null;
    rate?: string | null;
}

interface Props {
    data?: TaxesOption[];
}

const props = withDefaults(defineProps<Props>(), {
    data: () => [] as TaxesOption[],
});
</script>
