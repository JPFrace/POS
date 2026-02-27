<template>
  <form class="space-y-4 py-1 px-1">
    <div class="flex-1 flex flex-col">
      <span class="text-sm font-semibold mb-2">Account No.</span>
      <Input
        v-model="form.account_number"
        placeholder="Enter Account No."
        :is-valid="isValid('account_number')"
      />
    </div>
    <div class="flex-1 flex flex-col">
      <span class="text-sm font-semibold mb-2">Account Name</span>
      <Input
        v-model="form.account_name"
        placeholder="Enter Account Name"
        :is-valid="isValid('account_name')"
      />
    </div>
    <div class="flex-1 flex flex-col">
      <span class="text-sm font-semibold mb-2">Bank Code</span>
      <Input
        v-model="form.bank_code"
        placeholder="Enter Bank Code"
        :is-valid="isValid('bank_code')"
      />
    </div>
    <div class="flex-1 flex flex-col">
      <span class="text-sm font-semibold mb-2">Bank Name</span>
      <Input
        v-model="form.bank_name"
        placeholder="Enter Bank Name"
        :is-valid="isValid('bank_name')"
      />
    </div>
    <div class="flex flex-col flex-1">
      <span class="mb-2 font-semibold text-sm">Cash In Bank</span>
      <Select        
        url="/api/accounting/chart-accounts"
        v-model:data="chartAccounts"
        v-model:selected="form.chart_account"
        :mapResult="
          (result: any) =>
            result.data.map((row: ChartAccount) => ({
              id: row.uuid,
              value: row.uuid,
              label: `${row.code} - ${row.name}`,
              code: row.code,
              description: row.description,
            }))
        "
        :mapQuery="
          (search: any) => ({
            query: {
              name_code: search,
              cash_in_bank: true,
            },
          })
        "
        :is-valid="isValid('chart_account')"
        clearable
        remote
        loading
      >
        <template #customColumn="{ data }">
          <AccountColumn :data="data" />
        </template>
      </Select>
    </div>
    <Checkbox
      v-model="form.is_inactive"
      label="Inactive"
      :is-valid="isValid('is_inactive')"
      size="sm"
    />
  </form>
</template>

<script lang="ts" setup>
import type { BankAccounts } from "~/types/bank-accounts";
import type { ChartAccount } from "~/types/chart-account";
import type { Option } from "~/types/form";
const { yup } = useYup();
const chartAccounts = ref<Option[]>();

interface Props {
  errors?: object;
  schema: Function;
  form: Function;
  data?: BankAccounts;
}

const props = defineProps<Props>();

const form = ref<BankAccounts>({
  account_name: "",
  account_number: "",
  bank_name: "",
  bank_code: "",
  chart_account: null,
  is_inactive: false,
});

const Yup = yup();

const isValid = (key: string) =>
  props.errors
    ? Object.keys(props.errors).includes(key)
      ? (props?.errors as any)[key]?.length <= 0
      : null
    : null;

const setForm = (value: any) => {
  form.value = {
    account_name: value.account_name ?? "",
    account_number: value.account_number ?? "",
    bank_name: value.bank_name ?? "",
    bank_code: value.bank_code ?? "",    
    chart_account: value.chart_account
      ? {
          id: value.chart_account.uuid,
          value: value.chart_account.uuid,
          label: `${value.chart_account.code} - ${value.chart_account.name}`,
          code: value.chart_account.code,
          description: value.chart_account.description,
        }
      : null,
    is_inactive: value.is_inactive ?? false,
  };

  if (value.chart_account) {
    chartAccounts.value = [
      {
        id: value.chart_account.uuid,
        value: value.chart_account.uuid,
        label: `${value.chart_account.code} - ${value.chart_account.name}`,
        code: value.chart_account.code,
        description: value.chart_account.description,
      } as Option,
    ];
  } 
};

watch(
  form,
  (value) => {
    props.form(value);
  },
  {
    deep: true,
  }
);

onMounted(() => {
  if (props.data) {
    setForm(props.data);
  }

  props.schema(
    Yup.object().shape({
      account_name: Yup.string().notRequired(),
      account_number: Yup.string().notRequired(),
      bank_name: Yup.string().notRequired(),
      bank_code: Yup.string().notRequired(),
      chart_account: Yup.object().notRequired().nullable(),
      is_inactive: Yup.boolean().notRequired().nullable(),
    })
  );
});
</script>
