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
import Header from "~/features/business/invoice/components/header.vue";
import Body from "~/features/business/invoice/components/body.vue";
import Other from "~/features/business/invoice/components/other.vue";
import type { Invoice, InvoiceItem } from "~/types/invoice";
import moment from "moment";
import { usePageTitle } from "~/composables/usePageTitle";

usePageTitle();
definePageMeta({
    permission: "Business.Invoice.Edit",
});

const { send, receive, dismiss } = usePageEvent();

const tableRows = 4;
const errors = ref();

const item = ref<Partial<InvoiceItem>>({
    product: undefined,
    rate: 0,
    tax_rate: 0,
    quantity: 0,
    product_name: "",
    product_description: "",
    active: false,
});

const items: Partial<InvoiceItem>[] = [];

for (let i = 0; i < tableRows; i++) {
    items.push({ ...item.value });
}

const form = ref<Partial<Invoice>>({
    date: moment().format("MM/DD/YYYY"), // Set default date
    due_date: null,
    invoice_no: null,
    invoice_no_auto: true,
    remarks: null,
    attachment: null,
    customer: undefined,
    customer_idno: null,
    customer_name: null,
    customer_email: null,
    billing_address: null,
    payment_method: undefined,
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
                tax_rate: 0,
                product_name: "",
                product_description: "",
                active: false,
            },
        ]);
    }
};

const route = useRoute();
const client = useSanctumClient();
const { data: invoiceData, refresh } = useAsyncData<Invoice[]>(
    `${id(route.fullPath)}.invoices`,
    () =>
        client("/api/business/invoices", {
            method: "GET",
            params: {
                query: {
                    uuid: route.params.uuid,
                    details: true,
                    customer: true,
                    file: true,
                    status: true,
                    payment_method: true,
                    product: true,
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
    if (!invoiceData.value?.data?.[0]) {
        return;
    }
    const invData = invoiceData.value.data[0] ?? [];
    const { details, customer, ...filtered } = invData;
    form.value = {
        ...filtered,
        payment_method: invData.payment_method.uuid,
        attachment: invData.file,
        customer: {
            id: invData.customer.uuid,
            value: invData.customer.uuid,
            label: invData.customer.full_name,
            type: invData.customer.type_label,
            id_no: invData.customer.id_no,
            email: invData.customer.email,
        },
        invoice_no_auto: true,
    };
    const items: Partial<InvoiceItem>[] = [];
    (invData?.details ?? []).forEach((row: any) => {
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
                income_account: row.product.income_account,
            },
            rate: row.rate,
            tax_rate: row.tax_rate,
            quantity: row.quantity,
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

watch(invoiceData, () => {
    fillItem();
});

onMounted(() => {
    receive("on:create-new", (_value: any) => {
        const currentDate = form.value.date; // Preserve the date
        clearKeyValue(form.value);
        form.value.date = currentDate; // Restore the date after submit
        errors.value = [];
        fill();
        form.value.invoice_no_auto = true;
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
