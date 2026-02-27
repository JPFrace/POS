<template>
    <div class="flex flex-col items-baseline justify-between gap-y-9">
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
                        :value="method.uuid"
                        v-model="data!.payment_method"
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
import type { Invoice } from "~/types/invoice";

const data = defineModel<Partial<Invoice>>();

interface Props {
    errors: any;
}

const props = defineProps<Props>();

const deposits = ref([]);

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

watch(paymentMethods, (value) => {
    console.log(value);
});
</script>
