<template>
    <div class="space-y-4">
        <div class="flex gap-x-4 items-center justify-between">
            <Checkbox
                v-model="checked"
                label="I purchase this product/service from a vendor"
                size="sm"
            />
        </div>

        <div v-if="checked" ref="purchaseSection">
            <div class="flex-1 flex flex-col">
                <span class="text-sm font-semibold mb-2"
                    >Purchase Description</span
                >
                <Textarea
                    id="purchase_description"
                    v-model="form.purchase_description"
                    placeholder="..."
                    :is-valid="isValid('purchase_description')"
                />
            </div>

            <div class="flex gap-x-4 items-center justify-between mt-4">
                <div class="flex-2 flex flex-col">
                    <span class="text-sm font-semibold mb-2"
                        >Expense Account</span
                    >
                    <Select
                        v-model:data="expenses"
                        v-model:selected="form.expense"
                        url="/api/accounting/chart-accounts"
                        :map-result="mapChartAccounts"
                        :map-query="mapQueryExpense"
                        clearable
                        remote
                        loading
                        placeholder="Select..."
                        :is-valid="isValid('expense')"
                    />
                </div>
                <div class="flex-[1.1] flex flex-col">
                    <span class="text-sm font-semibold mb-2">Cost</span>
                    <Currency
                        v-model="form.cost"
                        placeholder="Enter Cost"
                        :is-valid="isValid('cost')"
                    />
                </div>
            </div>

            <div class="flex gap-x-4 items-center justify-between mt-4">
                <div class="flex-2 flex flex-col">
                    <span class="text-sm font-semibold mb-2"
                        >Payable Account</span
                    >
                    <Select
                        v-model:data="payables"
                        v-model:selected="form.payable"
                        url="/api/accounting/chart-accounts"
                        :map-result="mapChartAccounts"
                        :map-query="mapQueryName"
                        clearable
                        remote
                        loading
                        placeholder="Select..."
                        :is-valid="isValid('payable')"
                    />
                </div>
            </div>
            <div class="flex gap-x-4 items-center justify-between mt-4">
                <div class="flex-1 flex flex-col">
                    <span class="text-sm font-semibold mb-2">Vendor</span>
                    <Select
                        v-model:data="vendors"
                        v-model:selected="form.vendor"
                        url="/api/contacts/vendors"
                        :map-result="mapVendors"
                        :map-query="mapQueryName"
                        clearable
                        remote
                        loading
                        placeholder="Select..."
                        :is-valid="isValid('vendor')"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import type { ChartAccount } from "~/types/chart-account";
import type { Vendors } from "~/types/vendors";
import type { Option } from "~/types/form";

const form = defineModel<Record<string, any>>("form", { required: true });

const props = defineProps<{
    errors?: object;
}>();

const { isValid } = useFormErrors(() => props.errors);

const checked = ref(false);
const purchaseSection = ref<HTMLElement | null>(null);

const expenses = ref<Option[]>([]);
const payables = ref<Option[]>([]);
const vendors = ref<Option[]>([]);

const mapChartAccounts = (res: any) =>
    res.data.map((row: ChartAccount) => ({
        id: row.uuid,
        value: row.uuid,
        label: row.name,
    }));

const mapVendors = (res: any) =>
    res.data.map((row: Vendors) => ({
        id: row.uuid,
        value: row.uuid,
        label: row.full_name,
    }));

const mapQueryExpense = (search: any) => ({
    query: { name: search, category_expense: true },
});

const mapQueryName = (search: any) => ({
    query: { name: search },
});

watch(
    () => [form.value.expense, form.value.purchase_description],
    ([expense, desc]) => {
        checked.value = !!(expense || desc);
    },
    { immediate: true },
);

watch(checked, (val) => {
    form.value.is_purchase = val;
    if (!val) {
        form.value.expense = null;
        form.value.cost = null;
        form.value.payable = null;
        form.value.vendor = null;
        form.value.purchase_description = "";
    }
    nextTick(() => {
        if (val)
            purchaseSection.value?.scrollIntoView({
                behavior: "smooth",
                block: "start",
            });
    });
});
</script>
