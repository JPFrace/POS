<template>
    <div class="space-y-4">
        <div class="flex-1 flex flex-col">
            <span class="text-sm font-semibold mb-2">Income Account</span>
            <Select
                v-model:data="incomes"
                v-model:selected="form.income"
                url="/api/accounting/chart-accounts"
                :map-result="mapChartAccounts"
                :map-query="mapQueryRevenue"
                clearable
                remote
                loading
                placeholder="Select..."
                :is-valid="isValid('income')"
            />
        </div>
        <div class="flex-1 flex flex-col">
            <span class="text-sm font-semibold mb-2">Receivable Account</span>
            <Select
                v-model:data="receivables"
                v-model:selected="form.receivable"
                url="/api/accounting/chart-accounts"
                :map-result="mapChartAccounts"
                :map-query="mapQueryName"
                clearable
                remote
                loading
                placeholder="Select..."
                :is-valid="isValid('receivable')"
            />
        </div>
        <div class="flex-1 flex flex-col">
            <span class="text-sm font-semibold mb-2">Depository</span>
            <Select
                v-model:data="depositories"
                v-model:selected="form.depository"
                url="/api/accounting/chart-accounts"
                :map-result="mapChartAccounts"
                :map-query="mapQueryDepository"
                clearable
                remote
                loading
                placeholder="Select..."
                :is-valid="isValid('depository')"
            />
        </div>
    </div>
</template>

<script lang="ts" setup>
import type { ChartAccount } from "~/types/chart-account";
import type { Option } from "~/types/form";

const form = defineModel<Record<string, any>>("form", { required: true });

const props = defineProps<{
    errors?: object;
}>();

const { isValid } = useFormErrors(() => props.errors);

const incomes = ref<Option[]>([]);
const depositories = ref<Option[]>([]);
const receivables = ref<Option[]>([]);

const mapChartAccounts = (res: any) =>
    res.data.map((row: ChartAccount) => ({
        id: row.uuid,
        value: row.uuid,
        label: row.name,
    }));

const mapQueryRevenue = (search: any) => ({
    query: { name: search, category_revenue: true },
});

const mapQueryDepository = (search: any) => ({
    query: { name: search, depository_only: true },
});

const mapQueryName = (search: any) => ({
    query: { name: search },
});
</script>
