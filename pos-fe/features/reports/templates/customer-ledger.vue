<template>
    <div class="p-16 w-full flex items-center justify-center report-container">
        <div
            class="w-[100%] bg-white p-4 flex flex-col gap-y-8 items-center justify-center preview-report"
        >
            <table class="w-full">
                <thead>
                    <tr>
                        <td colspan="8">
                            <div
                                class="w-full flex flex-col items-center justify-center mb-4"
                            >
                                <h4 class="m-0">
                                    ST. SCHOLASTICA'S COLLEGE-WESTGROVE, INC.
                                </h4>
                                <h4 class="m-0">Customer Ledger</h4>
                                <h4 class="m-0">
                                    For the Period From {{ date1 }} to
                                    {{ date2 }}
                                </h4>
                            </div>
                        </td>
                    </tr>
                    <tr class="!border-t-[2px] !border-b-[2px] align-top">
                        <th class="w-[25%] text-start">
                            Customer ID<br />
                            <span class="ml-4 block">Customer Name</span>
                        </th>
                        <th class="w-[8%] text-start">Date</th>
                        <th class="w-[10%] text-start">Trans No</th>
                        <th class="w-[2%] text-start">Type</th>
                        <th class="w-[13%] text-end">Debit Amt</th>
                        <th class="w-[13%] text-end">Credit Amt</th>
                        <th class="w-[13%] text-end">Balance</th>
                        <th class="w-[10%] text-end">Bill To Contact</th>
                    </tr>
                </thead>
                <tbody>
                    <template
                        v-for="(row, index) in data?.customer_ledger"
                        :key="index"
                    >
                        <tr
                            v-if="
                                index !== 0 &&
                                row.customer_id !==
                                    data?.customer_ledger[index - 1].customer_id
                            "
                        >
                            <td colspan="8" class="h-8" />
                        </tr>
                        <tr>
                            <td class="text-start font-semibold align-top">
                                <div class="min-h-[3em]">
                                    <template
                                        v-if="
                                            index === 0 ||
                                            row.customer_id !=
                                                data?.customer_ledger[index - 1]
                                                    .customer_id
                                        "
                                    >
                                        {{ row.customer_id }}<br />
                                        <span class="ml-4 block">{{
                                            row.customer_name
                                        }}</span>
                                    </template>
                                </div>
                            </td>
                            <td class="text-start">
                                {{ formatDateShort(row.date) }}
                            </td>
                            <td class="text-start">
                                {{ row.trans_no }}
                            </td>
                            <td class="text-start">{{ row.code }}</td>
                            <td class="text-end">{{ money(row.debit, 2) }}</td>
                            <td class="text-end">{{ money(row.credit, 2) }}</td>
                            <td class="text-end">
                                {{ money(row.balance, 2) }}
                            </td>
                            <td class="text-end">-</td>
                        </tr>
                    </template>
                </tbody>
                <tr>
                    <td colspan="8" class="h-6" />
                </tr>
                <tr class="!border-t-[2px] !border-b-[4px] font-bold">
                    <td colspan="4" class="text-start py-1 pt-4 pb-4">
                        Report Total
                    </td>
                    <td class="text-end py-1">
                        {{ money(total?.debits ?? 0, 2) }}
                    </td>
                    <td class="text-end py-1">
                        {{ money(total?.credits ?? 0, 2) }}
                    </td>
                    <td class="text-end py-1">
                        {{ money(total?.balance ?? 0, 2) }}
                    </td>
                    <td />
                </tr>
            </table>
        </div>
    </div>
</template>

<script setup lang="ts">
import moment from "moment";
import { formatDateShort, money } from "~/utils/helper";
import type { CustomerLedger } from "~/types/journals";

const dates = defineModel<[Date, Date] | null>();

const key = `${id(useRoute().fullPath)}.customer-ledger`;
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

const total = computed(() => data.value?.total);

const { data, refresh, status } = useAsyncData<CustomerLedger>(
    key,
    () =>
        client("/api/reports/customer-ledger", {
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
