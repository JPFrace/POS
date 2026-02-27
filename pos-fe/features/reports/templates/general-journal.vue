<template>
    <div class="p-16 w-full flex items-center justify-center report-container">
        <div
            class="w-[80%] bg-white p-4 flex flex-col gap-y-8 items-center justify-center preview-report"
        >
            <div class="w-full flex flex-col items-center justify-center">
                <h4>ST. SCHOLASTICA'S COLLEGE-WESTGROVE, INC.</h4>
                <p class="p-0 m-0">Ayala Westgrove Heights, Silang, Cavite</p>
                <p class="p-0 m-0">ALL DEPARTMENTS</p>
                <p class="p-0 m-0">General Journal</p>
                <p class="p-0 m-0 font-bold">
                    For the Period From {{ date1 }} to {{ date2 }}
                </p>
            </div>
            <div class="w-full">
                <table class="w-full [&_th]:align-top [&_td]:align-top">
                    <thead>
                        <tr class="uppercase !border-t-[2px] !border-b-[2px]">
                            <th class="w-[8%] text-start">Date</th>
                            <th class="w-[10%] text-start">Reference</th>
                            <th class="w-[27%] text-start">
                                Trans Description
                            </th>
                            <th class="w-[10%] text-start">Account ID</th>
                            <th class="w-[20%] text-start">
                                Account Description
                            </th>
                            <th class="w-[12%] text-end">Debit Amt</th>
                            <th class="w-[12%] text-end">Credit Amt</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template
                            v-for="(item, index) in tableRows"
                            :key="item.type === 'empty' ? `empty-${index}` : item.row.uuid"
                        >
                            <tr v-if="item.type === 'data'">
                                <td
                                    v-if="item.dateRefRowSpan > 0"
                                    :rowspan="item.dateRefRowSpan"
                                    class="align-top"
                                >
                                    {{ moment(item.row.posted_at).format("MM/DD/YYYY") }}
                                </td>
                                <td
                                    v-if="item.dateRefRowSpan > 0"
                                    :rowspan="item.dateRefRowSpan"
                                    class="align-top text-blue-500 hover:underline cursor-pointer print:text-black print:no-underline"
                                    @click="openEditLink(item.row.transactable)"
                                >
                                    {{ item.row.ref_no }}
                                </td>
                                <td>{{ item.row.description }}</td>
                                <td>{{ item.row.chart_account?.code }}</td>
                                <td>{{ item.row.chart_account?.name }}</td>
                                <td
                                    class="text-end"
                                    :class="{
                                        '!border-b-2': item.isLastDataRow,
                                    }"
                                >
                                    {{ money(item.row.debit, 2) }}
                                </td>
                                <td
                                    class="text-end"
                                    :class="{
                                        '!border-b-2': item.isLastDataRow,
                                    }"
                                >
                                    {{ money(item.row.credit, 2) }}
                                </td>
                            </tr>
                            <tr v-else>
                                <td colspan="7">&nbsp;</td>
                            </tr>
                        </template>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td class="font-bold">Total</td>
                            <td colspan="2">&nbsp;</td>
                            <td class="text-end font-bold">
                                {{ money(total?.debits ?? 0, 2) }}
                            </td>
                            <td class="text-end font-bold">
                                {{ money(total?.credits ?? 0, 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import moment from "moment";
import { money } from "~/utils/helper";
import type { GeneralJournal } from "~/types/journals";

const dates = defineModel<[Date, Date] | null>();

const key = `${id(useRoute().fullPath)}.general-journal`;
const client = useSanctumClient();
const { receive, send, dismiss } = usePageEvent();

const date1 = computed(() => {
    const start = new Date();
    start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);

    let date = moment(start);
    if (dates.value != null) {
        date = moment(dates.value[0]);
    }

    return date.format("MMMM DD, YYYY");
});

const date2 = computed(() => {
    const end = new Date();

    let date = moment(end);
    if (dates.value != null) {
        date = moment(dates.value[1]);
    }

    return date.format("MMMM DD, YYYY");
});

const openEditLink = (row: any) => {
    window.open(`${row.url}`, "_blank");
};

const { data, refresh, status } = useAsyncData<GeneralJournal>(
    key,
    () =>
        client("/api/reports/general-journal", {
            method: "GET",
            params: {
                date_from: moment(dates.value![0]).format("YYYY-MM-DD"),
                date_to: moment(dates.value![1]).format("YYYY-MM-DD"),
            },
        }),
    {
        server: false,
        lazy: true,
        immediate: false,
    },
);

const journals = computed(() => data.value?.journals ?? []);
const total = computed(() => data.value?.total);

/** Rows with dateRefRowSpan: render date/ref cell only on first row of each entry (rowspan spans the rest). */
const rowsWithEntrySpan = computed(() => {
    const list = journals.value;
    const result: { row: (typeof list)[number]; dateRefRowSpan: number }[] = [];
    for (let i = 0; i < list.length; i++) {
        const row = list[i];
        const prev = i > 0 ? list[i - 1] : null;
        const isNewEntry =
            !prev ||
            prev.ref_no !== row.ref_no ||
            moment(prev.posted_at).format("YYYY-MM-DD") !==
                moment(row.posted_at).format("YYYY-MM-DD");
        if (isNewEntry) {
            let span = 1;
            while (
                i + span < list.length &&
                list[i + span].ref_no === row.ref_no &&
                moment(list[i + span].posted_at).format("YYYY-MM-DD") ===
                    moment(row.posted_at).format("YYYY-MM-DD")
            ) {
                span++;
            }
            result.push({ row, dateRefRowSpan: span });
        } else {
            result.push({ row, dateRefRowSpan: 0 });
        }
    }
    return result;
});

type TableRowItem =
    | { type: "data"; row: (typeof journals.value)[number]; dateRefRowSpan: number; isLastDataRow: boolean }
    | { type: "empty" };

/** Data rows plus one empty row after each transaction, for spacing. */
const tableRows = computed(() => {
    const list = rowsWithEntrySpan.value;
    const result: TableRowItem[] = [];
    for (let i = 0; i < list.length; i++) {
        const isLastDataRow = i === list.length - 1;
        result.push({
            type: "data",
            ...list[i],
            isLastDataRow,
        });
        const isLastRowOfTransaction =
            i === list.length - 1 || list[i + 1].dateRefRowSpan > 0;
        if (isLastRowOfTransaction) {
            result.push({ type: "empty" });
        }
    }
    return result;
});

watch(status, (value) => {
    if (value == "success") {
        send("report:done");
    }
});

onBeforeUnmount(() => {
    dismiss("report:show");
});

onMounted(() => {
    receive("report:show", () => {
        refresh();
    });
});
</script>

<style scoped>
@media print {
    .preview-report {
        width: 100%;
    }

    .report-container,
    .preview-report {
        padding: 0 !important;
    }

    @page {
        size: landscape;
        margin: 20mm 15mm;
    }

    @page :first {
        margin: 2mm 15mm 20mm 15mm;
    }
}
</style>
