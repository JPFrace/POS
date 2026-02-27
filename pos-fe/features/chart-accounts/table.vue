<template>
    <div class="overflow-auto">
        <AppPageTable
            :endpoint="`/api/transactions/${props.uuid}`"
            :params="{
                query: {},
            }"
            method="GET"
            class="min-w-[1200px]"
        >
            <template #columns>
                <el-table-column
                    prop="posted_at"
                    label="Transaction Date"
                    min-width="200"
                    sortable
                />
                <el-table-column
                    prop="ref_no"
                    label="Reference #"
                    min-width="200"
                    sortable
                />
                <el-table-column
                    prop="description"
                    label="Description"
                    min-width="200"
                    sortable
                />
                <el-table-column
                    prop="trans_type"
                    label="Transaction Type"
                    min-width="200"
                    sortable
                    :formatter="(_, __, value) => transactionType(value)"
                />
                <el-table-column label="Amount" min-width="200" sortable>
                    <template #default="scope">
                        {{ money(scope.row.transactable.amount, 2) }}
                    </template>
                </el-table-column>
            </template>
        </AppPageTable>
    </div>
</template>

<script setup lang="ts">
import { ref, watch } from "vue";
const { send } = usePageEvent();

interface Props {
    uuid: string;
    search?: string;
    dateFilter?: [string, string];
}

const props = defineProps<Props>();

const searchByDate = (start: string, end: string) => {
    send("search", {
        start_date: start,
        end_date: end,
    });
};

watch(
    () => props.search,
    () => {
        console.log("searching ", props.search);
        send(`${props.prefixKey ?? ""}search`, { ref_no: props.search || "" });
    },
);

watch(
    () => props.dateFilter,
    (val) => {
        if (!Array.isArray(val) || val.length !== 2) return;

        const [start, end] = val;

        console.log("date", start, end);

        searchByDate(start, end);
    },
    { deep: true },
);
</script>
