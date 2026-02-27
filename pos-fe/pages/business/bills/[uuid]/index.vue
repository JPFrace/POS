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
import { useRoute } from "vue-router";
import { usePageTitle } from "~/composables/usePageTitle";
import Header from "~/features/business/bills/components/header.vue";
import Body from "~/features/business/bills/components/body.vue";
import Other from "~/features/business/bills/components/other.vue";
import type { Bill, BillItem } from "~/types/bill";
import moment from "moment";
const { send, receive, dismiss } = usePageEvent();

usePageTitle();

definePageMeta({
    permission: "Business.Bills.Edit",
});

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

const refill = () => {
    for (let i = (form.value?.items ?? []).length - 1; i < 3; i++) {
        form.value!.items = (form.value!.items ?? []).concat([
            {
                product: undefined,
                rate: 0,
                quantity: 0,
                product_name: "",
                product_description: "",
                active: false,
            },
        ]);
    }
};

const route = useRoute();
const client = useSanctumClient();
const { data: bills, refresh } = useAsyncData<Bill[]>(
    `${id(route.fullPath)}.bills`,
    () =>
        client("/api/business/bills", {
            method: "GET",
            params: {
                query: {
                    uuid: route.params.uuid,
                    product: true,
                    term: true,
                    details: true,
                    vendor: true,
                    status: true,
                    file: true,
                },
            },
        }),
    {
        server: false,
        lazy: true,
        immediate: true,
    },
);

const fillItem = () => {
    if (!bills.value?.data?.[0]) {
        return;
    }
    const billData = bills.value.data[0] ?? [];
    const { details, vendor, term, ...filtered } = billData;
    form.value = {
        ...filtered,
        attachment: billData.file,
        bill_no_auto: true,
        vendor: billData.vendor
            ? {
            id: billData.vendor.uuid,
            value: billData.vendor.uuid,
            label: billData.vendor.full_name,
            type: billData.vendor.type_label,
            id_no: billData.vendor.id_no,
            email: billData.vendor.email,
            orders: billData.vendor.orders,
        } : null,
        term: billData.term ? {
            id: billData.term.uuid,
            value: billData.term.uuid,
            label: billData.term.name,
        } : null,

    };
    const items: Partial<BillItem>[] = [];
    (billData?.details ?? []).forEach((row: any) => {
        items.push({
            product: {
                ...row.product,
                id: row.product.uuid,
                value: row.product.uuid,
                label: `${row.product.sku}#${row.product.name}`,
                sku: row.product.sku,
                category: row.product.category,
                description: row.product.description,
                name: row.product.name,
                price: row.product.price,
                expense_account: row.product.expense_account,
            },
            rate: row.rate,
            quantity: row.quantity,
            original_quantity: row.original_quantity,
            delivered: row.delivered,
            product_name: row.product_name,
            product_description: row.product_description,
            active: true,
            product_active: false,
        });
    });
    form.value.items = items;
    refill();
};

onBeforeUnmount(() => {
    dismiss("on:create-new");
    dismiss("on:error");
    dismiss("on:new-line");
    dismiss("on:clear-lines");
});

watch(bills, () => {
    fillItem();
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
