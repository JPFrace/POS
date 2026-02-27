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
            <!-- Date with Format -->
            <div class="text-right translate-y-[26px] tracking-wider">
                <p class="flex justify-end items-baseline">
                    <span class="mr-2">Date:</span>
                    <span
                        class="min-w-[6rem] font-medium flex flex-col leading-tight"
                    >
                        <span>{{ formatDate2(payment?.date ?? "") }}</span>
                        <span class="text-xs text-gray-500 tracking-[0.25em]"
                            >MM-DD-YYYY</span
                        >
                    </span>
                </p>
            </div>

            <!-- Layout Info -->
            <div class="flex gap-2 pl-8 font-medium items-center">
                <!-- PAY TO THE ORDER OF (Payee Name) -->
                <div class="whitespace-nowrap leading-snug">
                    <p>
                        PAY TO THE <br />
                        ORDER OF
                    </p>
                </div>

                <!-- Payee Name -->
                <div class="flex-1 relative pl-10 mt-2">
                    <strong class="inline-block">{{
                        payment?.payee_name
                    }}</strong>
                    <div
                        class="absolute left-10 right-0 bottom-[-2px] border-b border-black"
                    />
                </div>

                <!-- Peso Amount -->
                <div class="whitespace-nowrap leading-snug">
                    <div class="flex items-center flex-nowrap">
                        <!-- Peso sign -->
                        <span class="text-[18px] mr-1">₱</span>

                        <!-- Amount -->
                        <span
                            class="flex-grow min-w-[13rem] min-h-[24px] border-b border-black text-[16px] text-right"
                        >
                            {{ money(cashInBank?.credit, 2) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex gap-2 pl-8 font-medium">
                <!-- Pesos Label -->
                <div class="whitespace-nowrap leading-snug">
                    <p>PESOS</p>
                </div>

                <!-- Pesos Value in Words -->
                <div class="flex-1">
                    <div class="flex flex-col pl-2">
                        <strong class="inline-block text-center uppercase">{{
                            payment?.net_in_words
                        }}</strong>
                        <div class="border-b border-black w-full mt-1" />
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

async function fetchLayoutData() {
    try {
        if (typeof uuid !== "string" || !uuid.trim()) {
            $message("info", t("make_payment.info.no_payment_data"));
            return;
        }
        const result = await useClient(`/api/reports/cheque-layout`, {
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
        journals.value.find(
            (row) => row?.chart_account?.usage_type?.code === "CASH_IN_BANK"
        ) ?? null
);

onMounted(() => {
    fetchLayoutData();
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
        margin: 20mm 30mm;
    }
}
</style>
