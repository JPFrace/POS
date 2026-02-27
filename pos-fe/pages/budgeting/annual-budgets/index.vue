<template>
    <div>
        <NuxtLayout>
            <template #toolbarLeft>
                <AppPageSearch query="search" no-options />
            </template>
            <template #toolbarRight>
                <div class="space-x-standard">
                    <NuxtLink to="/budgeting/budget">
                        <Button type="button" variant="primary" icon="plus-square">Add New</Button>
                    </NuxtLink>
                    <AppPageDeleteSel endpoint="/api/setup/departments/selected" />
                </div>
            </template>
            <div>
                <TableBudgets />
            </div>
        </NuxtLayout>
    </div>
</template>

<script lang="ts" setup>
import TableBudgets from "~/features/budgeting/budget/table.vue";
import { usePageTitle } from "~/composables/usePageTitle";

const show = () => {
    navigateTo("/budgeting/budget/form");
};

usePageTitle();
const { send } = usePageEvent();
</script>
