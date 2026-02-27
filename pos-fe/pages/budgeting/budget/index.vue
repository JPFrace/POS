<template>
    <div>
        <NuxtLayout>
            <div class="flex flex-col gap-y-8">
                <Header v-model="form" :errors="errors" />
                <Body v-model="form" :errors="errors" />
            </div>
        </NuxtLayout>
    </div>
</template>

<script lang="ts" setup>
import Header from "~/features/budgeting/budget/components/header.vue";
import Body from "~/features/budgeting/budget/components/body.vue";
import type { Budget, BudgetItem } from "~/types/budget";
import { usePageTitle } from "~/composables/usePageTitle";
import type { ChartAccount } from "~/types/chart-account";

usePageTitle();
const { receive, dismiss } = usePageEvent();

const tableRows = 7;
const errors = ref();

const item = ref<Partial<BudgetItem>>({
    account: null,
    amount: 0,
    description: ""
});

const items: Partial<BudgetItem[]> = [];

for (let i = 0; i < tableRows; i++) {
    items.push({ ...item.value });
}

const form = ref<Partial<Budget>>({
    department: null,
    calendar: null,
    type: null,
    isPosted: false,
    items: null,
    is_inactive: false,
});

const fill = () => {
    const items = [];
    for (let i = 0; i < tableRows; i++) {
        items.push({ ...item.value });
    }

    form.value.items = items;
};

const loadAccounts = async () => {
    const result = await useClient('/api/accounting/chart-accounts', {
        method: "GET",
        params: {
            query: {
                category: true,
            },
            size: 10000,
        },
    });

    form.value.items = result.data
        .filter((row: ChartAccount) => !row.children?.length)
        .map((row: ChartAccount) => ({
            active: false,
            account: {
                id: row.uuid,
                value: row.uuid,
                label: `${row.code} - ${row.name}`,
                name: row.name,
                code: row.code,
                description: row.description,
            },
            category: row.type?.category,
            amount: 0,
            description: row.description ?? '',
            periods: null,
            isBudgeted: false,
        }))
        .sort((a, b) => (a.category?.label || '').localeCompare(b.category?.label || ''));
};

onBeforeUnmount(() => {
    dismiss("on:create-new");
    dismiss("on:error");
    dismiss("on:new-line");
    dismiss("on:clear-lines");
});

onMounted(async () => {

    await loadAccounts();

    receive("on:create-new", (_value: any) => {
        clearKeyValue(form.value);
        errors.value = [];
        fill();
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