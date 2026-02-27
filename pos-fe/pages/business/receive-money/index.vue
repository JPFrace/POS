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
import type {
    OfficialReceipt,
    OfficialReceiptItem,
} from "~/types/official-receipts";
import moment from "moment";
import { usePageTitle } from "~/composables/usePageTitle";

usePageTitle();
definePageMeta({
    permission: "Business.Receive Money.View",
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
    denominations: null,
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
