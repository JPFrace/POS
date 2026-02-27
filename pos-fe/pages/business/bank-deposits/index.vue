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
import Header from "~/features/business/bank-deposits/components/header.vue";
import Body from "~/features/business/bank-deposits/components/body.vue";
import Other from "~/features/business/bank-deposits/components/other.vue";
import moment from "moment";
import { usePageTitle } from "~/composables/usePageTitle";
import type { Deposit, DepositItem } from "~/types/deposits";

usePageTitle();
definePageMeta({
    permission: "Business.Make Payments.View",
});

const config = useConfig();
const { send, receive, dismiss } = usePageEvent();

const tableRows = 4;
const errors = ref();

const item = ref<Partial<DepositItem>>({
    uuid: uuid(),
    payment_method: undefined,
    rate: 0,
    contact_idno: "",
    memo: "",
});

const items: Partial<DepositItem>[] = [];

for (let i = 0; i < tableRows; i++) {
    items.push({ ...item.value });
}

const form = ref<Partial<Deposit>>({
    date: moment().format("MM/DD/YYYY"), // Set default date
    ref_no: null,
    ref_no_auto: true,
    remarks: null,
    attachment: null,
    cash_in_bank: undefined,
    items,
});

onBeforeUnmount(() => {
    dismiss("on:create-new");
    dismiss("on:error");
});

onMounted(() => {
    receive("on:error", (value: any) => {
        errors.value = value;
    });

    receive("on:create-new", (_value: any) => {
        const currentDate = form.value.date; // Preserve the date
        clearKeyValue(form.value);
        form.value.date = currentDate; // Restore the date after submit
        errors.value = [];
        form.value.ref_no_auto = true;
    });
});
</script>
