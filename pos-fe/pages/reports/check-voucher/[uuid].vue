<template>
    <!-- Loading State -->
    <div
        v-if="isLoading"
        class="h-screen flex justify-center mt-4 ml-4"
        role="status"
        aria-live="polite"
    >
        <div class="flex items-center space-y-5 gap-5">
            <div class="spinner-border text-blue-500" aria-hidden="true" />
            <p class="text-gray-500 font-medium text-[14px]">Loading...</p>
        </div>
    </div>

    <div
        v-else
        class="p-16 w-full flex items-center justify-center report-container"
    >
        <div
            class="w-[100%] bg-white p-4 flex flex-col gap-y-8 justify-center text-[14px] leading-none preview-report"
        >
            <div class="flex justify-end no-print">
                <Button
                    light
                    variant="primary"
                    class="flex items-center gap-2"
                    @click="printChequeLayout"
                >
                    <KTIcon
                        icon-name="cheque"
                        icon-type="solid"
                        icon-class="fs-3"
                    />
                    Print Check
                </Button>
            </div>
            <div class="flex justify-between items-start">
                <!-- Header Left -->
                <div class="text-left">
                    <h1 class="text-xl font-bold">
                        ST. SCHOLASTICA'S COLLEGE-WESTGROVE
                    </h1>
                    <p class="font-medium">Ayala Westgrove Heights, Silang</p>
                </div>
                <!-- Header Right-->
                <div class="text-right pt-1 font-medium">
                    <p class="flex leading-[0.5]">
                        <span class="flex-1">Voucher No.:</span
                        ><span class="min-w-60"
                            ><strong class="text-[28px]">
                                {{ payment?.ref_no }}</strong
                            ></span
                        >
                    </p>
                    <p class="flex leading-[0.5]">
                        <span class="flex-1">Check Number:</span
                        ><span class="min-w-60"
                            ><strong class="text-[16px]">
                                {{ payment?.check_no }}</strong
                            ></span
                        >
                    </p>
                    <p class="flex">
                        <span class="flex-1">Date:</span
                        ><span class="min-w-60">
                            {{ formatDate2(payment?.date ?? "") }}</span
                        >
                    </p>
                </div>
            </div>

            <!-- Title -->
            <div class="text-center">
                <h2 class="text-xl font-bold">CHECK VOUCHER</h2>
            </div>

            <!-- Voucher Info -->
            <div class="flex gap-4 pl-8 font-medium">
                <!-- Payee Label-->
                <div class="whitespace-nowrap">
                    <strong>PAYEE</strong>
                </div>
                <!-- Payee Name -->
                <div class="flex-1 relative pl-20">
                    <strong class="inline-block">
                        {{ payment?.payee_name }}</strong
                    >
                    <!-- First Underline -->
                    <div
                        class="absolute left-20 right-0 bottom-[-2px] border-b border-black"
                    />
                    <!-- Second Underline -->
                    <div
                        class="absolute left-20 right-0 bottom-[-10px] border-b border-black"
                    />
                </div>
            </div>
            <div class="flex gap-4 pl-8 font-medium">
                <!-- Amount in Words -->
                <div class="whitespace-nowrap">
                    <p>Amount in<br />words</p>
                </div>
                <!-- Amount in Words value -->
                <div class="flex-1 relative pl-13 pt-2">
                    <p class="inline-block uppercase">
                        {{ payment?.net_in_words }}
                    </p>
                    <!-- First Underline -->
                    <div
                        class="absolute left-13 right-0 bottom-0 border-b border-black"
                    />
                    <!-- Second Underline -->
                    <div
                        class="absolute left-13 right-0 bottom-[8px] border-b border-black"
                    />
                </div>
            </div>
            <div class="flex gap-4 pl-8 font-medium">
                <!-- Particulars Label -->
                <div class="whitespace-nowrap">
                    <p>Particulars</p>
                </div>
                <!-- Particulars value -->
                <div class="flex-1 relative pl-13">
                    <p class="inline-block uppercase">
                        {{ payment?.remarks }}
                    </p>
                </div>
            </div>

            <!-- Journal Entry -->
            <table class="w-full mb-24">
                <thead>
                    <tr class="!border-t-[1px] !border-b-[1px]">
                        <th class="text-left px-2 py-1 w-1/4">Account Code</th>
                        <th class="text-left px-2 py-1 w-2/4">Account Title</th>
                        <th class="text-end px-2 py-1 w-1/4">Debit (Credit)</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Debit (Credit) -->
                    <tr v-for="row in payment?.journals ?? []" :key="row.id">
                        <td class="px-2 py-1">
                            {{ row.chart_account?.code
                            }}{{ row?.chart_account?.usage_type?.code }}
                        </td>
                        <td class="px-2 py-1">{{ row.chart_account?.name }}</td>
                        <td class="px-2 py-1 text-end">
                            <span
                                v-if="
                                    row?.chart_account?.usage_type?.code == ''
                                "
                                >(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </span>
                            {{
                                row.debit != 0
                                    ? money(row.debit, 2)
                                    : `(${money(row.credit, 2)})`
                            }}
                            <span
                                v-if="
                                    row?.chart_account?.usage_type?.code == ''
                                "
                                >)</span
                            >
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Credit Summary -->
            <div
                class="relative flex items-center justify-between font-bold text-[16px] pt-2 pb-2"
            >
                <!-- Top Border -->
                <div
                    class="absolute left-0 right-0 top-0 border-t border-black"
                />

                <!-- Label -->
                <span>Credit Cash in Bank: {{ payment?.cash_bank?.name }}</span>

                <!-- Amount -->
                <span class="text-right"
                    >Php<span class="pl-14">{{
                        money(cashInBank?.credit, 2)
                    }}</span></span
                >

                <!-- Bottom Borders -->
                <div
                    class="absolute left-0 right-0 bottom-0 border-b border-black"
                />
                <div
                    class="absolute left-0 right-0 -bottom-2 border-b border-black"
                />
            </div>

            <!-- Signature Fields Section -->
            <div class="grid grid-cols-2 gap-4 ml-1">
                <!-- Left Side -->
                <div class="space-y-4 leading-relaxed tracking-tighter">
                    <div class="flex items-end">
                        <span class="w-30">Prepared by:</span>
                        <div class="flex-1">
                            <span class="block text-center">{{}}</span>
                            <div class="border-b border-black mt-[-2px]" />
                        </div>
                    </div>
                    <div class="flex items-end">
                        <span class="w-30">Checked by:</span>
                        <div class="flex-1">
                            <span class="block text-center">{{}}</span>
                            <div class="border-b border-black mt-[-2px]" />
                        </div>
                    </div>
                    <div class="flex items-end">
                        <span class="w-30">Approved by:</span>
                        <div class="flex-1">
                            <span class="block text-center">{{}}</span>
                            <div class="border-b border-black mt-[-2px]" />
                        </div>
                    </div>
                </div>

                <!-- Right side -->
                <div class="space-y-2">
                    <div class="mb-6 leading-snug tracking-tight">
                        <p class="flex items-center text-[10px]">
                            Received from SSC-W the amount of in payment of the
                            above described.
                            <span class="flex w-full border-b border-black">
                                <span class="ml-6 text-[14px] font-bold"
                                    >Php</span
                                >
                                <strong
                                    class="flex-grow text-right text-[14px]"
                                    >{{ money(cashInBank?.credit, 2) }}</strong
                                >
                            </span>
                        </p>
                        <div class="flex flex-col items-start">
                            <div class="flex items-end w-full">
                                <span class="w-30">Received by:</span>
                                <div class="flex-1">
                                    <span class="block text-center">{{}}</span>
                                    <div class="border-b border-black" />
                                </div>
                            </div>
                            <div class="flex w-full">
                                <span class="w-30" />
                                <span class="flex-1 text-center text-[10px]"
                                    >Print name &amp; signature</span
                                >
                            </div>
                            <div class="flex items-end w-full pt-2">
                                <span class="w-30">Date:</span>
                                <div class="flex-1">
                                    <span
                                        class="block text-center text-[12px]"
                                        >{{
                                    }}</span>
                                    <div class="border-b border-black" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { useRoute } from "vue-router";
import type { Payment, PaymentJournalRow } from "~/types/payment";
import { money, formatDate2 } from "~/utils/helper";

const route = useRoute();
const uuid = route.params.uuid;

const payment = ref<Payment | null>(null);
const journals = ref<PaymentJournalRow[]>([]);
const isLoading = ref(true);

const { t } = useI18n();
const { $message } = useNuxtApp();

const printChequeLayout = (event: MouseEvent) => {
    const url = `/reports/cheque-layout/${route.params.uuid}`;
    window.open(url, "_blank");

    (event.currentTarget as HTMLElement)?.blur();
};

async function fetchVoucherData() {
    try {
        if (typeof uuid !== "string" || !uuid.trim()) {
            $message("info", t("make_payment.info.no_payment_data"));
            return;
        }
        const result = await useClient(`/api/reports/check-voucher`, {
            method: "GET",
            params: {
                uuid: route.params.uuid,
            },
        });

        journals.value = result?.payment?.journals ?? [];

        result.payment.journals = (result?.payment?.journals ?? []).filter(
            (row: PaymentJournalRow) =>
                row?.chart_account?.usage_type?.code != "CASH_IN_BANK"
        );

        payment.value = result.payment;
    } catch {
        $message("error", t("error.failed_to_load"));
    } finally {
        isLoading.value = false;
    }
}

const cashInBank = computed(
    () =>
        journals.value.filter(
            (row: PaymentJournalRow) =>
                row?.chart_account?.usage_type?.code == "CASH_IN_BANK"
        )[0] ?? null
);

onMounted(() => {
    fetchVoucherData();
});
</script>

<style scoped>
@media print {
    .preview-report {
        width: 100%;
    }
    .no-print {
        display: none !important;
    }

    .report-container,
    .preview-report {
        padding: 0 !important;
    }

    @page {
        size: landscape;
        margin: 20mm 30mm;
    }
}
</style>
