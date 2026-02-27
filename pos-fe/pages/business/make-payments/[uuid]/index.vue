<template>
    <div>
        <NuxtLayout>
            <div class="flex flex-col gap-y-8">
                <Header
                    v-model="form"
                    v-model:contacts="contacts"
                    v-model:cash_in_banks="cash_in_banks"
                    :errors="errors"
                />
                <Body v-model="form" :errors="errors" />
                <Other v-model="form" :errors="errors" />
            </div>
        </NuxtLayout>
    </div>
</template>

<script lang="ts" setup>
import Header from "~/features/business/make-payments/components/header.vue";
import Body from "~/features/business/make-payments/components/body.vue";
import Other from "~/features/business/make-payments/components/other.vue";
import moment from "moment";

import type { Payment, PaymentItem } from "~/types/payment";
import type { Option } from "~/types/form";
import { usePageTitle } from "~/composables/usePageTitle";

usePageTitle();
definePageMeta({
    permission: "Business.Make Payments.Edit",
});

const { send, receive, dismiss } = usePageEvent();

const tableRows = 4;
const errors = ref();

const item = ref<Partial<PaymentItem>>({
    product: undefined,
    rate: 0,
    tax_rate: 0,
    quantity: 0,
    product_name: "",
    product_description: "",
    sub_contact: undefined,
    active: false,
    product_active: false,
    sub_total: 0,
    price: 0,
});

const contacts = ref<Option[]>([]);
const cash_in_banks = ref<Option[]>([]);

const items: Partial<PaymentItem>[] = [];

for (let i = 0; i < tableRows; i++) {
    items.push({ ...item.value });
}

const form = ref<Partial<Payment>>({
    date: moment().format("MM/DD/YYYY"), // Set default date
    ref_no: null,
    check_no: null,
    ref_no_auto: true,
    check_no_auto: true,
    remarks: null,
    attachment: null,
    contact: undefined,
    payee_idno: null,
    payee_name: null,
    payee_email: null,
    payee_address: null,
    payment_method: undefined,
    cash_in_bank: undefined,
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
                tax_rate: 0,
                quantity: 0,
                product_name: "",
                product_description: "",
                sub_contact: undefined,
                active: false,
            },
        ]);
    }
};

const route = useRoute();
const client = useSanctumClient();
const { data: payments, refresh } = useAsyncData<Payment[]>(
    `${id(route.fullPath)}.payments-made`,
    () =>
        client("/api/business/payments", {
            method: "GET",
            params: {
                query: {
                    uuid: route.params.uuid,
                    payee: true,
                    payment_method: true,
                    status: true,
                    file: true,
                    "details.product.expense": true,
                    "details.product.payable": true,
                    "details.contact": true,
                    "transdim.dimension": true,
                    "cash_in_bank.type": true,
                },
            },
        }),
    {
        server: false,
        lazy: true,
        immediate: true,
    }
);

const fillItem = () => {
    if (!payments.value?.data?.[0]) {
        return;
    }
    const paymentData = payments.value.data[0] ?? [];
    const { details, payee, cash_in_bank, ...filtered } = paymentData;
    form.value = {
        ...filtered,
        ref_no_auto: true,
        check_no_auto: true,
        payee_address: paymentData.payee_address,
        payment_method: paymentData.payment_method,
        attachment: paymentData.file,
        payee_email: paymentData.payee_email,
        contact: {
            id: paymentData.payee.uuid,
            value: paymentData.payee.uuid,
            label: paymentData.payee.full_name,
        },
        cash_in_bank: {
            id: paymentData.cash_in_bank.uuid,
            value: paymentData.cash_in_bank.uuid,
            label: `${paymentData.cash_in_bank.code} - ${paymentData.cash_in_bank.name}`,
        },
    };
    const items: Partial<PaymentItem>[] = [];
    (paymentData?.details ?? []).forEach((row: any) => {
        items.push({
            product: {
                ...row.product,
                id: row.product.uuid,
                value: row.product.uuid,
                label: `${row.product.sku}#${row.product.name}`,
            },
            rate: row.rate,
            withholding_tax_rate: row.withholding_tax_rate,
            quantity: row.quantity,
            product_name: row.product_name,
            product_description: row.product_description,
            sub_contact: row.sub_contact
                ? {
                      id: row.sub_contact.uuid,
                      value: row.sub_contact.uuid,
                      label: row.sub_contact.full_name,
                      type: row.sub_contact.type_label,
                      id_no: row.sub_contact.id_no,
                  }
                : undefined,
            active: true,
            product_active: false,
            sub_contact_active: false,
        });
    });
    form.value.items = items;

    const dimensions: Option[] = [];
    (paymentData?.transaction_dimensions ?? []).forEach((row: any) => {
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

watch(payments, () => {
    fillItem();
});

onMounted(() => {
    receive("on:create-new", (_value: any) => {
        const currentDate = form.value.date; // Preserve the date
        clearKeyValue(form.value);
        form.value.date = currentDate; // Restore the date after submit
        errors.value = [];
        fill();
        form.value.ref_no_auto = true;
        form.value.check_no_auto = true;
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
