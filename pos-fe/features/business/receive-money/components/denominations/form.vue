<template>
  <form class="space-y-4 py-1 px-1">
    <div class="table-responsive">
      <table
        class="table table-hover table-row-gray-300 table-rounded gy-2 gs-2"
      >
        <thead>
          <tr
            class="border-gray-200 border-bottom-2 text-gray-800 fw-semibold fs-6"
          >
            <th style="width: 140px">DEPOSIT TO</th>
            <th style="width: 140px">PAYMENT METHOD</th>
            <th class="text-center" style="width: 120px; width: 130px">DENOMINATION</th>
            <th class="text-center" style="width: 80px; width: 90px">QUANTITY</th>
            <th class="text-center" style="width: 120px; width: 130px">AMOUNT</th>
            <th v-if="!readOnly" class="text-center" style="width: 15px">...</th>
          </tr>
        </thead>
        <tbody>
          <template v-for="(row, rowIndex) in tableRows" :key="row._key">
            <tr>
              <td
                class="align-middle"
                :class="{
                  'table-cell-error': hasRowError(rowIndex, 'depositAccount'),
                }"
              >
                <Select
                  ref="accounts"
                  column
                  custom-column
                  url="/api/accounting/chart-accounts"
                  v-model:data="deposits"
                  v-model:selected="row.depositAccount"
                  :mapResult="
                    (result: any) =>
                      result.data.map((row: ChartAccount) => ({
                        id: row.uuid,
                        value: row.uuid,
                        label: `${row.name}`,
                        code: row.code,
                        description: row.description,
                        columns: ['account'],
                        children: row.children?.map((children) => ({
                          id: children.uuid,
                          value: children.uuid,
                          label: `${children.name}`,
                          code: children.code,
                          description: children.description,
                        })),
                      }))
                  "
                  :mapQuery="
                    (search: any) => ({
                      query: {
                        name_code: search,
                        'cash_in_bank.undeposited': true,
                      },
                    })
                  "
                  :is-valid="isValidRow(rowIndex, 'depositAccount')"
                  clearable
                  remote
                  loading
                >
                  <template #customColumn="{ data }">
                    <AccountColumn :data="data" />
                  </template>
                </Select>
              </td>
              <td
                class="align-middle"
                :class="{
                  'table-cell-error': hasRowError(rowIndex, 'payment_method'),
                }"
              >
                <Select
                  url="/api/setup/payment-types"
                  v-model:data="paymentMethods"
                  v-model:selected="row.payment_method"
                  :mapResult="
                    (result: any) =>
                      result.data.map((row: PaymentTypes) => ({
                        id: row.uuid,
                        value: row.uuid,
                        label: row.name,
                        code: row.code,
                      }))
                  "
                  :mapQuery="
                    (search: any) => ({
                      query: { name: search },
                    })
                  "
                  :is-valid="isValidRow(rowIndex, 'payment_method')"
                  clearable
                  remote
                  loading
                />
              </td>
              <td
                class="align-middle"
                :class="{
                  'table-cell-error': hasRowError(rowIndex, 'denomination'),
                }"
              >
                <Currency
                  v-model="row.denomination"
                  placeholder="0.00"
                  class="text-center"
                  :is-valid="isValidRow(rowIndex, 'denomination')"
                />
              </td>
              <td
                class="align-middle"
                :class="{
                  'table-cell-error': hasRowError(rowIndex, 'quantity'),
                }"
              >
                <Currency
                  v-model="row.quantity"
                  placeholder="0"
                  :is-valid="isValidRow(rowIndex, 'quantity')"
                  class="text-center"
                />
              </td>
              <td
                class="align-middle text-end"
                :class="{ 'table-cell-error': hasRowError(rowIndex, 'amount') }"
              >
                <Input
                  :model-value="row.amount"
                  placeholder="0.00"
                  readonly
                  class="text-end"
                  :is-valid="isValidRow(rowIndex, 'amount')"
                />
              </td>
              <td v-if="!readOnly" class="text-center align-middle">
                <KTIcon
                  icon-name="trash"
                  icon-class="fs-2 cursor-pointer"
                  @click="removeRow(rowIndex)"
                  title="Remove row"
                />
              </td>
            </tr>
            <!-- Sub-row: Bank, Date, Reference No. (only when a non-CASH payment method is selected) -->
            <tr
              v-if="row.payment_method?.value && !isCashPayment(row)"
              class="denomination-details-row"
            >
              <td colspan="6" class="align-middle py-2 bg-light">
                <div class="d-flex flex-wrap gap-3 align-items-end">
                  <div
                    class="flex-grow-1"
                    :class="{
                      'table-cell-error': hasRowError(rowIndex, 'bank'),
                    }"
                    style="min-width: 140px"
                  >
                    <label class="form-label small text-muted mb-1">Bank</label>
                    <Input
                      v-model="row.bank"
                      placeholder="Bank"
                      :is-valid="isValidRow(rowIndex, 'bank')"
                      class="w-100"
                    />
                  </div>
                  <div
                    :class="{
                      'table-cell-error': hasRowError(
                        rowIndex,
                        'reference_date',
                      ),
                    }"
                    style="min-width: 140px"
                  >
                    <label class="form-label small text-muted mb-1">Date</label>
                    <Input
                      v-model="row.reference_date"
                      type="date"
                      placeholder="Date"
                      :is-valid="isValidRow(rowIndex, 'reference_date')"
                      class="w-100"
                    />
                  </div>
                  <div
                    class="flex-grow-1"
                    :class="{
                      'table-cell-error': hasRowError(rowIndex, 'reference_no'),
                    }"
                    style="min-width: 140px"
                  >
                    <label class="form-label small text-muted mb-1"
                      >Reference No.</label
                    >
                    <Input
                      v-model="row.reference_no"
                      placeholder="Reference No."
                      :is-valid="isValidRow(rowIndex, 'reference_no')"
                      class="w-100"
                    />
                  </div>
                </div>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
  </form>
</template>

<script lang="ts" setup>
import type { ChartAccount } from "~/types/chart-account";
import type { Option } from "~/types/form";
import type { Denomination } from "~/types/official-receipts";
import type { PaymentTypes } from "~/types/payment-types";
import { useYup } from "~/composables/useYup";
import { currencyFormat, numberOnly, uuid } from "~/utils/helper";
import AccountColumn from "./account-column.vue";

const { yup } = useYup();
const deposits = ref<Option[]>();
const paymentMethods = ref<Option[]>();

interface Row {
  _key?: string;
  uuid?: string;
  depositAccount?: Option;
  payment_method?: Option;
  quantity?: number;
  denomination?: number | null;
  bank?: string;
  reference_date?: string;
  reference_no?: string;
  amount?: number | null;
}

function emptyRow(): Row {
  return {
    _key: uuid(),
    uuid: undefined,
    depositAccount: props.defaultDeposit ?? undefined,
    payment_method: undefined,
    quantity: 0,
    denomination: null,
    bank: "",
    reference_date: "",
    reference_no: "",
    amount: null,
  };
}

interface Props {
  errors?: object;
  isValid?: (index: number, key: string) => null | false;
  schema: (s: unknown) => void;
  form: (value: Partial<Denomination>[]) => void;
  data?: Partial<Denomination> | Partial<Denomination>[] | null;
  defaultDeposit?: Option;
  readOnly?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  readOnly: false,
});

const DEFAULT_ACTIVE_ROWS = 1;
const tableRows = ref<Row[]>(
  Array.from({ length: DEFAULT_ACTIVE_ROWS }, () => emptyRow()),
);

function defaultIsValidRow(index: number, field: string) {
  const key = `denominations.${index}.${field}`;
  if (!props.errors) return null;
  if (!Object.keys(props.errors).includes(key)) return null;
  const err = (props.errors as Record<string, { length?: number }>)[key];
  return (err?.length ?? 0) <= 0;
}

function isValidRow(index: number, field: string) {
  if (props.isValid) return props.isValid(index, field);
  return defaultIsValidRow(index, field);
}

function hasRowError(index: number, field: string) {
  return isValidRow(index, field) === false;
}

function addRow() {
  const lastRow = tableRows.value[tableRows.value.length - 1];
  const newRow = emptyRow();
  if (lastRow) {
    newRow.depositAccount =
      lastRow.depositAccount != null
        ? { ...lastRow.depositAccount }
        : undefined;
  }
  tableRows.value.push(newRow);
}

function removeRow(index: number) {
  tableRows.value.splice(index, 1);
  if (tableRows.value.length === 0) {
    tableRows.value.push(emptyRow());
  }
}

const totalAmount = computed(() =>
  tableRows.value.reduce((sum, row) => sum + numberOnly(row.amount), 0),
);

function toDenominations(rows: Row[]): Partial<Denomination>[] {
  return rows.map((row) => ({
    uuid: row.uuid,
    depositAccount: row.depositAccount ?? null,
    payment_method: row.payment_method ?? null,
    quantity: numberOnly(row.quantity) || 0,
    denomination: numberOnly(row.denomination) || 0.00,
    bank: row.bank ?? "",
    reference_date: row.reference_date ?? "",
    reference_no: row.reference_no ?? "",
    amount: numberOnly(row.amount) || 0.00,
  }));
}

function hasDepositAndPayment(row: Row) {
  const hasDeposit =
    row.depositAccount?.value != null && row.depositAccount.value !== "";
  const hasPayment =
    row.payment_method?.value != null && row.payment_method.value !== "";
  return hasDeposit && hasPayment;
}

function isCashPayment(row: Row): boolean {
  const label = (row.payment_method?.label ?? "")
    .toString()
    .toLowerCase()
    .trim();
  const code = (row.payment_method as { code?: string } | undefined)?.code
    ?.toLowerCase()
    .trim();
  return label === "cash" || code === "cash";
}

function isPaymentMethodCashVal(pm: unknown): boolean {
  if (!pm || typeof pm !== "object") return false;
  const o = pm as { label?: string; code?: string };
  const label = (o.label ?? "").toString().toLowerCase().trim();
  const code = (o.code ?? "").toString().toLowerCase().trim();
  return label === "cash" || code === "cash";
}

watch(
  () => tableRows.value,
  (rows) => {
    rows.forEach((row) => {
      const qtyNum = numberOnly(row.quantity) || 0;
      if (hasDepositAndPayment(row) && qtyNum === 0) {
        row.quantity = 1;
      }
      const qty = numberOnly(row.quantity) || 0;
      const denom = numberOnly(row.denomination) || 0;
      row.amount = money(qty * denom, 2) ?? null;
    });
    props.form(toDenominations(rows));
  },
  { deep: true },
);

function asOptionIfAlready(val: unknown): Option | undefined {
  if (!val || typeof val !== "object") return undefined;
  const o = val as Record<string, unknown>;
  const value = o.value ?? o.uuid;
  const label = o.label ?? (o.name as string) ?? (o.code as string);
  if (value == null && label == null) return undefined;
  return {
    id: (o.id ?? value) as number,
    value: value as string | number | null,
    label: typeof label === "string" ? label : "",
  };
}

function toOptionFromChartAccount(
  acc: ChartAccount | Option | null | undefined,
): Option | undefined {
  if (!acc) return undefined;
  const asOpt = asOptionIfAlready(acc);
  if (asOpt) return asOpt;
  const a = acc as ChartAccount;
  return {
    id: a.uuid as unknown as number,
    value: a.uuid,
    label: `${a.code} - ${a.name}`,
  };
}

function toOptionFromPaymentType(
  pt: PaymentTypes | Option | null | undefined,
): Option | undefined {
  if (!pt || typeof pt !== "object") return undefined;
  const asOpt = asOptionIfAlready(pt);
  if (asOpt) return asOpt;
  const p = pt as { uuid?: string; name?: string; code?: string };
  return {
    id: (p.uuid ?? p.code) as unknown as number,
    value: p.uuid ?? p.code ?? "",
    label: p.name ?? p.code ?? "",
  };
}

function setForm(
  value: Partial<Denomination> | Partial<Denomination>[] | null | undefined,
) {
  if (!value) {
    tableRows.value = Array.from({ length: DEFAULT_ACTIVE_ROWS }, () =>
      emptyRow(),
    );
    return;
  }
  const list = Array.isArray(value) ? value : [value];
  tableRows.value =
    list.length > 0
      ? list.map((item) => ({
          _key: uuid(),
          uuid: item.uuid,
          depositAccount: toOptionFromChartAccount(
            item.depositAccount as ChartAccount | null,
          ),
          payment_method: toOptionFromPaymentType(
            item.payment_method as PaymentTypes | null,
          ),
          quantity: item.quantity ?? 0,
          denomination: item.denomination ?? 0,
          bank: item.bank ?? "",
          reference_date: item.reference_date ?? "",
          reference_no: item.reference_no ?? "",
          amount: item.amount ?? 0,
        }))
      : [emptyRow()];
}

onMounted(() => {
  if (props.data) {
    setForm(props.data);
  }
  const Yup = yup();
  props.schema(
    Yup.object().shape({
      denominations: Yup.array().of(
        Yup.object().shape({
          depositAccount: Yup.object()
            .shape({ value: Yup.string().required() })
            .required("Deposit To is required"),
          payment_method: Yup.mixed()
            .required("Payment Method is required")
            .test(
              "has-value",
              "Payment Method is required",
              (v: any) =>
                v != null &&
                (typeof v !== "object" ? true : !!(v?.value ?? v?.uuid)),
            ),
          quantity: Yup.number().when("denomination", {
            is: (val: number) => val != null && Number(val) > 0,
            then: (schema) =>
              schema
                .required("Quantity is required")
                .min(1, "Quantity must be more than zero"),
            otherwise: (schema) =>
              schema
                .notRequired()
                .test(
                  "positive",
                  "Quantity must be more than zero",
                  (v: unknown) => v == null || v === "" || Number(v) > 0,
                ),
          }),
          denomination: Yup.number()
            .notRequired()
            .test(
              "positive",
              "Denomination must be more than zero",
              (v: unknown) => v == null || v === "" || Number(v) > 0,
            ),
          bank: Yup.string().when("payment_method", {
            is: isPaymentMethodCashVal,
            then: (s) => s.notRequired(),
            otherwise: (s) =>
              s.required("Bank is required for non-cash payment"),
          }),
          reference_date: Yup.string().when("payment_method", {
            is: isPaymentMethodCashVal,
            then: (s) => s.notRequired(),
            otherwise: (s) =>
              s.required("Date is required for non-cash payment"),
          }),
          reference_no: Yup.string().when("payment_method", {
            is: isPaymentMethodCashVal,
            then: (s) => s.notRequired(),
            otherwise: (s) =>
              s.required("Reference No. is required for non-cash payment"),
          }),
          amount: Yup.number()
            .notRequired()
            .test(
              "positive",
              "Amount must be more than zero",
              (v: unknown) => v == null || v === "" || Number(v) > 0,
            ),
        }),
      ),
    }),
  );
});

const emit = defineEmits<{ "update:totalAmount": [value: number] }>();

watch(
  totalAmount,
  (value) => emit("update:totalAmount", value),
  { immediate: true },
);

defineExpose({ setForm, addRow, totalAmount });
</script>

<style scoped>
.denomination-details-row td {
  border-top: none;
  vertical-align: middle;
}
</style>
