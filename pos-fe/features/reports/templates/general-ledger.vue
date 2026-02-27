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
                                <h4 class="m-0">General Ledger</h4>
                                <h4 class="m-0">
                                    For the Period From {{ date1 }} to
                                    {{ date2 }}
                                </h4>
                            </div>
                        </td>
                    </tr>
                    <tr class="!border-t-[2px] !border-b-[2px] align-top">
                        <th class="w-[22%] text-start">
                            Account ID<br />
                            <span class="ml-4 block">Account Description</span>
                        </th>
                        <th class="w-[8%] text-start">Date</th>
                        <th class="w-[10%] text-start">Reference</th>
                        <th class="w-[8%] text-start">Jrnl</th>
                        <th class="w-[20%] text-start">Trans Description</th>
                        <th class="w-[10%] text-end">Debit Amt</th>
                        <th class="w-[10%] text-end">Credit Amt</th>
                        <th class="w-[10%] text-end">Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <template
                        v-for="(row, index) in data ?? []"
                        :key="row.account.code"
                    >
                        <tr>
                            <td>
                                {{ row.account.code }}
                            </td>
                            <td>{{ row.beginning?.start_at }}</td>
                            <td></td>
                            <td></td>
                            <td>Beginning Balance</td>
                            <td class="text-end"></td>
                            <td class="text-end"></td>
                            <td class="text-end">
                                {{
                                    reportsMoney(
                                        row.beginning?.beginning,
                                        2,
                                        row.config.showZero,
                                    )
                                }}
                            </td>
                        </tr>
                        <tr v-for="(journal, index) in row?.journals ?? []">
                            <td class="align-top">
                                {{ index <= 0 ? row.account.name : "" }}
                            </td>
                            <td class="align-top">{{ journal.posted_at }}</td>
                            <td
                                class="align-top text-blue-500 hover:underline cursor-pointer print:text-black print:no-underline"
                                @click="openEditLink(journal.transactable)"
                            >
                                {{ journal.ref_no }}
                            </td>
                            <td class="align-top">
                                {{ journal.financial_code?.code }}
                            </td>
                            <td class="align-top">
                                {{ journal.trans_description }}
                            </td>
                            <td class="text-end align-top">
                                {{
                                    reportsMoney(
                                        journal.debit,
                                        2,
                                        row.config.showZero,
                                    )
                                }}
                            </td>
                            <td class="text-end align-top">
                                {{
                                    reportsMoney(
                                        journal.credit,
                                        2,
                                        row.config.showZero,
                                    )
                                }}
                            </td>
                            <td class="text-end"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>{{ row.ending?.start_at }}</td>
                            <td></td>
                            <td></td>
                            <td class="font-bold">Ending Balance</td>
                            <td class="text-end"></td>
                            <td class="text-end"></td>
                            <td class="text-end font-bold">
                                {{
                                    reportsMoney(
                                        row.ending?.ending,
                                        2,
                                        row.config.showZero,
                                    )
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="8">&nbsp;</td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup lang="ts">
import moment from "moment";

const company = ref(import.meta.env.VITE_APP_COMPANY_NAME);

const dates = defineModel();

const key = `${id(useRoute().fullPath)}.general-ledger`;
const client = useSanctumClient();
const { receive, send, dismiss } = usePageEvent();
const user = useSanctumUser();

const openEditLink = (row: any) => {
    window.open(`${row.url}`, "_blank");
};

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

const { data, refresh, status } = useAsyncData(
    key,
    () =>
        client("/api/reports/general-ledger", {
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

const moneyDisplay = (value: number) => {
    return value < 0
        ? `(${money(value.toString().replace("-", ""), 2)})`
        : money(value, 2);
};
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
