<template>
    <div class="flex flex-col items-baseline justify-between gap-y-9">
        <div class="flex gap-x-4 w-full">
            <div class="flex-1">
                <label class="form-label">
                    Dimension
                    <span class="text-xs italic">(Search records)</span>
                </label>
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
            <div class="flex-1">
                <label class="form-label"
                    >Cash in Bank
                    <span class="text-xs italic">(Search records)</span></label
                >
                <Select
                    ref="accounts"
                    column
                    custom-column
                    url="/api/accounting/chart-accounts"
                    v-model:data="cash_in_banks"
                    v-model:selected="data!.cash_in_bank"
                    :mapResult="
                        (result: any) =>
                            result.data.map((row: ChartAccount) => ({
                                id: row.uuid,
                                value: row.uuid,
                                label: `${row.code} - ${row.name}`,
                                type: row.type.name,
                                code: row.code,
                                department: row.department,
                                description: row.description,
                                columns: ['account', 'department', 'type'],
                            }))
                    "
                    :mapQuery="
                        (search: any) => ({
                            query: {
                                name_code: search,
                                category: true,
                                type: true,
                                department: true,
                                'cash_in_bank.undeposited': true,
                            },
                        })
                    "
                    :is-valid="isValid('cash_in_bank')"
                    clearable
                    remote
                    loading
                >
                    <template #customColumn="{ data }">
                        <AccountColumn :data="data" hide-new-product />
                    </template>
                </Select>
            </div>
        </div>
        <div>
            <label
                class="form-label"
                :class="{
                    '!text-red-400': isValid('payment_method') == false,
                }"
                >Payment Methods
            </label>
            <div class="flex gap-x-4 items-center justify-start">
                <div
                    v-for="method in (paymentMethods as any)?.data ?? []"
                    class="flex gap-x-2 items-center justify-start"
                >
                    <Radio
                        type="radio"
                        variant="success"
                        name="payment_method"
                        solid
                        :label="method.name"
                        :id="method.uuid"
                        :value="method"
                        v-model="data!.payment_method"
                        @change="onChange(method)"
                    >
                        <template #label="{ id, label }">
                            <div
                                class="flex gap-x-2 items-center justify-start ml-2"
                            >
                                <KTIcon
                                    :icon-name="method.icon ?? 'graph-2'"
                                    icon-class="fs-2x"
                                />
                                <label class="form-check-label" :for="id">{{
                                    label
                                }}</label>
                            </div>
                        </template>
                    </Radio>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import type { ChartAccount } from "~/types/chart-account";
import AccountColumn from "./product-column.vue";
import type { Payment } from "~/types/payment";
import type { PaymentMethod } from "~/types/payment-method";

interface Props {
    errors: any;
}
const props = defineProps<Props>();

const config = useConfig();
const { send } = usePageEvent();
const data = defineModel<Partial<Payment>>();

const cash_in_banks = defineModel("cash_in_banks");
const dimensions = ref([]);

const defaultPaymentMethod = ref();
const defaultCashInBank = ref();

const isValid = (key: string) =>
    props.errors
        ? Object.keys(props.errors).includes(key)
            ? (props.errors as any)[key]?.length <= 0
            : null
        : null;

const page = ref(1);
const size = ref(10);
const search = ref();
const sizes = ref([10, 30, 50, 100]);

const client = useSanctumClient();

const {
    data: paymentMethods,
    refresh,
    status,
} = useAsyncData(
    "payment_methods",
    () =>
        client("/api/setup/payment-types", {
            method: "GET",
            params: {
                query: { ...search.value },
                page: page.value,
                size: size.value,
            },
        }),
    {
        server: false,
        lazy: true,
        watch: [page, search, size],
    }
);

const onChange = (method: any) => {
    send("payment-method-changed", method);
};

watch(paymentMethods, (value: any) => {
    if (!data.value?.payment_method) {
        data.value!.payment_method = (value?.data ?? []).filter(
            (f: PaymentMethod) => f.uuid == defaultPaymentMethod.value?.value
        )[0];
    }
});

onMounted(() => {
    defaultPaymentMethod.value = config.get(
        "business_make_payments_default_payment_method"
    );

    defaultCashInBank.value = config.get(
        "business_make_payments_default_cash_in_bank"
    );

    const { value, label, code } = defaultCashInBank.value?.options[0] ?? {};

    if (value) {
        data.value.cash_in_bank = {
            id: value,
            value,
            label,
        };
    }
});
</script>
