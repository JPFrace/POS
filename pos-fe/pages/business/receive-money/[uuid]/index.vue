<template>
  <div>
    <NuxtLayout>
      <div class="flex flex-col gap-y-8">
        <Header v-model="form" :errors="errors" />
        <Body v-model="form" :errors="errors" />
        <Other v-model="form" :errors="errors" />
      </div>
    </NuxtLayout>
  </div>
</template>

<script lang="ts" setup>
import Header from "~/features/business/receive-money/components/header.vue";
import Body from "~/features/business/receive-money/components/body.vue";
import Other from "~/features/business/receive-money/components/other.vue";
import moment from "moment";
import type { Option } from "~/types/form";
import { usePageTitle } from "~/composables/usePageTitle";
import type {
  Denomination,
  OfficialReceipt,
  OfficialReceiptItem,
} from "~/types/official-receipts";

usePageTitle();
definePageMeta({
  permission: "Business.Receive Money.Edit",
});

const { send, receive, dismiss } = usePageEvent();

const tableRows = 4;
const errors = ref();

const item = ref<Partial<OfficialReceiptItem>>({
  product: undefined,
  rate: 0,
  tax_rate: 0,
  quantity: 0,
  product_name: "",
  product_description: "",
  active: false,
});

const items: Partial<OfficialReceiptItem>[] = [];

for (let i = 0; i < tableRows; i++) {
  items.push({ ...item.value });
}

const form = ref<Partial<OfficialReceipt>>({
  date: moment().format("MM/DD/YYYY"), // Set default date
  ref_no: null,
  ref_no_auto: true,
  or_no: null,
  or_no_auto: true,
  remarks: null,
  attachment: null,
  customer: undefined,
  customer_idno: null,
  customer_name: null,
  customer_email: null,
  billing_address: null,
  dimension: null,
  items,
});

const fill = () => {
  const items = [];
  for (let i = 0; i < tableRows; i++) {
    items.push({ ...item.value });
  }

  form.value.items = items;
};

const refill = () => {
  for (let i = (form.value?.items ?? []).length - 1; i < 3; i++) {
    form.value!.items = (form.value!.items ?? []).concat([
      {
        product: undefined,
        rate: 0,
        quantity: 0,
        product_name: "",
        product_description: "",
        active: false,
      },
    ]);
  }
};

const route = useRoute();
const client = useSanctumClient();
const { data: OR, refresh } = useAsyncData<OfficialReceipt[]>(
  `${id(useRoute().fullPath)}.money-receives`,
  () =>
    client("/api/business/official-receipts", {
      method: "GET",
      params: {
        query: {
          uuid: route.params.uuid,
          details: true,
          denominations: true,
          customer: true,
          file: true,
          status: true,
          "transdim.dimension": true,
          "details.product.expense": true,
          "details.product.sales_tax": true,
          "details.product.withholding_tax": true,
        },
      },
    }),
  {
    server: false,
    lazy: true,
    immediate: true,
  },
);

const fillItem = () => {
  if (!OR.value?.data?.[0]) {
    return;
  }
  const orData = OR.value.data[0] ?? [];
  const { details, customer, deposit, ...filtered } = orData;
  form.value = {
    ...filtered,
    or_no_auto: true,
    ref_no_auto: true,
    attachment: orData.file,
    customer: {
      id: orData.customer.uuid,
      value: orData.customer.uuid,
      label: orData.customer.full_name,
      type: orData.customer.type_label,
      id_no: orData.customer.id_no,
      email: orData.customer.email,
      billing_address: orData.customer.billing_address,
    },
  };
  const items: Partial<OfficialReceiptItem>[] = [];
  (orData?.details ?? []).forEach((row: any) => {
    items.push({
      product: {
        ...row.product,
        id: row.product.uuid,
        value: row.product.uuid,
        label: `${row.product.sku}#${row.product.name}`,
      },
      rate: row.rate,
      tax_rate: row.tax_rate,
      quantity: row.quantity,
      product_name: row.product_name,
      product_description: row.product_description,
      active: true,
      product_active: false,
    });
  });

  const denominations: Partial<Denomination>[] = [];
  (orData?.denominations ?? []).forEach((row: any) => {
    const dep = row.deposit_account;
    const pm = row.payment_method;
    const qty = Number(row.quantity) ?? 0;
    const denom = Number(row.denomination) ?? 0;
    denominations.push({
      uuid: row.uuid,
      depositAccount: dep
        ? {
            id: dep.uuid,
            value: dep.uuid,
            label: `${dep.name ?? ""}`,
          }
        : null,
      payment_method: pm
        ? {
            id: pm.uuid,
            value: pm.uuid,
            label: pm.name ?? pm.code ?? "",
          }
        : null,
      quantity: qty,
      denomination: denom,
      bank: row.bank ?? "",
      reference_date: row.reference_date ?? "",
      reference_no: row.reference_no ?? "",
      amount: qty * denom,
    });
  });

  form.value.items = items;
  form.value.denominations = denominations;

  const dimensions: Option[] = [];
  (orData?.transaction_dimensions ?? []).forEach((row: any) => {
    dimensions.push({
      id: row.dimension.uuid,
      value: row.dimension.uuid,
      label: row.dimension.name,
    });
  });
  form.value.dimension = dimensions;
  refill();
};

onBeforeUnmount(() => {
  dismiss("on:create-new");
  dismiss("on:error");
  dismiss("on:new-line");
  dismiss("on:clear-lines");
});

watch(OR, () => {
  fillItem();
});

onMounted(() => {
  receive("on:create-new", (_value: any) => {
    const currentDate = form.value.date; // Preserve the date
    clearKeyValue(form.value);
    form.value.date = currentDate; // Restore the date after submit
    errors.value = [];
    fill();
    form.value.or_no_auto = true;
    form.value.ref_no_auto = true;
  });

  receive("on:error", (value: any) => {
    errors.value = value;
  });

  receive("on:new-line", (_value: any) => {
    form.value.items = (form.value.items ?? []).concat({ ...item.value });
  });

  receive("on:clear-lines", (_value: any) => {
    fill();
  });
});
</script>
