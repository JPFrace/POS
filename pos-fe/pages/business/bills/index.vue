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
import Header from "~/features/business/bills/components/header.vue";
import Body from "~/features/business/bills/components/body.vue";
import Other from "~/features/business/bills/components/other.vue";
import type { Bill, BillItem } from "~/types/bill";
import moment from "moment";
import { usePageTitle } from "~/composables/usePageTitle";

usePageTitle();

definePageMeta({
    permission: "Business.Bills.View",
});

const { send, receive, dismiss } = usePageEvent();

const tableRows = 4;
const errors = ref();

const item = ref<Partial<BillItem>>({
    product: undefined,
    rate: 0,
    quantity: 0,
    product_name: "",
    product_description: "",
    active: false,
});

const items: Partial<BillItem>[] = [];

for (let i = 0; i < tableRows; i++) {
    items.push({ ...item.value });
}

const form = ref<Partial<Bill>>({
    date: moment().format("MM/DD/YYYY"), // Set default date
    due_date: null,
    bill_no: null,
    bill_no_auto: true,
    remarks: null,
    term: null,
    attachment: null,
    vendor: undefined,
    vendor_idno: null,
    vendor_name: null,
    vendor_email: null,
    billing_address: null,
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
        form.value.bill_no_auto = true;
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
