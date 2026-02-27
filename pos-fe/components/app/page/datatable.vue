<template>
    <Datatable
        :data="paginatedData"
        element-loading-text="Loading..."
        row-key="uuid"
        v-loading="loading"
        style="width: 100%"
        @selection-change="selectionChange"
        v-bind="{ ...$attrs }"
    >
        <slot name="columns" />
    </Datatable>

    <div class="flex items-center justify-center mt-2">
        <Pagination
            v-model:current="page"
            v-model:size="size"
            :total="total"
            :sizes="sizes"
        />
    </div>
</template>

<script lang="ts" setup>
interface Props {
    dataSource: any[];
    transform?: (data: any[]) => any[];
    selection?: (rows: any[]) => void;
    loading?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    dataSource: () => [],
    loading: false,
});

const page = ref(1);
const size = ref(10);
const sizes = ref([10, 30, 50, 100]);

const selected = defineModel("selected") as Ref<any[]>;
const model = defineModel("data") as Ref<any[]>;

const processedData = computed(() => {
    const d = props.dataSource ?? [];
    return typeof props.transform === "function"
        ? props.transform(d)
        : d;
});

const paginatedData = computed(() => {
    const start = (page.value - 1) * size.value;
    const end = start + size.value;
    return processedData.value.slice(start, end);
});

const total = computed(() => processedData.value.length);

watch(
    processedData,
    (value) => {
        model.value = value;
    },
    { immediate: true }
);

const selectionChange = (rows: any[]) => {
    selected.value = rows;

    if (typeof props.selection === "function") {
        props.selection(rows);
    }
};
</script>
