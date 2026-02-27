<template>
    <li class="flex items-center justify-start gap-y-3 pl-6 pr-12 py-2">
        <div v-for="column in columns" class="flex-1 text-start">
            {{ columnTitle(column) }}
        </div>
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
            <div v-for="column in columns" class="flex-1 text-start">
                {{ item[columnKey(column)] }}
            </div>
        </template>
    </el-option>
</template>

<script lang="ts" setup>
import { computed } from "vue";
import type { Option } from "~/types/form";

interface Props {
    data?: Option[];
}

const props = withDefaults(defineProps<Props>(), {
    data: [],
});

const columns = computed(() => props.data[0]?.columns ?? []);

const columnKey = (value: string | string[]): string => {
    let key = value;
    if (Array.isArray(value)) {
        key = value[0];
    }

    return key.toString();
};

const columnTitle = (value: string | string[]): string => {
    let title = value;
    if (Array.isArray(value)) {
        title = value[1];
    }

    return title.toString().replace(/\b\w/, (l) => l.toUpperCase());
};
</script>
