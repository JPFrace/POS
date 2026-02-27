<template>
    <AppPageTable endpoint="api/budgeting/budget" :params="{
        query: {
            department: true,
            calendar: true,
            type: true
        },
    }">
        <template #columns>
            <el-table-column type="selection" width="55" />
            <el-table-column prop="name" label="Name" :sortable="true">
                <template #default="scope">
                    <div class="flex flex-col gap-y-1">
                        <NuxtLink :to="`/budgeting/budget/${scope.row.uuid}`"
                            class="font-bold !text-blue-500 hover:!underline underline-offset-2">
                            {{ scope.row.name }}
                        </NuxtLink>
                        <p class="text-sm text-gray-600">{{ scope.row.description }}</p>
                    </div>
                </template>
            </el-table-column>
            <el-table-column prop="department" label="Department" :sortable="true">
                <template #default="scope">
                    {{ scope.row.department ? scope.row.department.name : 'All Department' }}
                </template>
            </el-table-column>
            <el-table-column prop="calendar.year" label="Fiscal Year" :sortable="true" />
            <el-table-column prop="type.name" label="Type" width="120" :sortable="true" />
            <el-table-column prop="status" label="Status" width="120" :sortable="true">
                <template #default="scope">
                    <div class="flex justify-between items-center">
                        <el-tag
                            round
                            effect="dark"
                            :type="posted(scope) ? 'success' : 'info'"

                        >
                            {{ scope.row.status }}
                        </el-tag>
                    </div>
                </template>
            </el-table-column>
            <el-table-column prop="created_at" label="Created" width="120" :sortable="true" />
            <el-table-column label="Actions" width="150" class-name="!static">
                <template #default="scope">
                    <div class="space-x-standard justify-end">
                        <AppPagePost endpoint="/api/budgeting/budget/post" :uuid="scope.row.uuid" :title="scope.row.name"
                            :posted="posted(scope)" :after="() => send(`refresh`)" />
                        <AppPageDelete endpoint="/api/budgeting/budget" :uuid="scope.row.uuid"
                            :title="scope.row.name" :disabled="posted(scope)" />
                        <AppPageMore>
                            <LazyDeactivate />
                        </AppPageMore>
                    </div>
                </template>
            </el-table-column>
        </template>
    </AppPageTable>
</template>

<script lang="ts" setup>
import LazyDeactivate from "./components/deactivate.vue";
const { send } = usePageEvent();

const posted = (scope: any) => {
    return scope.row.status === 'Posted';
};
</script>
