<template>
    <div class="flex items-end gap-2 -mt-2 w-full">
        <div class="w-64 bg-white shadow rounded-xl p-4 flex flex-col items-center">
            <h4 class="text-sm font-semibold text-gray-700 mb-2">Total Budget</h4>
            <div class="text-xl font-bold text-green-600">
                {{ money(amounts, 2) }}
            </div>
        </div>

        <div class="flex gap-4 ml-auto w-[40%]">
            <div class="flex-1 flex flex-col">
                <label class="mb-1">Search</label>
                <Input v-model="searchTerm" placeholder="Search accounts..." clearable />
            </div>

            <div class="flex-1 flex flex-col">
                <label class="mb-1">Filter by Account Categories</label>
                <Select url="/api/accounting/account-categories" v-model:data="categories" v-model:selected="category"
                    :mapResult="(result: any) =>
                        result.data.map((row: any) => ({
                            id: row.uuid,
                            value: row.uuid,
                            label: row.name,
                        }))
                        " :mapQuery="(search: any) => ({ query: { name: search } })" clearable remote loading multiple
                    placeholder="Account categories..." />
            </div>
        </div>
    </div>

    <Table :data-source="filteredItems">
        <template #columns>
            <el-table-column width="35">
                <KTIcon icon-name="abstract-30" icon-class="fs-2 cursor-pointer" />
            </el-table-column>
            <el-table-column prop="account" label="Account" :sortable="true">
                <template #default="scope">
                    <div class="flex flex-col">
                        <span class="text-blue-500 font-bold">{{
                            scope.row.account ? scope.row.account.name : ""
                        }}</span>
                        <span class="text-sm font-bold">
                            {{ scope.row.account ? scope.row.account.code : "" }}
                        </span>
                    </div>
                </template>
            </el-table-column>
            <el-table-column prop="category.name" label="Category" :sortable="true" />
            <el-table-column prop="amount" label="Total" width="200" :sortable="true">
                <template #default="scope">
                    <Currency class="text-center" v-model="scope.row.amount" @change="scope.row.isBudgeted = false" />
                </template>
            </el-table-column>
            <el-table-column prop="description" label="Description">
                <template #default="scope">
                    <Input v-model="scope.row.description" />
                </template>
            </el-table-column>
            <el-table-column prop="running_balance" label="Running Balance">
                <template #default="scope">
                    <div class="text-center">
                        {{ scope.row.amount ? money(scope.row.amount, 2) : "0.00" }}
                    </div>
                </template>
            </el-table-column>
            <el-table-column label="Actions" width="150" class-name="!static">
                <template #default="scope">
                    <div class="space-x-standard flex justify-end">
                        <KTIcon v-if="scope.row.amount && !data?.isPosted" icon-name="abstract-11" icon-class="fs-2x cursor-pointer"
                            @click="scope.row.amount = null" title="Reset Amount" />
                        <AppPageEdit v-if="scope.row.amount && scope.row.isBudgeted" :id="scope.row.periods?.uuid"
                            :endpoint="`/api/budgeting/budget-period/${scope.row.periods?.uuid}`" width="30%"
                            width-lg="30%">
                            <template #form="{ errors, form, schema }">
                                <Form :key="scope.row.periods?.uuid" :errors="errors" :form="form" :schema="schema" :data="{
                                    ...scope.row.periods
                                }" />
                            </template>
                            <template #drawerFooter="{ submit, cancel }">
                                <Button variant="light" class="btn btn-light ms-auto fw-semibold" icon="black-left"
                                    @click="cancel()">
                                    <span>{{ data?.isPosted ? 'Close' : 'Cancel' }}</span>
                                </Button>
                                <Button v-if="!data?.isPosted" variant="primary" class="btn btn-primary fw-semibold" icon="add-folder"
                                    @click="submit">
                                    <span>Submit</span>
                                </Button>
                            </template>
                        </AppPageEdit>
                        <KTIcon v-else title="Edit Record" icon-name="notepad-edit"
                            icon-class="!text-3xl cursor-not-allowed !text-gray-400"
                            icon-type="outline" />
                    </div>
                </template>
            </el-table-column>
        </template>
    </Table>
</template>

<script setup lang="ts">
import type { Budget, BudgetItem } from "~/types/budget";

import Table from "~/components/app/page/datatable.vue";
import Form from "~/features/budgeting/budget/components/form.vue";
import type { Option } from "~/types/form";

interface Props {
    errors: any;
}

const props = defineProps<Props>();
const data = defineModel<Partial<Budget>>();

const amounts = ref(0.0);

const categories = ref<any[]>([]);
const category = ref<Option[]>([]);
const searchTerm = ref<string>("");

const filteredItems = computed(() => {
    let items = data.value?.items ?? [];
    
    if (category.value && category.value.length > 0) {
        items = items.filter(
            (item) =>
                category.value.some((cat) => cat.label?.toLowerCase() === item.category?.name?.toLowerCase())
        );
    }

    if (searchTerm.value.trim()) {
        const term = searchTerm.value.toLowerCase();
        items = items.filter(
            (item) =>
                item.account?.name?.toLowerCase().includes(term) ||
                item.account?.code?.toLowerCase().includes(term) ||
                item.category?.name?.toLowerCase().includes(term),
        );
    }

    return items;
});

const total = (key: string, items?: BudgetItem[]): number =>
    (items ?? []).reduce((sum: any, item: any) => {
        if (item[key]) {
            return numberOnly(sum) + numberOnly(item[key]);
        }

        return sum;
    }, 0);

watch(
    data,
    (value: Budget) => {
        amounts.value = money(total("amount", value?.items ?? []), 2);
    },
    {
        deep: true,
    },
);

</script>
