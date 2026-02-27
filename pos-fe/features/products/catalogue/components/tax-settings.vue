<template>
    <form class="space-y-4 py-1 px-1">
        <div class="flex gap-4 flex-col items-center justify-between w-full">
            <div class="flex-2 flex flex-col w-full">
                <span class="text-sm font-semibold mb-2">Sales Tax</span>
                <Select
                    v-model:data="salesTaxes"
                    v-model:selected="form.sales_tax"
                    url="/api/setup/taxes"
                    :map-result="mapTaxes"
                    :map-query="mapQuerySalesTax"
                    clearable
                    remote
                    loading
                    placeholder="Select..."
                    :is-valid="isValid('sales_tax')"
                />
            </div>
            <div class="flex-2 flex flex-col w-full">
                <span class="text-sm font-semibold mb-2">Witholding Tax</span>
                <Select
                    v-model:data="withholdingTaxes"
                    v-model:selected="form.withholding_tax"
                    url="/api/setup/taxes"
                    :map-result="mapTaxes"
                    :map-query="mapQueryWithholdingTax"
                    clearable
                    remote
                    loading
                    placeholder="Select..."
                    :is-valid="isValid('withholding_tax')"
                />
            </div>
        </div>
    </form>
</template>

<script lang="ts" setup>
import type { Option } from "~/types/form";
import type { Tax } from "~/types/Tax";

const form = defineModel<Record<string, any>>("form", { required: true });

const props = defineProps<{
    errors?: object;
}>();

const { isValid } = useFormErrors(() => props.errors);

const salesTaxes = ref<Option[]>([]);
const withholdingTaxes = ref<Option[]>([]);

const mapTaxes = (res: any) =>
    res.data.map((row: Tax) => ({
        id: row.uuid,
        value: row.uuid,
        label: `${row.rate}% ${row.code}`,
    }));

const mapQuerySalesTax = (search: any) => ({
    query: { name: search, type: "VAT" },
});

const mapQueryWithholdingTax = (search: any) => ({
    query: { name: search, type: "Withholding" },
});
</script>
