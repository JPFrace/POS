<template>
    <el-pagination
        v-model:current-page="current"
        v-model:page-size="size"
        background
        layout="total, sizes, prev, pager, next"
        :total="total"
        :page-sizes="sizes"
        @update:current-page="(page) => console.log(page)"
        @size-change="(value) => (size = value)"
    />
</template>

<script lang="ts" setup>
interface Props {
    total: number;
    sizes: number[];
}

defineProps<Props>();

const size = defineModel<number>("size");
const current = defineModel<number>("current");
</script>
