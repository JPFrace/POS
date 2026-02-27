<template>
    <div class="flex justify-center items-center p-16 w-full report-container">
        <div
            class="flex flex-col justify-center items-center gap-y-8 bg-white p-4 w-[80%] preview-report"
        >
            <div class="flex flex-col justify-center items-center w-full">
                <h4>ST. SCHOLASTICA'S COLLEGE-WESTGROVE, INC.</h4>
                <p class="m-0 p-0">Ayala Westgrove Heights, Silang, Cavite</p>
                <p class="m-0 p-0">ALL DEPARTMENTS</p>
                <p class="m-0 p-0">Statement of Income and Expenses</p>
                <p class="m-0 p-0 font-bold">
                    For the Period From {{ date1 }} to {{ date2 }}
                </p>
            </div>
            <div class="w-full">
                <table class="w-full">
                    <thead>
                        <tr class="uppercase">
                            <th class="w-[15%] text-center">Annual Budget</th>
                            <th class="w-[30%]" />
                            <th class="w-[18.33%] text-end">Current Month</th>
                            <th class="w-[18.33%] text-end">Year to Date</th>
                            <th class="w-[18.33%] text-end">Budget to Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="row in data?.educational_income ?? []">
                            <td colspan="5">
                                <table class="w-full">
                                    <tr>
                                        <td
                                            class="!border-b border-dotted w-[15%]"
                                        >
                                            &nbsp;
                                        </td>
                                        <td
                                            class="w-[30%] font-bold uppercase align-middle"
                                        >
                                            {{ row.name }}
                                        </td>
                                        <td class="w-[18.33%]">&nbsp;</td>
                                        <td class="w-[18.33%]">&nbsp;</td>
                                        <td class="w-[18.33%]">&nbsp;</td>
                                    </tr>
                                </table>
                                <table class="w-full">
                                    <tbody>
                                        <tr
                                            v-for="(
                                                children, index
                                            ) in row.children"
                                        >
                                            <StateIncomeExpense
                                                :data="children"
                                                :last="
                                                    row.children.length - 1 ==
                                                    index
                                                "
                                            />
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5">&nbsp;</td>
                        </tr>
                        <tr>
                            <StateIncomeExpense
                                :data="data?.total_income ?? []"
                                :last="true"
                                :highlight-name="true"
                            />
                        </tr>
                        <tr>
                            <td colspan="5">&nbsp;</td>
                        </tr>
                        <tr>
                            <StateIncomeExpense
                                :data="data?.total_income_discounts ?? []"
                                :last="true"
                            />
                        </tr>
                        <tr>
                            <td colspan="5">&nbsp;</td>
                        </tr>
                        <tr>
                            <StateIncomeExpense
                                :data="data?.net_income ?? []"
                                :last="true"
                                :highlight-name="true"
                            />
                        </tr>
                        <tr>
                            <td colspan="5">&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="!border-b border-dotted w-[15%]">
                                &nbsp;
                            </td>
                            <td class="w-[30%] font-bold align-middle">
                                Less: Expenses
                            </td>
                            <td class="w-[18.33%]">&nbsp;</td>
                            <td class="w-[18.33%]">&nbsp;</td>
                            <td class="w-[18.33%]">&nbsp;</td>
                        </tr>
                        <tr v-for="row in data?.educational_expenses ?? []">
                            <td colspan="5">
                                <table class="w-full">
                                    <tr>
                                        <td
                                            class="!border-b border-dotted w-[15%]"
                                        >
                                            &nbsp;
                                        </td>
                                        <td
                                            class="w-[30%] font-bold uppercase align-middle"
                                        >
                                            {{ row.name }}
                                        </td>
                                        <td class="w-[18.33%]">&nbsp;</td>
                                        <td class="w-[18.33%]">&nbsp;</td>
                                        <td class="w-[18.33%]">&nbsp;</td>
                                    </tr>
                                </table>
                                <table class="w-full">
                                    <tbody>
                                        <tr
                                            v-for="(
                                                children, index
                                            ) in row.children"
                                        >
                                            <StateIncomeExpense
                                                :data="children"
                                                :last="
                                                    row.children.length - 1 ==
                                                    index
                                                "
                                            />
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5">&nbsp;</td>
                        </tr>
                        <tr>
                            <StateIncomeExpense
                                :data="data?.total_educational_expenses ?? []"
                                :last="true"
                                :highlight-name="true"
                            />
                        </tr>
                        <tr>
                            <td colspan="5">&nbsp;</td>
                        </tr>
                        <tr v-for="row in data?.administrative_expenses ?? []">
                            <td colspan="5">
                                <table class="w-full">
                                    <tr>
                                        <td
                                            class="!border-b border-dotted w-[15%]"
                                        >
                                            &nbsp;
                                        </td>
                                        <td
                                            class="w-[30%] font-bold uppercase align-middle"
                                        >
                                            {{ row.name }}
                                        </td>
                                        <td class="w-[18.33%]">&nbsp;</td>
                                        <td class="w-[18.33%]">&nbsp;</td>
                                        <td class="w-[18.33%]">&nbsp;</td>
                                    </tr>
                                </table>
                                <table class="w-full">
                                    <tbody>
                                        <tr
                                            v-for="(
                                                children, index
                                            ) in row.children"
                                        >
                                            <StateIncomeExpense
                                                :data="children"
                                                :last="
                                                    row.children.length - 1 ==
                                                    index
                                                "
                                            />
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5">&nbsp;</td>
                        </tr>
                        <tr>
                            <StateIncomeExpense
                                :data="
                                    data?.total_administrative_expenses ?? []
                                "
                                :last="true"
                                :highlight-name="true"
                            />
                        </tr>
                        <tr>
                            <td colspan="5">&nbsp;</td>
                        </tr>
                        <tr>
                            <StateIncomeExpense
                                :data="
                                    data?.total_educational_administrative_expenses ??
                                    []
                                "
                                :last="true"
                                :highlight-name="true"
                            />
                        </tr>
                        <tr>
                            <td colspan="5">&nbsp;</td>
                        </tr>
                        <tr>
                            <StateIncomeExpense
                                :data="data?.excess_income_over_expenses ?? []"
                                :last="true"
                                :highlight-name="true"
                            />
                        </tr>
                        <tr>
                            <td colspan="5">&nbsp;</td>
                        </tr>
                        <tr
                            v-for="row in data?.educational_related_income ??
                            []"
                        >
                            <td colspan="5">
                                <table class="w-full">
                                    <tr>
                                        <td
                                            class="!border-b border-dotted w-[15%]"
                                        >
                                            &nbsp;
                                        </td>
                                        <td
                                            class="w-[30%] font-bold uppercase align-middle"
                                        >
                                            {{ row.name }}
                                        </td>
                                        <td class="w-[18.33%]">&nbsp;</td>
                                        <td class="w-[18.33%]">&nbsp;</td>
                                        <td class="w-[18.33%]">&nbsp;</td>
                                    </tr>
                                </table>
                                <table class="w-full">
                                    <tbody>
                                        <tr
                                            v-for="(
                                                children, index
                                            ) in row.children"
                                        >
                                            <StateIncomeExpense
                                                :data="children"
                                                :last="
                                                    row.children.length - 1 ==
                                                    index
                                                "
                                            />
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5">&nbsp;</td>
                        </tr>
                        <tr>
                            <StateIncomeExpense
                                :data="
                                    data?.total_educational_related_income ?? []
                                "
                                :last="true"
                                :highlight-name="true"
                            />
                        </tr>
                        <tr>
                            <td colspan="5">&nbsp;</td>
                        </tr>
                        <tr>
                            <StateIncomeExpense
                                :data="data?.net_income_loss ?? []"
                                :last="true"
                                :final="true"
                                :highlight-name="true"
                            />
                        </tr>
                    </tbody>
                </table>
                <div class="flex justify-start items-center gap-x-24 mt-16">
                    <div
                        v-for="s in sortedSignatories"
                        :key="s.uuid"
                        class="flex flex-col gap-y-8"
                    >
                        <span class="italic">{{ s.label }}</span>
                        <div class="flex flex-col gap-y-2">
                            <span class="font-bold uppercase">
                                {{ s.signatory?.name }}
                            </span>
                            <span>
                                {{
                                    s.signatory?.position?.title ??
                                    "Business Office Staff"
                                }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import moment from "moment";
import StateIncomeExpense from "./state-income-expenses/row.vue";

const company = ref(import.meta.env.VITE_APP_COMPANY_NAME);

const dates = defineModel();

const key = `${id(useRoute().fullPath)}.profit-loss`;
const client = useSanctumClient();
const { receive, send, dismiss } = usePageEvent();
const user = useSanctumUser();
const reportId = ref();

const sortedSignatories = computed(() => {
    const year =
        dates.value && (dates.value as any)[0]
            ? moment((dates.value as any)[0]).year()
            : moment().year();

    return (report.value?.report_signatories ?? [])
        .filter((s) => {
            return s.year_signatory == year;
        })
        .slice()
        .sort((a, b) => (a.sort ?? 0) - (b.sort ?? 0));
});

const report = computed(() => data.value?.report ?? {});

const getSignatory = (label: string) => {
    return (
        report.value?.report_signatories?.find((r) => r.label === label)
            ?.signatory ?? {}
    );
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
        client("/api/reports/statement-income-expenses", {
            method: "GET",
            params: {
                date_from: moment((dates.value as any)[0]).format("YYYY-MM-DD"),
                date_to: moment((dates.value as any)[1]).format("YYYY-MM-DD"),
                report_id: reportId.value,
            },
        }),
    {
        server: false,
        lazy: true,
        immediate: false,
    }
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
    receive("report:show", (id: string) => {
        reportId.value = id;
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
        size: A4;
        margin: 20mm 15mm;
    }

    @page :first {
        margin: 2mm 15mm 20mm 15mm;
    }
}
</style>
