<template>
    <AppPageTable
        endpoint="/api/business/journal-entries"
        :params="{
            query: {
                category: true,
            },
        }"
    >
        <template #columns>
            <el-table-column type="selection" width="55" />
            <el-table-column prop="name" label="Name">
                <template #default="scope">
                    <div class="flex flex-col gap-y-1">
                        <span class="text-blue-400 font-bold">{{
                            scope.row.name
                        }}</span>
                        <span class="text-slate-400 text-xs italic">{{
                            scope.row.description
                        }}</span>
                    </div>
                </template>
            </el-table-column>
            <el-table-column prop="category.name" label="Category" />
            <el-table-column prop="is_inactive" label="Status">
                <template #default="scope">
                    <label v-if="scope.row.is_inactive" class="badge badge-info"
                        >Not active</label
                    >
                    <label v-else class="badge badge-success">Active</label>
                </template>
            </el-table-column>
            <el-table-column prop="created_at" label="Created" />
            <el-table-column label="Actions" width="150" class-name="!static">
                <template #default="scope">
                    <div class="space-x-standard">
                        <AppPageDelete
                            endpoint="/api/accounting/account-types"
                            :uuid="scope.row.uuid"
                            :title="scope.row.name"
                        />
                        <AppPageMore>
                            <LazyDeactivate />
                        </AppPageMore>
                    </div>
                </template>
            </el-table-column>
        </template>
    </AppPageTable>
</template>

<script lang="ts" setup></script>
