<template>
    <div class="row">
        <div class="col">
            <h1 style="display: flex; align-items: center">
                <el-icon> <Notebook /> </el-icon>&nbsp;My Bookmarks
            </h1>
        </div>
        <div class="col mb-1">
            <p>Filter by Group:&nbsp;</p>
            <BookmarkGroupSelect
                v-model:groups="bookmarkGroups"
                v-model:selected="selectedGroup"
            />
        </div>
    </div>
    <el-table :data="bookmarks" style="width: 100%">
        <el-table-column label="Name">
            <template #default="scope">
                {{
                    scope.row.name == "" || scope.row.name == null
                        ? scope.row.report_details.name
                        : scope.row.name
                }}
            </template>
        </el-table-column>
        <el-table-column>
            <template #default="scope">
                <el-button plain @click="redirect(scope.row)" icon="">
                    <el-icon>
                        <View />
                    </el-icon>
                </el-button>
            </template>
        </el-table-column>
    </el-table>
</template>
<script lang="ts" setup>
import type { Bookmark } from "~/types/bookmark";
import { View, Notebook } from "@element-plus/icons-vue";
import { ref } from "vue";
import BookmarkGroupSelect from "./bookmark-group-select.vue";

const bookmarks = ref<Bookmark[]>([]);
const selectedGroup = ref("All");
const bookmarkGroups = ref<string[]>([]);
const client = useSanctumClient();
const { data, error, refresh, execute } = await useAsyncData(
    "bookmarks",
    () =>
        client("/api/reports/bookmarks", {
            method: "GET",
            params: {
                query: {
                    userBookmark: true,
                    group:
                        selectedGroup.value == "All" ? "" : selectedGroup.value,
                },
            },
        }),
    {
        server: false,
        lazy: true,
        immediate: true,
    }
);

watch(data, () => {
    bookmarks.value = (data.value as { data?: Bookmark[] })?.data ?? [];
    if (
        bookmarkGroups.value.length == 0 ||
        bookmarks.value.length > bookmarkGroups.value.length
    ) {
        bookmarkGroups.value = [
            "All",
            ...new Set(
                (bookmarks.value ?? [])
                    .map((u) => u.group)
                    .filter(
                        (v): v is string => typeof v === "string" && v !== ""
                    )
            ),
        ];
    }
});

const redirect = async (value: any) => {
    const data = {
        template: value.report_details,
        date_from: value.date_from,
        date_to: value.date_to,
    };
    const sharedData = useState("reportData", () => data);
    sharedData.value = data;
    await navigateTo("/reports");
};

watch(selectedGroup, async () => {
    await refresh();
    bookmarks.value = (data.value as { data?: Bookmark[] })?.data ?? [];
});
</script>
