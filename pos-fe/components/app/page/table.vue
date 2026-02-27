<template>
    <Datatable
        v-loading="loading"
        style="width: 100%"
        :data="data"
        element-loading-text="Loading..."
        @selection-change="selectionChange"
        row-key="uuid"
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
import type { Method } from "~/types/common";

interface Props {
    endpoint: string;
    method: Method["method"];
    transform: Function;
    params: any;
    prefixKey: string;
    selection: Function;
}

const props = withDefaults(defineProps<Partial<Props>>(), {
    method: "GET",
});

const key = id((props.prefixKey ?? "") + useRoute().fullPath);
const page = ref(1);
const size = ref(10);
const sizes = ref([10, 30, 50, 100]);

const search = defineModel<object>("search");
const params = defineModel<{ query: object }>("params");

const { receive, dismiss, send } = usePageEvent();
const client = useSanctumClient();

const {
    data: asData,
    refresh,
    status,
} = useAsyncData(
    key,
    () =>
        client(props.endpoint, {
            method: props.method,
            params: {
                ...params.value,
                query: {
                    ...(params.value?.query ?? {}),
                    ...search.value,
                },
                page: page.value,
                size: size.value,
            },
        }),
    {
        server: false,
        lazy: true,
        watch: [page, search, size, params],
    }
);

const data = computed(() => {
    var d = asData.value?.data ?? [];
    if (typeof props.transform == "function") {
        return props.transform(d);
    }

    return d;
});

const loading = computed(() => status.value == "pending");

const total = computed(() => asData.value?.meta?.total ?? 0);

const selected = defineModel("selected");

const model = defineModel("data");

watch(asData, (value) => {
    model.value = value;
});

const selectionChange = (value: any[]) => {
    selected.value = value;

    if (typeof props.selection == "function") {
        props.selection(value);
    }

    send(`${props.prefixKey ?? ""}select`, value);
};

onBeforeUnmount(() => {
    dismiss(`${props.prefixKey ?? ""}refresh`);
    dismiss(`${props.prefixKey ?? ""}search`);
});

onMounted(async () => {
    receive(`${props.prefixKey ?? ""}refresh`, () => {
        refresh();
    });

    receive(`${props.prefixKey ?? ""}search`, (query: object) => {
        search.value = { ...search.value, ...query };
    });
});
</script>
