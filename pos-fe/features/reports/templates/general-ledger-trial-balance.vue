<template>
    <div class="p-16 w-full flex items-center justify-center report-container">
        <div
            class="w-[80%] bg-white p-4 flex flex-col gap-y-8 items-center justify-center preview-report"
        >
            <table class="w-full">
                <thead>
                    <tr>
                        <td colspan="4">
                            <div
                                class="w-full flex flex-col items-center justify-center mb-4"
                            >
                                <h4>
                                    ST. SCHOLASTICA'S COLLEGE-WESTGROVE, INC.
                                </h4>
                                <p class="p-0 m-0 font-bold">
                                    General Ledger Trial Balance
                                </p>
                                <p class="p-0 m-0 font-bold">
                                    As of {{ date2 }}
                                </p>
                            </div>
                        </td>
                    </tr>
                    <tr class="!border-t-[2px] !border-b-[2px]">
                        <th class="w-[20%] text-start">Account ID</th>
                        <th class="w-[40%] text-start">Account Description</th>
                        <th class="w-[20%] text-end">Debit Amt</th>
                        <th class="w-[20%] text-end">Credit Amt</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(row, index) in data?.reports ?? []"
                        :key="row.code"
                    >
                        <td
                            class="align-top text-blue-500 hover:underline cursor-pointer print:text-black print:no-underline"
                            @click="openEditLink(row)"
                        >
                            {{ row.code }}
                        </td>
                        <td>{{ row.name }}</td>
                        <td
                            class="text-end"
                            :class="{
                                '!border-b-2':
                                    (data?.reports ?? []).length - 1 == index,
                            }"
                        >
                            {{ moneyDisplay(row.debit, data?.config.showZero) }}
                        </td>
                        <td
                            class="text-end"
                            :class="{
                                '!border-b-2':
                                    (data?.reports ?? []).length - 1 == index,
                            }"
                        >
                            {{
                                moneyDisplay(row.credit, data?.config.showZero)
                            }}
                        </td>
                    </tr>
                    <tr class="font-bold">
                        <td class="pt-6"></td>
                        <td class="text-start pt-6">Total</td>
                        <td class="text-end pt-6">
                            {{ moneyDisplay(data?.total.debit ?? 0) }}
                        </td>
                        <td class="text-end pt-6">
                            {{ moneyDisplay(data?.total.credit ?? 0) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup lang="ts">
import moment from "moment";

const company = ref(import.meta.env.VITE_APP_COMPANY_NAME);

const dates = defineModel();

const key = `${id(useRoute().fullPath)}.general-ledger-trial-balance`;
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

const { data, refresh, status } = useAsyncData(
    key,
    () =>
        client("/api/reports/trial-balance", {
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

const openEditLink = (row: any) => {
    window.open(`${row.url}`, "_blank");
};

const moneyDisplay = (value: number, showZero = true) => {
    if (!showZero && value === 0) {
        return "";
    }

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
        size: A4;
        margin: 20mm 15mm;
    }

    @page :first {
        margin: 2mm 15mm 20mm 15mm;
    }
}
</style>
