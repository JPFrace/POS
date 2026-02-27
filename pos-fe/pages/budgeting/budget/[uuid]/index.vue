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
import moment from "moment";

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
    items: null,
});

const fill = () => {
    const items = [];
    for (let i = 0; i < tableRows; i++) {
        items.push({ ...item.value });
    }

    form.value.items = items;
};

const route = useRoute();
const client = useSanctumClient();
const { data: budgets, refresh } = useAsyncData<Budget[]>(
    `${id(route.fullPath)}.budgets`,
    () =>
        client("/api/budgeting/budget", {
            method: "GET",
            params: {
                query: {
                    uuid: route.params.uuid,
                    department: true,
                    calendar: true,
                    type: true,
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
    if (!budgets.value?.data?.[0]) {
        return;
    }
    const budgetData = budgets.value.data[0] ?? [];
    const { budget_details, ...filtered } = budgetData;
    form.value = {
        ...filtered,
        name: budgetData.name,
        description: budgetData.description,
        department: budgetData.department
            ? {
                id: budgetData.department.uuid,
                value: budgetData.department.uuid,
                label: budgetData.department.name,
            }
            : null,
        calendar: budgetData.calendar
            ? {
                id: budgetData.calendar.uuid,
                value: budgetData.calendar.uuid,
                label: `${moment(budgetData.calendar.start_date).format('MM/DD/YYYY')} - ${moment(budgetData.calendar.end_date).format('MM/DD/YYYY')}`,
                year: budgetData.calendar.year,
            } : null,
        type: budgetData.type
            ? {
                id: budgetData.type.uuid,
                value: budgetData.type.uuid,
                label: budgetData.type.name,
            } : null,
        isPosted: budgetData.status.toLowerCase() === 'posted',
        is_inactive: budgetData.is_inactive
    };
    const items: Partial<BudgetItem>[] = [];
    (budgetData?.budget_details ?? []).forEach((row: any) => {
        console.log('Budget Detail Row:', row);
        items.push({
            account: {
                id: row.account.uuid,
                value: row.account.uuid,
                label: `${row.account.code} - ${row.account.name}`,
                name: row.account.name,
                code: row.account.code,
                description: row.account.description,
            },
            category: row.category,
            amount: row.amount ?? 0.00,
            description: row.description,
            periods: row.periods ? {
                uuid: row.periods.uuid,
                period_1: row.periods.period_1 ?? 0,
                period_2: row.periods.period_2 ?? 0,
                period_3: row.periods.period_3 ?? 0,
                period_4: row.periods.period_4 ?? 0,
                period_5: row.periods.period_5 ?? 0,
                period_6: row.periods.period_6 ?? 0,
                period_7: row.periods.period_7 ?? 0,
                period_8: row.periods.period_8 ?? 0,
                period_9: row.periods.period_9 ?? 0,
                period_10: row.periods.period_10 ?? 0,
                period_11: row.periods.period_11 ?? 0,
                period_12: row.periods.period_12 ?? 0,
                total: row.amount ?? 0,
            } : null,
            isBudgeted: row.is_budgeted,
        });
    });
    form.value.items = items;
};

onBeforeUnmount(() => {
    dismiss("on:create-new");
    dismiss("on:error");
    dismiss("on:new-line");
    dismiss("on:clear-lines");
});

onMounted(async () => {

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

watch(budgets, () => {
    fillItem();
});
</script>
