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
import Header from "~/features/business/orders/components/header.vue";
import Body from "~/features/business/orders/components/body.vue";
import Other from "~/features/business/orders/components/other.vue";
import type { Order, OrderItem } from "~/types/order";
import moment from "moment";
import { usePageTitle } from "~/composables/usePageTitle";

usePageTitle();
definePageMeta({
    permission: "Business.Purchase Orders.Edit",
});

const { send, receive, dismiss } = usePageEvent();

const tableRows = 4;
const errors = ref();

const item = ref<Partial<OrderItem>>({
    product: undefined,
    rate: 0,
    quantity: 0,
    product_name: "",
    product_description: "",
    active: false,
});

const items: Partial<OrderItem>[] = [];

for (let i = 0; i < tableRows; i++) {
    items.push({ ...item.value });
}

const form = ref<Partial<Order>>({
    date: moment().format("MM/DD/YYYY"), // Set default date
    order_no: null,
    order_no_auto: true,
    remarks: null,
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
const { data: orders, refresh } = useAsyncData<Order[]>(
    `${id(useRoute().fullPath)}.purchase-orders`,
    () =>
        client("/api/business/orders", {
            method: "GET",
            params: {
                query: {
                    uuid: route.params.uuid,
                    details: true,
                    file: true,
                    vendor: true,
                    "details.product.expense": true,
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
    if (!orders.value?.data?.[0]) {
        return;
    }
    const orderData = orders.value.data[0] ?? [];
    const { details, vendor, ...filtered } = orderData;
    form.value = {
        ...filtered,
        attachment: orderData.file,
        vendor_email: orderData.vendor.email,
        vendor: {
            id: orderData.vendor.uuid,
            value: orderData.vendor.uuid,
            label: orderData.vendor.full_name,
        },
        order_no_auto: true
    };
    const items: Partial<OrderItem>[] = [];
    (orderData?.details ?? []).forEach((row: any) => {
        items.push({
            product: {
                ...row.product,
                id: row.product.uuid,
                value: row.product.uuid,
                label: `${row.product.sku}#${row.product.name}`,
            },
            rate: row.rate,
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

watch(orders, () => {
    fillItem();
});

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
        form.value.order_no_auto = true;
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
