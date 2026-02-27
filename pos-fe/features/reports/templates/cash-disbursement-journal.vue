<template>
  <div class="p-16 w-full flex items-center justify-center report-container print:p-0">
    <div
      class="w-full bg-white p-4 flex flex-col gap-y-8 items-center justify-center preview-report print:p-0 print:w-full"
    >
      <div class="w-full flex flex-col items-center justify-center">
        <h4>ST. SCHOLASTICA'S COLLEGE-WESTGROVE, INC.</h4>
        <p class="p-0 m-0 font-bold">Cash Disbursements Journal</p>
        <p class="p-0 m-0 font-bold">
          For the Period From {{ date1 }} to {{ date2 }}
        </p>
      </div>
      <div class="w-full overflow-x-auto">
        <table class="w-full table-fixed">
          <thead>
            <tr class="uppercase !border-t-2 !border-b-2 border-black text-xs">
              <th class="w-[6%] min-w-[4rem] text-start">Date</th>
              <th class="w-[5%] min-w-[3.5rem] text-start">Ref</th>
              <th class="w-[8%] min-w-[5rem] text-start">Check#</th>
              <th class="w-[18%] min-w-[8rem] text-start">Name</th>
              <th class="w-[18%] min-w-[8rem] text-start">Line Description</th>
              <th class="w-[8%] min-w-[4.5rem] text-start">Account ID</th>
              <th class="w-[18%] min-w-[8rem] text-start">Account Description</th>
              <th class="w-[10%] min-w-[4.5rem] text-end">Debit Amount</th>
              <th class="w-[9%] min-w-[4.5rem] text-end">Credit Amount</th>
            </tr>
          </thead>
          <tbody>
            <template v-for="(row, rowIndex) in data?.data ?? []" :key="rowIndex">
              <tr
                v-for="(journal, jIndex) in row.journals ?? []"
                :key="`${rowIndex}-${jIndex}`"
                class="text-xs"
              >
                <td
                  v-if="jIndex === 0"
                  :rowspan="(row.journals ?? []).length"
                  class="align-top border-b border-gray-200"
                >
                  {{ row.date }}
                </td>
                <td
                  v-if="jIndex === 0"
                  :rowspan="(row.journals ?? []).length"
                  class="align-top border-b border-gray-200 text-blue-500 hover:underline cursor-pointer print:text-black print:no-underline"
                  @click="openEditLink(row)"
                >
                  {{ row.ref_no }}
                </td>
                <td
                  v-if="jIndex === 0"
                  :rowspan="(row.journals ?? []).length"
                  class="align-top border-b border-gray-200"
                >
                  {{ row.check_no }}
                </td>
                <td
                  v-if="jIndex === 0"
                  :rowspan="(row.journals ?? []).length"
                  class="align-top border-b border-gray-200 max-w-0 break-words"
                >
                  {{ row.payee?.full_name?.toUpperCase() ?? "" }}
                </td>
                <td class="align-top border-b border-gray-200 max-w-0 break-words">
                  {{
                    jIndex < (row.details ?? []).length
                      ? (row.details[jIndex]?.product_name ?? row.details[jIndex]?.product?.name ?? "")
                      : (row.payee?.full_name?.toUpperCase() ?? "")
                  }}
                </td>
                <td class="text-start align-top border-b border-gray-200">
                  {{ journal.chart_account?.code ?? "" }}
                </td>
                <td class="text-start align-top border-b border-gray-200 max-w-0 break-words">
                  {{ journal.chart_account?.name ?? "" }}
                </td>
                <td class="text-end align-top border-b border-gray-200">
                  {{ money(journal.debit, 2) }}
                </td>
                <td class="text-end align-top border-b border-gray-200">
                  {{ money(journal.credit, 2) }}
                </td>
              </tr>
              <tr class="text-xs">
                <td colspan="9" class="border-b border-gray-100 py-1">&nbsp;</td>
              </tr>
            </template>
            <tr>
              <td colspan="9">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="5">&nbsp;</td>
              <td class="font-bold">Total</td>
              <td>&nbsp;</td>
              <td class="text-end font-bold !border-b-4">
                {{ money(data?.total?.debits ?? 0, 2) }}
              </td>
              <td class="text-end font-bold !border-b-4">
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
@page {
  size: landscape;
}
</style>
<script setup lang="ts">
import moment from "moment";

const company = ref(import.meta.env.VITE_APP_COMPANY_NAME);

const dates = defineModel();

const key = `${id(useRoute().fullPath)}.cash-disbursement-journal`;
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
    client("/api/reports/cash-disbursement-journal", {
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
