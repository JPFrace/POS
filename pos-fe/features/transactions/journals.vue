<template>
    <div class="flex justify-between items-center mt-1 mb-1">
        <div class="max-w-xs">
            <Input group placeholder="Search">
                <template #default>
                    <InputNative
                        v-model="search"
                        placeholder="Search entries..."
                        title="Type and enter key to search"
                        @keyup.enter="handleSearch"
                    />
                </template>
                <template #append>
                    <div>
                        <KTIcon
                            title="Click me to search"
                            icon-name="click"
                            icon-class="fs-2 cursor-pointer"
                            @click="handleSearch"
                        />
                    </div>
                </template>
            </Input>
        </div>
        <div class="flex gap-4 items-center mr-8">
            <div class="flex flex-col gap-1 mb-4">
                <span class="text-sm font-medium">Filter by Status</span>
                <Select
                    v-model="selectedStatus"
                    url="/api/business/journal-entry-statuses"
                    :map-result="mapStatuses"
                    remote
                    style="width: 170px"
                    size="medium"
                />
            </div>
            <el-dropdown split-button type="primary" size="large">
                <NuxtLink
                    to="/business/journal-entry"
                    class="flex items-center text-white"
                >
                    <el-icon class="mr-2"><Plus /></el-icon>
                    New Entry
                </NuxtLink>
                <template #dropdown>
                    <el-dropdown-menu>
                        <PostStatus
                            :selected-rows="selectedRows"
                            :on-success="handlePostSuccess"
                        />
                    </el-dropdown-menu>
                </template>
            </el-dropdown>
        </div>
    </div>
    <AppPageTable
        v-model:params="params"
        :prefix-key="tab"
        endpoint="/api/business/journal-entries"
        :preserve-expanded-content="true"
        class="min-w-[1000px]"
        @selection-change="handleSelectionChange"
    >
        <template #columns>
            <el-table-column
                type="selection"
                width="40"
                :selectable="isRowSelectable"
            >
                <template #default="{ row }">
                    <el-checkbox
                        v-if="row.status.name.toLowerCase() !== 'posted'"
                        :model-value="
                            selectedRows.some((r) => r.uuid === row.uuid)
                        "
                        @change="toggleSelection(row)"
                    />
                    <el-icon
                        v-else
                        class="!text-gray-400 !text-xl"
                        title="Posted entries are locked"
                    >
                        <Lock />
                    </el-icon>
                </template>
            </el-table-column>
            <el-table-column type="expand">
                <template #default="props">
                    <div class="w-1/2 px-8 py-4">
                        <el-table
                            :data="props.row.details"
                            :border="true"
                            show-summary
                            :summary-method="summary"
                            sum-text="Total"
                        >
                            <el-table-column
                                prop="chart_account.name"
                                label="Account"
                            >
                                <template #default="scope">
                                    <div class="flex flex-col gap-y-1">
                                        <span class="text-blue-400 font-bold">{{
                                            scope.row.chart_account.name
                                        }}</span>
                                        <span
                                            class="text-slate-400 text-xs italic"
                                            >{{
                                                scope.row.chart_account.code
                                            }}</span
                                        >
                                    </div>
                                </template>
                            </el-table-column>
                            <el-table-column
                                label="Debit"
                                prop="debit"
                                class-name="text-end"
                            >
                                <template #default="scope">
                                    <div class="flex flex-col gap-y-1">
                                        {{ money(scope.row.debit, 2) }}
                                    </div>
                                </template>
                            </el-table-column>
                            <el-table-column
                                label="Credit"
                                prop="credit"
                                class-name="text-end"
                            >
                                <template #default="scope">
                                    <div class="flex flex-col gap-y-1">
                                        {{ money(scope.row.credit, 2) }}
                                    </div>
                                </template>
                            </el-table-column>
                        </el-table>
                    </div>
                </template>
            </el-table-column>
            <el-table-column prop="date" label="Date" min-width="40" sortable />
            <el-table-column
                prop="ref_no"
                label="Ref No"
                min-width="50"
                sortable
            >
                <template #default="{ row }">
                    <div class="flex flex-col gap-y-1">
                        <NuxtLink
                            :to="`/business/journal-entry/${row.uuid}`"
                            target="_blank"
                            class="font-bold !text-blue-500 hover:!underline underline-offset-2"
                        >
                            {{ row.ref_no }}
                        </NuxtLink>
                    </div>
                </template>
            </el-table-column>
            <el-table-column
                prop="je_no"
                label="JE No"
                min-width="50"
                sortable
            />
            <el-table-column
                prop="memo"
                label="Memo / Particulars"
                min-width="120"
            >
                <template #default="scope">
                    <div class="flex flex-col gap-y-1">
                        <span
                            class="font-bold line-clamp-2"
                            :title="scope.row.memo"
                            >{{ scope.row.memo }}</span
                        >
                    </div>
                </template>
            </el-table-column>
            <el-table-column
                prop="amount"
                label="Amount"
                min-width="40"
                sortable
            >
                <template #default="{ row }">
                    <div class="flex flex-col gap-y-1">
                        ₱{{ money(row.amount, 2) }}
                    </div>
                </template>
            </el-table-column>

            <el-table-column
                prop="status.name"
                label="Status"
                min-width="40"
                sortable
            >
                <template #default="{ row }">
                    <div class="flex justify-between items-center">
                        <BadgeElTag
                            :label="row.status.name"
                            :status="row.status.name"
                        />
                    </div>
                </template>
            </el-table-column>

            <el-table-column
                label="Actions"
                min-width="50"
                class-name="!static"
            >
                <template #default="{ row }">
                    <div class="flex space-x-standard mr-2">
                        <template v-if="isPosted(row)">
                            <KTIcon
                                title="Edit"
                                icon-name="notepad-edit"
                                icon-type="outline"
                                icon-class="!text-3xl cursor-not-allowed !text-gray-400"
                                @click="
                                    $message(
                                        'warning',
                                        t('journal.validation.cannot_edit')
                                    )
                                "
                            />
                        </template>
                        <template v-else>
                            <NuxtLink
                                :to="`/business/journal-entry/${row.uuid}`"
                            >
                                <KTIcon
                                    title="Edit"
                                    icon-name="notepad-edit"
                                    icon-type="outline"
                                    icon-class="!text-3xl cursor-pointer !text-blue-500 hover:!text-blue-700 dark:hover:!text-blue-400"
                                />
                            </NuxtLink>
                        </template>
                        <UnpostStatus
                            :row="row"
                            :on-success="handleUnpostSuccess"
                        />
                        <AppPageMore>
                            <CancelAction />
                            <DeleteAction :row="row" :tab="tab" />
                        </AppPageMore>
                    </div>
                </template>
            </el-table-column>
        </template>
    </AppPageTable>
</template>

<script setup lang="ts">
import moment from "moment";
import { h } from "vue";
import type { VNode } from "vue";
import type { TableColumnCtx } from "element-plus";
import type { Option } from "~/types/form";
import { money } from "~/utils/helper";
import type { JournalItem } from "~/types/journal-entry";
import { Plus, Lock } from "@element-plus/icons-vue";
import PostStatus from "./components/journal-actions/post.vue";
import UnpostStatus from "./components/journal-actions/unpost.vue";
import CancelAction from "./components/journal-actions/more/cancel.vue";
import DeleteAction from "./components/journal-actions/more/delete.vue";

const { receive } = usePageEvent();
const search = ref();
const selectedRows = ref<any[]>([]);
const tab = ref("journal-entries");

const { t } = useI18n();
const { $message } = useNuxtApp();

const dates = defineModel<Date[]>("dates", {
    default: () => [new Date(), new Date()],
});

const params = ref({
    query: {
        details: true,
        account: true,
        status: true,
        date_between: [
            moment(dates.value[0]).format("YYYY-MM-DD"),
            moment(dates.value[1]).format("YYYY-MM-DD"),
        ],
        search: undefined as string | undefined,
        filter_status: undefined as string | undefined,
    },
    order_by: JSON.stringify(["date", "desc"]),
});

const toggleSelection = (row: any) => {
    const index = selectedRows.value.findIndex((r) => r.uuid === row.uuid);
    if (index > -1) {
        selectedRows.value.splice(index, 1);
    } else {
        selectedRows.value.push(row);
    }
};

const isRowSelectable = (row: any) => {
    return row.status.name.toLowerCase() !== "posted";
};

const handleSelectionChange = (rows: any[]) => {
    selectedRows.value = rows;
};

const handlePostSuccess = () => {
    // Refresh the table and clear selection
    params.value = { ...params.value };
    selectedRows.value = [];
};

const handleUnpostSuccess = () => {
    params.value = { ...params.value };
};

const isPosted = (row: any) => {
    return row.status.name.toLowerCase() === "posted";
};

const selectedStatus = ref<Option | null>({
    id: null,
    value: null,
    label: "All Statuses",
});

const mapStatuses = (res: any) => [
    { id: null, value: null, label: "All Statuses" },
    ...res.data.map((row: any) => ({
        id: row.uuid,
        value: row.uuid,
        label: row.name,
    })),
];

const handleSearch = () => {
    params.value = {
        ...params.value,
        query: {
            ...params.value.query,
            search: search.value,
        },
    };
};

interface SummaryMethodProps<T = JournalItem> {
    columns: TableColumnCtx<T>[];
    data: T[];
}

const summary = (param: SummaryMethodProps) => {
    const { columns, data } = param;
    const sums: (string | VNode)[] = [];
    columns.forEach((column, index) => {
        if (index === 0) {
            sums[index] = h("div", { style: { textDecoration: "underline" } }, [
                "Total",
            ]);
            return;
        }

        const property = column.property as keyof JournalItem;
        const values = data.map((item) => Number(item[property]));

        if (!values.every((value) => Number.isNaN(value))) {
            sums[index] = `Php ${money(
                values.reduce((prev, curr) => {
                    const value = Number(curr);
                    if (!Number.isNaN(value)) {
                        return prev + curr;
                    } else {
                        return prev;
                    }
                }, 0),
                2
            )}`;
        } else {
            sums[index] = "N/A";
        }
    });

    return sums;
};

watch(dates, (value) => {
    params.value = {
        ...params.value,
        query: {
            ...params.value.query,
            date_between: [
                moment(value[0]).format("YYYY-MM-DD"),
                moment(value[1]).format("YYYY-MM-DD"),
            ],
        },
    };
});

watch(selectedStatus, (value) => {
    params.value = {
        ...params.value,
        query: {
            ...params.value.query,
            filter_status: value?.value as string | undefined,
        },
    };
});

onMounted(() => {
    receive("refresh", (id: any) => {
        if (id && id !== tab.value) {
            return;
        }
        params.value = { ...params.value };
    });
});
</script>
