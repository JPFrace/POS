<template>
    <Datatable
        v-loading="loading"
        style="width: 100%"
        :data="users"
        element-loading-text="Loading..."
        @selection-change="selectionChange"
    >
        <el-table-column type="selection" width="55" />
        <el-table-column prop="name" label="Name" />
        <el-table-column prop="email" label="Email" />
        <el-table-column label="Roles">
            <template #default="scope">
                <div class="space-x-2">
                    <Badge v-for="role of scope.row.roles">
                        {{ role.role.name }}
                    </Badge>
                </div>
            </template>
        </el-table-column>
        <el-table-column prop="created_at" label="Created" />
        <el-table-column label="Actions" width="150" class-name="!static">
            <template #default="scope">
                <div class="space-x-standard text-end">
                    <Delete :uuid="scope.row.uuid" :title="scope.row.name" />
                    <Edit :data="scope.row" />
                    <More :data="scope.row" />
                </div>
            </template>
        </el-table-column>
    </Datatable>
    <div class="flex items-center justify-end mt-2">
        <Pagination
            v-model:current="page"
            v-model:size="size"
            :total="total"
            :sizes="sizes"
        />
    </div>
</template>

<script lang="ts" setup>
import type { User } from "~/types/user";
import Delete from "../actions/delete.vue";
import Edit from "../actions/edit.vue";
import More from "../actions/more.vue";

const { $bus } = useNuxtApp();

const page = ref(1);
const size = ref(10);
const search = ref();
const sizes = ref([10, 30, 50, 100]);

const client = useSanctumClient();
const { data, refresh, status } = useAsyncData(
    "users",
    () =>
        client("/api/security/users", {
            method: "GET",
            params: {
                query: { "roles.role": true, ...search.value },
                page: page.value,
                size: size.value,
            },
        }),
    {
        server: false,
        lazy: true,
        watch: [page, search, size],
    },
);

const users = computed(() => data.value?.data ?? []);

const loading = computed(() => status.value == "pending");

const total = computed(() => data.value?.meta?.total ?? 0);

const selected = defineModel("selected");

const selectionChange = (value: User[]) => {
    $bus.emit("users:select", value);
};

onBeforeUnmount(() => {
    $bus.off("users:refresh");
    $bus.off("users:search");
});

onMounted(async () => {
    $bus.on("users:refresh", () => {
        refresh();
    });

    $bus.on("users:search", (query: object) => {
        search.value = { ...search.value, ...query };
    });
});

watch(users, (value) => {
    console.log(value);
});
</script>
