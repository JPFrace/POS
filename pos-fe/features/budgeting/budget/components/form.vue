<template>
  <form class="space-y-4 py-1 px-1">
    <div class="flex-1 flex flex-col">
      <span class="text-sm font-semibold mb-2"> {{ form.period_1.date }}</span>
      <Currency class="text-center" v-model="form.period_1.amount" placeholder="Enter Period 1 Amount"
        :is-valid="isValid('period_1')" />
    </div>
    <div class="flex-1 flex flex-col">
      <span class="text-sm font-semibold mb-2"> {{ form.period_2.date }}</span>
      <Currency class="text-center" v-model="form.period_2.amount" placeholder="Enter Period 2 Amount"
        :is-valid="isValid('period_2')" />
    </div>
    <div class="flex-1 flex flex-col">
      <span class="text-sm font-semibold mb-2"> {{ form.period_3.date }}</span>
      <Currency class="text-center" v-model="form.period_3.amount" placeholder="Enter Period 3 Amount"
        :is-valid="isValid('period_3')" />
    </div>
    <div class="flex-1 flex flex-col">
      <span class="text-sm font-semibold mb-2"> {{ form.period_4.date }}</span>
      <Currency class="text-center" v-model="form.period_4.amount" placeholder="Enter Period 4 Amount"
        :is-valid="isValid('period_4')" />
    </div>
    <div class="flex-1 flex flex-col">
      <span class="text-sm font-semibold mb-2"> {{ form.period_5.date }}</span>
      <Currency class="text-center" v-model="form.period_5.amount" placeholder="Enter Period 5 Amount"
        :is-valid="isValid('period_5')" />
    </div>
    <div class="flex-1 flex flex-col">
      <span class="text-sm font-semibold mb-2"> {{ form.period_6.date }}</span>
      <Currency class="text-center" v-model="form.period_6.amount" placeholder="Enter Period 6 Amount"
        :is-valid="isValid('period_6')" />
    </div>
    <div class="flex-1 flex flex-col">
      <span class="text-sm font-semibold mb-2"> {{ form.period_7.date }}</span>
      <Currency class="text-center" v-model="form.period_7.amount" placeholder="Enter Period 7 Amount"
        :is-valid="isValid('period_7')" />
    </div>
    <div class="flex-1 flex flex-col">
      <span class="text-sm font-semibold mb-2"> {{ form.period_8.date }}</span>
      <Currency class="text-center" v-model="form.period_8.amount" placeholder="Enter Period 8 Amount"
        :is-valid="isValid('period_8')" />
    </div>
    <div class="flex-1 flex flex-col">
      <span class="text-sm font-semibold mb-2"> {{ form.period_9.date }}</span>
      <Currency class="text-center" v-model="form.period_9.amount" placeholder="Enter Period 9 Amount"
        :is-valid="isValid('period_9')" />
    </div>
    <div class="flex-1 flex flex-col">
      <span class="text-sm font-semibold mb-2"> {{ form.period_10.date }}</span>
      <Currency class="text-center" v-model="form.period_10.amount" placeholder="Enter Period 10 Amount"
        :is-valid="isValid('period_10')" />
    </div>
    <div class="flex-1 flex flex-col">
      <span class="text-sm font-semibold mb-2"> {{ form.period_11?.date }}</span>
      <Currency class="text-center" v-model="form.period_11.amount" placeholder="Enter Period 11 Amount"
        :is-valid="isValid('period_11')" />
    </div>
    <div class="flex-1 flex flex-col">
      <span class="text-sm font-semibold mb-2"> {{ form.period_12.date }}</span>
      <Currency class="text-center" v-model="form.period_12.amount" placeholder="Enter Period 12 Amount"
        :is-valid="isValid('period_12')" />
    </div>
    <div class="flex-1 flex flex-col">
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <!-- Budget Total -->
        <div class="flex flex-col">
          <span class="text-sm font-semibold mb-2">Budget Total</span>
          <div class="bg-white border rounded-lg px-4 py-3 shadow-sm">
            <span class="text-base font-bold text-center">
              {{ money(form.total, 2) }}
            </span>
          </div>
        </div>

        <!-- Periods Total -->
        <div class="flex flex-col">
          <span class="text-sm font-semibold mb-2">Periods Total</span>
          <div class="bg-white border rounded-lg px-4 py-3 shadow-sm">
            <span class="text-base font-bold text-center">
              {{ money(total(), 2) }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </form>
</template>

<script lang="ts" setup>
import type { BudgetPeriod } from "~/types/budget";

const { yup } = useYup();

interface Props {
  errors?: object;
  schema: Function;
  form: Function;
  data?: BudgetPeriod;
}

const props = defineProps<Props>();

const emptyPeriod = { date: "", amount: 0 };

const form = ref<BudgetPeriod>({
  uuid: "",
  period_1: { ...emptyPeriod },
  period_2: { ...emptyPeriod },
  period_3: { ...emptyPeriod },
  period_4: { ...emptyPeriod },
  period_5: { ...emptyPeriod },
  period_6: { ...emptyPeriod },
  period_7: { ...emptyPeriod },
  period_8: { ...emptyPeriod },
  period_9: { ...emptyPeriod },
  period_10: { ...emptyPeriod },
  period_11: { ...emptyPeriod },
  period_12: { ...emptyPeriod },
  total: 0,
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
    uuid: value.uuid ?? null,
    period_1: value.period_1 ?? emptyPeriod,
    period_2: value.period_2 ?? emptyPeriod,
    period_3: value.period_3 ?? emptyPeriod,
    period_4: value.period_4 ?? emptyPeriod,
    period_5: value.period_5 ?? emptyPeriod,
    period_6: value.period_6 ?? emptyPeriod,
    period_7: value.period_7 ?? emptyPeriod,
    period_8: value.period_8 ?? emptyPeriod,
    period_9: value.period_9 ?? emptyPeriod,
    period_10: value.period_10 ?? emptyPeriod,
    period_11: value.period_11 ?? emptyPeriod,
    period_12: value.period_12 ?? emptyPeriod,
    total: value.total ?? 0,
  }
};

const total = () => {
  return Array.from({ length: 12 }, (_, i) =>
    numberOnly(form.value[`period_${i + 1}`]?.amount)
  ).reduce((sum, value) => sum + value, 0);
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
      period_1: Yup.object().required().shape({
        date: Yup.string().notRequired(),
        amount: Yup.number()
          .transform((_, value) =>
            Number(String(value).replace(/,/g, ''))
          )
          .min(1, 'Period 1 amount must be a positive number')
          .required('Period 1 amount is required'),
      }),
      period_2: Yup.object().required().shape({
        date: Yup.string().notRequired(),
        amount: Yup.number()
          .transform((_, value) =>
            Number(String(value).replace(/,/g, ''))
          )
          .min(1, 'Period 2 amount must be a positive number')
          .required('Period 2 amount is required'),
      }),
      period_3: Yup.object().required().shape({
        date: Yup.string().notRequired(),
        amount: Yup.number()
          .transform((_, value) =>
            Number(String(value).replace(/,/g, ''))
          )
          .min(1, 'Period 3 amount must be a positive number')
          .required('Period 3 amount is required'),
      }),
      period_4: Yup.object().required().shape({
        date: Yup.string().notRequired(),
        amount: Yup.number()
          .transform((_, value) =>
            Number(String(value).replace(/,/g, ''))
          )
          .min(1, 'Period 4 amount must be a positive number')
          .required('Period 4 amount is required'),
      }),
      period_5: Yup.object().required().shape({
        date: Yup.string().notRequired(),
        amount: Yup.number()
          .transform((_, value) =>
            Number(String(value).replace(/,/g, ''))
          )
          .min(1, 'Period 5 amount must be a positive number')
          .required('Period 5 amount is required'),
      }),
      period_6: Yup.object().required().shape({
        date: Yup.string().notRequired(),
        amount: Yup.number()
          .transform((_, value) =>
            Number(String(value).replace(/,/g, ''))
          )
          .min(1, 'Period 6 amount must be a positive number')
          .required('Period 6 amount is required'),
      }),
      period_7: Yup.object().required().shape({
        date: Yup.string().notRequired(),
        amount: Yup.number()
          .transform((_, value) =>
            Number(String(value).replace(/,/g, ''))
          )
          .min(1, 'Period 7 amount must be a positive number')
          .required('Period 7 amount is required'),
      }),
      period_8: Yup.object().required().shape({
        date: Yup.string().notRequired(),
        amount: Yup.number()
          .transform((_, value) =>
            Number(String(value).replace(/,/g, ''))
          )
          .min(1, 'Period 8 amount must be a positive number')
          .required('Period 8 amount is required'),
      }),
      period_9: Yup.object().required().shape({
        date: Yup.string().notRequired(),
        amount: Yup.number()
          .transform((_, value) =>
            Number(String(value).replace(/,/g, ''))
          )
          .min(1, 'Period 9 amount must be a positive number')
          .required('Period 9 amount is required'),
      }),
      period_10: Yup.object().required().shape({
        date: Yup.string().notRequired(),
        amount: Yup.number()
          .transform((_, value) =>
            Number(String(value).replace(/,/g, ''))
          )
          .min(1, 'Period 10 amount must be a positive number')
          .required('Period 10 amount is required'),
      }),
      period_11: Yup.object().required().shape({
        date: Yup.string().notRequired(),
        amount: Yup.number()
          .transform((_, value) =>
            Number(String(value).replace(/,/g, ''))
          )
          .min(1, 'Period 11 amount must be a positive number')
          .required('Period 11 amount is required'),
      }),
      period_12: Yup.object().required().shape({
        date: Yup.string().notRequired(),
        amount: Yup.number()
          .transform((_, value) =>
            Number(String(value).replace(/,/g, ''))
          )
          .min(1, 'Period 12 amount must be a positive number')
          .required('Period 12 amount is required'),
      }),
    })
  );
});

</script>
