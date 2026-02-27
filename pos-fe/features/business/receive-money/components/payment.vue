<template>
  <div class="flex flex-col items-baseline justify-between gap-y-4 w-full">
    <div class="flex gap-x-4 w-full">
      <div class="w-[65%]">
        <label class="form-label">
          Dimension
          <span class="text-xs italic">(Search records)</span>
        </label>
        <div class="w-full">
          <Select
            v-model:data="dimensions"
            v-model:selected="data!.dimension"
            url="/api/accounting/dimensions"
            :mapResult="
              (result: any) =>
                result.data.map((row: Dimensions) => ({
                  id: row.uuid,
                  value: row.uuid,
                  label: row.name,
                }))
            "
            :mapQuery="
              (search: any) => ({
                query: {
                  name: search,
                },
              })
            "
            :is-valid="isValid('dimension')"
            multiple
            clearable
            remote
            loading
          />
        </div>
      </div>
    </div>
    <div class="w-full">
      <label
        class="form-label"
        :class="{ '!text-red-400': isValid('payment_method') == false }"
      >
        Payment Methods
      </label>
      <div class="w-[65%] flex items-center gap-3">
        <Input
          :model-value="denominationsSummary"
          readonly
          solid
          :is-valid="isValid('payment_method')"
          class="flex-1 min-w-0"
          placeholder="Cash: 0.00 / Check: 0.00 / ..."
        />
        <Button
          type="button"
          icon="cheque"
          variant="success"
          label="Open Payments"
          title="Click to open payment denominations"
          @click="openDenominationsModal"
        />
      </div>
      <DenominationComponent
        ref="denominationRef"
        v-model="denominations"
        :show-button="false"
        :errors="denominationErrors"
        :is-posted="isPosted"
      />
    </div>

   
  </div>
</template>

<script lang="ts" setup>
import type { Dimensions } from "~/types/dimensions";
import type { OfficialReceipt, Denomination } from "~/types/official-receipts";
import DenominationComponent from "./denominations/denomination.vue";

const data = defineModel<Partial<OfficialReceipt>>();

const isPosted = computed(
  () => data.value?.status?.name?.toLowerCase() === "posted"
);

interface Props {
  errors: any;
}

const props = defineProps<Props>();
const { send } = usePageEvent();

const dimensions = ref([]);
const denominations = ref<Partial<Denomination[]>>([]);
const denominationErrors = ref<Record<string, { length?: number }>>({});
const denominationRef = ref<InstanceType<typeof DenominationComponent> | null>(
  null,
);

const isValid = (key: string) =>
  props.errors
    ? Object.keys(props.errors).includes(key)
      ? (props.errors as any)[key]?.length <= 0
      : null
    : null;

const onChangeCustomer = (value: any) => {
  data.value!.customer_name = value.label;
  data.value!.customer_email = value.email;
};

const openDenominationsModal = () => {
  denominationRef.value?.openModal();
};

let isSyncingFromData = false;

function syncFromDataToLocal(val: unknown) {
  const next =
    val == null || (Array.isArray(val) && val.length === 0)
      ? []
      : Array.isArray(val) && val.length > 0
        ? JSON.parse(JSON.stringify(val))
        : undefined;
  if (next === undefined) return;
  const shouldSync =
    next.length === 0 ||
    !denominations.value ||
    denominations.value.length === 0;
  if (!shouldSync) return;
  isSyncingFromData = true;
  denominations.value = next;
  nextTick(() => {
    isSyncingFromData = false;
  });
}

watch(
  denominations,
  (val) => {
    if (isSyncingFromData) return;
    if (data.value) (data.value as any).denominations = val ?? [];
  },
  { deep: true }
);

watch(
  () => data.value?.denominations,
  syncFromDataToLocal,
  { deep: true, immediate: true }
);

const denominationsSummary = computed(() => {
  const list = denominations.value ?? [];
  if (!list.length) return "";

  const byMethod = new Map<string, number>();
  for (const d of list) {
    if (!d) continue;
    const label =
      (d.payment_method as { label?: string } | null)?.label ?? "Other";
    const amount = Number(d?.amount) ?? 0;
    byMethod.set(label, (byMethod.get(label) ?? 0) + amount);
  }

  return Array.from(byMethod.entries())
    .map(([method, total]) => `${method}: ${currencyFormat(total, 2)}`)
    .join(" / ");
});
</script>
