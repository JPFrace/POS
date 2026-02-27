<template>
    <div class="p-16 w-full flex items-center justify-center report-container print:p-0">
        <div
            class="w-full bg-white p-4 flex flex-col gap-y-8 items-center justify-center preview-report print:w-full print:p-0"
        >
            <div class="w-full flex flex-col items-center justify-center">
                <h4>ST. SCHOLASTICA'S COLLEGE-WESTGROVE, INC.</h4>
                <p class="p-0 m-0 font-bold">Cash Receipts Journal</p>
                <p class="p-0 m-0 font-bold">
                    For the Period From {{ date1 }} to {{ date2 }}
                </p>
            </div>
            <div class="w-full">
                <table class="w-full table-fixed [&_td]:align-top [&_th]:align-top">
                    <thead>
                        <tr
                            class="uppercase !border-t-[2px] !border-b-[2px] text-xs align-top"
                        >
                            <th class="w-[6%] text-start text-xs align-top">Date</th>
                            <th class="w-[14%] text-start text-xs align-top">Name</th>
                            <th class="w-[5%] text-start text-xs align-top">
                                Grade
                            </th>
                            <th class="w-[7%] text-start text-xs align-top">Receipt NO.</th>
                            <th class="w-[7%] text-start text-xs align-top">
                                Trans. Ref
                            </th>
                            <th class="w-[23%] text-start text-xs align-top">
                                Line Description
                            </th>
                            <th class="w-[8%] text-start text-xs align-top">
                                Account ID
                            </th>
                            <th class="w-[16%] text-start text-xs align-top">
                                Account Description
                            </th>
                            <th class="w-[9%] text-end align-top">Debit Amount</th>
                            <th class="w-[9%] text-end align-top">Credit Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(row, index) in data?.data ?? []" :key="index">
                            <tr class="text-xs align-top">
                                <td class="text-start align-top w-[6%]">{{ row.date }}</td>
                                <td class="text-start align-top w-[14%] uppercase">{{ row.customer_name }}</td>
                                <td class="text-start align-top w-[5%]">-</td>
                                <td
                                    class="text-start align-top w-[7%] text-blue-500 hover:underline cursor-pointer print:text-black print:no-underline"
                                    @click="openEditLink(row)"
                                >
                                    {{ row.or_no }}
                                </td>
                                <td class="text-start align-top w-[7%]">{{ row.references }}</td>
                                <td colspan="5" class="align-top p-0">
                                    <table class="w-full table-fixed border-collapse [&_td]:align-top [&_td]:overflow-hidden [&_td]:py-0.5 [&_td]:px-1">
                                        <colgroup>
                                            <col style="width: 35.38%" />
                                            <col style="width: 12.31%" />
                                            <col style="width: 24.62%" />
                                            <col style="width: 13.85%" />
                                            <col style="width: 13.85%" />
                                        </colgroup>
                                        <tbody>
                                            <tr v-for="(line, lineIdx) in receiptLines(row)" :key="lineIdx" class="align-top">
                                                <td class="text-start align-top">{{ line.description }}</td>
                                                <td class="text-start align-top">{{ line.journal?.chart_account?.code ?? "" }}</td>
                                                <td class="text-start align-top">{{ line.journal?.chart_account?.name ?? "" }}</td>
                                                <td class="text-end align-top tabular-nums">{{ line.journal?.debit !== undefined && line.journal?.debit !== "" ? money(line.journal.debit, 2) : "" }}</td>
                                                <td class="text-end align-top tabular-nums">{{ line.journal?.credit !== undefined && line.journal?.credit !== "" ? money(line.journal.credit, 2) : "" }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr class="empty-row">
                                <td colspan="10" class="align-top">&nbsp;</td>
                            </tr>
                        </template>
                        <tr>
                            <td colspan="5" class="align-top">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2" class="align-top">&nbsp;</td>
                            <td class="font-bold align-top"></td>
                            <td colspan="5" class="align-top">&nbsp;</td>
                            <td class="text-end font-bold !border-b-4 align-top tabular-nums">
                                {{ money(data?.total?.debits ?? 0, 2) }}
                            </td>
                            <td class="text-end font-bold !border-b-4 align-top tabular-nums">
                                {{ money(data?.total?.credits ?? 0, 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<style scoped>
@media print {
    .no-print {
        display: none !important;
    }
}
@page {
    size: landscape;
}
</style>
<script setup lang="ts">
import moment from "moment";

const company = ref(import.meta.env.VITE_APP_COMPANY_NAME);

const dates = defineModel();

const key = `${id(useRoute().fullPath)}.cash-receipts-journal`;
const client = useSanctumClient();
const { receive, send, dismiss } = usePageEvent();
const user = useSanctumUser();

const date1 = computed(() => {
    const start = new Date();
    start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);

    let date = moment(start);
    if (dates.value != null) {
        date = moment((dates.value as any)[0]);
    }

    return date.format("MMMM DD, YYYY");
});

const date2 = computed(() => {
    const end = new Date();

    let date = moment(end);
    if (dates.value != null) {
        date = moment((dates.value as any)[1]);
    }

    return date.format("MMMM DD, YYYY");
});

const openEditLink = (row: any) => {
    window.open(`${row.url}`, "_blank");
};

function isZeroAmount(val: any): boolean {
    if (val === "" || val == null) return true;
    return Number(val) === 0;
}

/** One row per line: description (product names + customer name) paired with journal entry for alignment. Hides accounts with 0 debit and 0 credit. */
function receiptLines(row: any) {
    const details = row?.details ?? [];
    const allJournals = row?.journals ?? [];
    const journals = allJournals.filter(
        (j: any) => !(isZeroAmount(j?.debit) && isZeroAmount(j?.credit))
    );
    const descriptionLines = [
        ...details.map((d: any) => d.product_name ?? ""),
        String(row?.customer_name ?? "").toUpperCase(),
    ];
    const rowCount = Math.max(descriptionLines.length, journals.length);
    return Array.from({ length: rowCount }, (_, i) => ({
        description: descriptionLines[i] ?? "",
        journal: journals[i] ?? null,
    }));
}

const { data, refresh, status } = useAsyncData(
    key,
    () =>
        client("/api/reports/cash-receipts-journal", {
            method: "GET",
            params: {
                date_from: moment((dates.value as any)[0]).format("YYYY-MM-DD"),
                date_to: moment((dates.value as any)[1]).format("YYYY-MM-DD"),
            },
        }),
    {
        server: false,
        lazy: true,
        immediate: false,
    },
);

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
