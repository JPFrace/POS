<template>
  <div class="flex flex-col gap-y-4">
    <div class="flex justify-start" v-if="showButton">
      <Button
        variant="secondary"
        size="sm"
        label="Open Payment Denominations"
        class="!uppercase text-sm"
        @click="openModal"
      />
    </div>
      <ModalDialog
        v-model:open="modalOpen"
        title="Payment Denominations"
        width="min(95vw, 1100px)"
        class="denominations-modal"
      >
        <template #body>
          <Form
            ref="formRef"
            :errors="errors"
            :is-valid="isValid"
            :form="setFormValue"
            :schema="(s: unknown) => (schema = s as any)"
            :data="data as any"
            :default-deposit="defaultDeposit"
            :read-only="isPosted"
            @update:total-amount="totalAmount = $event"
          />
          <div
            class="flex flex-wrap items-center justify-between gap-3 mt-3 pt-3 border-t border-gray-200"
          >
            <Button
              v-if="!isPosted"
              type="button"
              variant="light"
              size="sm"
              label="Add Payment Row"
              icon="plus"
              @click="formRef?.addRow()"
            />
            <div class="ml-auto flex flex-col items-end">
              <Input
                :model-value="currencyFormat(totalAmount, 2)"
                readonly
                class="font-semibold text-base text-gray-800 text-right w-[200px]"
              />
            </div>
          </div>
        </template>
        <template #footer>
          <div class="flex w-full flex-wrap items-center justify-end gap-3">
            <div class="flex gap-2">
              <Button
                variant="light"
                class="font-semibold"
                icon="black-left"
                @click="modalOpen = false"
              >
                <span>Cancel</span>
              </Button>
              <Button
                v-if="!isPosted"
                variant="primary"
                class="font-semibold"
                icon="add-folder"
                :disabled="processing"
                @click="apply"
              >
                <span>Confirm Payment</span>
              </Button>
            </div>
          </div>
        </template>
      </ModalDialog>
  </div>
</template>

<script setup lang="ts">
import type { Denomination } from "~/types/official-receipts";
import Form from "./form.vue";
import ModalDialog from "~/components/modal/dialog.vue";
import isEqual from "lodash/isEqual";
import type { Option } from "~/types/form";
import { currencyFormat } from "~/utils/helper";

const data = defineModel<Partial<Denomination[]>>();

interface Props {
  showButton?: boolean;
  errors?: Record<string, { length?: number }>;
  isPosted?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  showButton: true,
  errors: () => ({}),
  isPosted: false,
});

const modalOpen = ref(false);
const formRef = ref<InstanceType<typeof Form> | null>(null);
const formValue = ref<Partial<Denomination>[]>([]);
const totalAmount = ref(0);
const schema = ref<any>(null);
const internalErrors = ref<Record<string, { length?: number }>>({});
const errors = computed(() => ({ ...props.errors, ...internalErrors.value }));
const processing = ref(false);
const config = useConfig();
const router = useRoute();
const defaultDeposit = ref<Option>();

const isValid = (index: number, key: string) => {
  const errs = errors.value;
  const bracketKey = `denominations[${index}].${key}`;
  const dotKey = `denominations.${index}.${key}`;
  const hasError =
    (errs as Record<string, unknown>)[bracketKey] != null ||
    (errs as Record<string, unknown>)[dotKey] != null;
  return hasError ? false : null;
};

const { $message } = useNuxtApp();
const { t } = useI18n();
const { validate } = useYup();

const openModal = () => {
  modalOpen.value = true;
};

const setFormValue = (
  value: Partial<Denomination>[] | Ref<Partial<Denomination>[]>,
) => {
  formValue.value = (value as Ref<Partial<Denomination>[]>)?.value ?? value;
};

const onBeforeShow = () => {
  formRef.value?.setForm(
    (data.value ?? null) as Partial<Denomination>[] | null,
  );
  internalErrors.value = {};
};

watch(modalOpen, (isOpen) => {
  if (isOpen) onBeforeShow();
});

const apply = async () => {
  try {
    if (processing.value) return;
    processing.value = true;
    internalErrors.value = {};

    const payload = { denominations: formValue.value };
    await validate(schema.value ?? {}, { data: payload });

    const cloned = JSON.parse(JSON.stringify(formValue.value));
    if (isEqual(cloned, data.value)) {
      $message("info", t("action.no_changes"));
      modalOpen.value = false;
      processing.value = false;
      return;
    }

    data.value = cloned as any;
    modalOpen.value = false;
  } catch (err: any) {
    internalErrors.value = err?.errors ?? {};
    $message("error", err?.message ?? t("error.failed_request"));
  } finally {
    processing.value = false;
  }
};

defineExpose({
  openModal,
});

onMounted(() => {
  if (!router.params?.uuid) {
    
    const defaultCashInBank = config.get("business_receive_default_cash_in_bank");

    const { id, value, label } = defaultCashInBank?.options?.[0] as Option ?? {};

    if (value != null) {
      defaultDeposit.value = {
        id: typeof id === "number" ? id : null,
        value: value,
        label: label,
      };
    }
   }
});
</script>
