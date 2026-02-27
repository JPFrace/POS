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
import Header from "~/features/business/make-payments/components/header.vue";
import Body from "~/features/business/make-payments/components/body.vue";
import Other from "~/features/business/make-payments/components/other.vue";
import moment from "moment";
import { usePageTitle } from "~/composables/usePageTitle";
import type { Payment, PaymentItem } from "~/types/payment";

usePageTitle();
definePageMeta({
    permission: "Business.Make Payments.View",
});

const config = useConfig();
const { send, receive, dismiss } = usePageEvent();

const tableRows = 4;
const errors = ref();

const item = ref<Partial<PaymentItem>>({
    uuid: uuid(),
    product: undefined,
    rate: 0,
    tax_rate: 0,
    quantity: 0,
    product_name: "",
    product_description: "",
    sub_contact: undefined,
    active: false,
});

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

onBeforeUnmount(() => {
    dismiss("on:create-new");
    dismiss("on:error");
    dismiss("on:new-line");
    dismiss("on:clear-lines");
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
