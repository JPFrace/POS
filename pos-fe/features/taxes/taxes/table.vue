<template>
    <AppPageTable
        endpoint="/api/taxes/tax"
        :params="{
            query: {
                class: true,
                chartAccount: true,
                taxAgency: true,
                tax_agency_id: selectedTaxAgency?.value?.id,
                children: true,
                children_class: true,
                children_taxAgency: true,
                children_chartAccount: true,
                children_parent: true,
                parent_only: true,
            },
        }"
    >
        <template #columns>
            <!-- <el-table-column type="selection" width="55" /> -->

            <el-table-column prop="description" label="Tax" width="150">
                <template #default="scope">
                    <div class="flex flex-col gap-y-1">
                        <span class="font-bold">
                            {{ scope.row.code }} -
                            {{ scope.row.name }}
                        </span>
                        <span class="text-slate-400 text-xs italic">
                            {{ scope.row.description }}
                        </span>
                    </div>
                </template>
            </el-table-column>

            <el-table-column prop="rate_type" label="Rate">
                <template #default="scope">
                    <div class="flex flex-col gap-y-1">
                        <span>
                            {{ scope.row.rate }}
                            {{ scope.row.rate_type }}
                        </span>
                    </div>
                </template>
            </el-table-column>

            <el-table-column prop="type" label="Tax Type">
                <template #default="scope">
                    <div class="flex flex-col gap-y-1">
                        <span class="font-bold">
                            {{ scope.row?.type_obj?.label.toUpperCase() }}
                        </span>
                    </div>
                </template>
            </el-table-column>

            <el-table-column label="Actions" width="100" class-name="!static">
                <template #default="scope">
                    <div class="space-x-standard justify-end">
                        <AppPageDelete
                            v-if="!scope.row?.children?.length"
                            endpoint="/api/taxes/tax"
                            :uuid="scope.row.uuid"
                            :title="scope.row.name"
                        />
                        <EditTax
                            :id="scope.row.uuid"
                            endpoint="/api/taxes/tax"
                            :uuid="scope.row.uuid"
                            width="60%"
                            width-lg="30%"
                            :data="scope.row"
                            @registerEditSubmit="onRegisterEditSubmit"
                        >
                        </EditTax>
                    </div>
                </template>
            </el-table-column>
        </template>
    </AppPageTable>
</template>

<script setup lang="tsx">
const form = ref<any>({}); // reactive shared form
const schema = ref<any>({});
const dataRef = ref<any>({});
const selectedTaxAgency = ref<Option | null>(null);
const emit = defineEmits<{
    (e: "registerEditSubmit", fn: () => void): void;
}>();
const { $bus } = useNuxtApp();

const onRegisterEditSubmit = (fn: () => void) => {
    emit("registerEditSubmit", fn);
};

$bus.on("tax:agencySelect", (row: TaxesAgency) => {
    selectedTaxAgency.value = row;
});

import axios from "axios";
import type { Option } from "element-plus/es/components/segmented/src/types.mjs";
import { ScrollComponent } from "~/assets/ts/components";
import EditTax from "~/features/taxes/actions/edit.tax.vue";
import TaxFilter from "~/features/taxes/components/tax-filter.vue";

import type { TaxesAgency } from "~/types/taxes-agency";
</script>
