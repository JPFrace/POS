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
      class="w-full bg-white px-50 py-20 flex flex-col gap-y-8 justify-center text-[14px] leading-none preview-report"
    >
      <div class="w-full flex flex-col items-center justify-center">
        <!-- <h4>ST. SCHOLASTICA'S COLLEGE-WESTGROVE</h4>
                <p class="p-0 m-0">Ayala Westgrove Heights, Silang, Cavite</p>
                <p class="h3 font-bold py-5">Official Receipt Report</p> -->

        <div class="w-full flex justify-between mt-6 mb-6">
          <div class="flex flex-col gap-y-1 text-left leading-tight">
            <div>
              <span class="font-semibold ml-33 uppercase">
                {{ official_receipt?.customer_name }}
              </span>
            </div>

            <div>
              <span class="font-semibold ml-33">
                {{ official_receipt?.billing_address ?? "-" }}
              </span>
            </div>

            <div>
              <span class="ml-33">-</span>
            </div>
          </div>

          <div class="text-right mt-14">
            {{ formatDate(official_receipt?.journals?.[1]?.posted_at ?? "") }}
          </div>
        </div>

        <div class="w-full">
          <table class="w-full border-separate border-spacing-y-1">
            <thead>
              <tr class="uppercase !border-t-[2px] !border-b-[2px] no-print">
                <th class="w-[30%] text-start py-1">Item Description</th>
                <th class="w-[10%] text-center py-1">Qty.</th>
                <th class="w-[20%] text-end py-1">Unit Price</th>
                <th class="w-[20%] text-end py-1">Amount</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-if="!official_receipt?.journals?.length"
                class="leading-tight"
              >
                <td colspan="4" class="text-center py-6 text-gray-500 italic">
                  Not yet posted.
                </td>
              </tr>
              <tr
                v-for="(journal, index) in official_receipt?.journals"
                v-else
                :key="index"
                class="leading-tight"
              >
                <!-- Item Description -->
                <td class="text-start py-1 uppercase">
                  {{ journal?.description }}
                </td>

                <!-- Qty -->
                <td class="text-center py-1">
                  {{ official_receipt?.details?.[index]?.quantity }}
                </td>

                <!-- Unit Price -->
                <td class="text-end py-1">
                  {{ money(official_receipt?.details?.[index]?.rate, 2) }}
                </td>

                <!-- Amount -->
                <td class="text-end py-1">
                  {{ money(official_receipt?.details?.[index]?.sub_total, 2) }}
                </td>
              </tr>
            </tbody>
          </table>
          <div class="w-full mt-12 leading-tight">
            <div class="grid grid-cols-[40%_10%_20%_20%] gap-y-1">
              <!-- Total Amount -->
              <div class="col-start-3 text-right">
                <span class="font-bold">Total Amount</span>
              </div>
              <div class="col-start-4 text-end">
                {{ money(official_receipt?.total, 2) }}
              </div>

              <!-- Less Discount -->
              <div class="col-start-3 text-right">
                <span class="font-bold">Less Discount</span>
              </div>
              <div class="col-start-4 text-end">{{}}</div>

              <!-- Discount Type -->
              <div class="col-start-3 text-right">
                <span class="font-bold">(SC/PWD/NAAC/MOV/SP)</span>
              </div>
              <div class="col-start-4 text-end">{{}}</div>

              <!-- Payment Method -->
              <div
                class="col-start-2 text-start uppercase font-bold"
              >
                {{ official_receipt?.references }}
              </div>

              <!-- Total Amount Due -->
              <div class="col-start-3 text-right">
                <span class="font-bold">Total Amount Due</span>
              </div>
              <div class="col-start-4 text-end">
                {{ money(official_receipt?.total, 2) }}
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
import { formatDate, money } from "~/utils/helper";
import type { OfficialReceipt } from "~/types/official-receipts";

const route = useRoute();
const uuid = route.params.uuid;

const official_receipt = ref<OfficialReceipt | null>(null);
const isLoading = ref(true);

const { t } = useI18n();
const { $message } = useNuxtApp();

async function fetchReceiptData() {
  try {
    if (typeof uuid !== "string" || !uuid.trim()) {
      $message("info", t("money_receive.info.no_receipt_data"));
      return;
    }
    const result = await useClient(`/api/reports/official-receipt-report`, {
      method: "GET",
      params: {
        uuid: route.params.uuid,
      },
    });

    official_receipt.value = result.official_receipt;
  } catch {
    $message("error", t("error.failed_to_load"));
  } finally {
    isLoading.value = false;
  }
}

onMounted(() => {
  fetchReceiptData();
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
