<template>
    <div>
        <NuxtLayout>
            <template #toolbarRight>
                <FilterDaterange v-model="dates" />
            </template>
            <template #toolbarLeft>
                <AppPageSearch
                    query="name"
                    no-options
                    placeholder="Search by Customer No."
                />
                <AppPageFilter>
                    <template #options>
                        <div class="flex flex-col mt-4">
                            <label class="form-label">Transaction Type</label>
                            <Select />
                        </div>
                        <div class="flex flex-col">
                            <label class="form-label">Uploaded Date</label>
                            <Select />
                        </div>
                    </template>
                </AppPageFilter>
            </template>
            <div>
                <TableTransactions />
            </div>
        </NuxtLayout>
    </div>
</template>

<script lang="ts" setup>
import TableTransactions from "~/features/collections/transactions/table.vue";
import FilterDaterange from "~/features/reports/filter-daterange.vue";
import { usePageTitle } from "~/composables/usePageTitle";

usePageTitle();
definePageMeta({
    permission: "PRIISMS Collections.Transactions.View",
});

const dates = defineModel();
</script>
