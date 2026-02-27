<template>
    <!-- Loading State -->
    <div
        v-if="isLoading"
        class="h-screen flex justify-center mt-4 ml-4"
        role="status"
        aria-live="polite"
    >
        <div class="flex items-center space-y-5 gap-5">
            <div class="spinner-border text-blue-500" aria-hidden="true"></div>
            <p class="text-gray-500 font-medium text-[14px]">Loading...</p>
        </div>
    </div>

    <div
        v-else
        class="p-16 w-full flex items-center justify-center report-container"
    >
        <div
            class="w-[100%] bg-white px-50 py-20 flex flex-col gap-y-8 justify-center text-[14px] leading-none preview-report"
        >
            <div class="w-full flex flex-col items-center justify-center">
                <h4>ST. SCHOLASTICA'S COLLEGE-WESTGROVE</h4>
                <p class="p-0 m-0">Ayala Westgrove Heights, Silang, Cavite</p>
                <p class="h3 font-bold py-5">JOURNAL VOUCHER</p>
                <div class="w-[30%] flex flex-col my-20">
                    <!-- Ref No -->
                    <div class="flex w-full">
                        <span class="font-bold w-[50%] text-left">
                            Reference
                        </span>
                        <span>
                            {{ journal_entry?.journal_entries?.je_no }}
                        </span>
                    </div>

                    <!-- Date -->
                    <div class="flex w-full mt-1">
                        <span class="font-bold w-[50%] text-left"> Date </span>
                        <span class="whitespace-nowrap overflow-visible">
                            {{
                                new Date(
                                    journal_entry?.journal_entries?.date
                                ).toLocaleDateString("en-US", {
                                    month: "long",
                                    day: "numeric",
                                    year: "numeric",
                                })
                            }}
                        </span>
                    </div>
                </div>

                <div class="w-full">
                    <div class="w-full">
                        <div class="w-full">
                            <table
                                class="w-full border-separate border-spacing-y-1"
                            >
                                <!-- spacing-y-1 -->
                                <thead>
                                    <tr
                                        class="uppercase !border-t-[2px] !border-b-[2px]"
                                    >
                                        <th class="w-[10%] text-start py-1">
                                            Date
                                        </th>
                                        <th class="w-[15%] text-start py-1">
                                            Code
                                        </th>
                                        <th class="w-[20%] text-start py-1">
                                            Name
                                        </th>
                                        <th class="w-[12%] text-end py-1">
                                            Debit Amt
                                        </th>
                                        <th class="w-[12%] text-end py-1">
                                            Credit Amt
                                        </th>
                                        <th class="w-[12%] text-end py-1">
                                            Remarks
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    v-if="
                                        journal_entry?.journal_entries?.details
                                            ?.length
                                    "
                                >
                                    <tr
                                        v-for="(row, index) in journal_entry
                                            .journal_entries.details"
                                        :key="row.id ?? index"
                                    >
                                        <td
                                            v-if="index === 0"
                                            :rowspan="
                                                journal_entry?.journal_entries
                                                    ?.details?.length ?? 1
                                            "
                                            class="align-top py-1"
                                        >
                                            {{
                                                moment(
                                                    journal_entry?.journal_entries
                                                        ?.date
                                                ).format("MM/DD/YYYY")
                                            }}
                                        </td>
                                        <td class="py-1">
                                            {{ row.chart_account?.code }}
                                        </td>
                                        <td class="py-1">
                                            {{ row.chart_account?.name }}
                                        </td>
                                        <td class="text-end py-1">
                                            {{ Number(row.debit) !== 0 ? money(row.debit, 2) : "" }}
                                        </td>
                                        <td class="text-end py-1">
                                            {{ Number(row.credit) !== 0 ? money(row.credit, 2) : "" }}
                                        </td>
                                        <td class="text-end py-1">  
                                            {{ row.description }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-row pt-20">
                <span class="pr-2 text-right font-bold">Explanation:</span>
                <span class="pr-2 text-right font-bold">
                    {{ journal_entry?.journal_entries?.memo }}
                </span>
            </div>
            <div
                class="w-full flex flex-row justify-between mt-12 space-y-6 text-sm"
            >
                <!-- Prepared By -->
                <div class="flex flex-col items-center">
                    <div class="w-64 border-b border-black mr-3"></div>
                    <span class="font-bold">Prepared By:</span>
                </div>

                <!-- Audited By -->
                <div class="flex flex-col items-center">
                    <div class="w-64 border-b border-black mr-3"></div>
                    <span class="font-bold">Audited By:</span>
                </div>

                <!-- Approved / Noted -->
                <div class="flex flex-col items-center">
                    <div class="w-64 border-b border-black mr-3"></div>
                    <span class="font-bold">Noted By:</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { useRoute } from "vue-router";
import moment from "moment";
import type { JournalEntry } from "~/types/journal-entry";

const route = useRoute();
const uuid = route.params.uuid;

const journal_entry = ref<JournalEntry | null>(null);
const isLoading = ref(true);

const { t } = useI18n();
const { $message } = useNuxtApp();

async function fetchVoucherData() {
    try {
        if (typeof uuid !== "string" || !uuid.trim()) {
            $message("info", t("journal.info.no_journal_entry_data"));
            return;
        }
        const result = await useClient(`/api/reports/journal-voucher`, {
            method: "GET",
            params: {
                uuid: route.params.uuid,
            },
        });

        journal_entry.value = result;
    } catch {
        $message("error", t("error.failed_to_load"));
    } finally {
        isLoading.value = false;
    }
}

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
