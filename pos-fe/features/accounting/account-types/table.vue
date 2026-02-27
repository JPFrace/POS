<template>
    <AppPageTable
        endpoint="/api/accounting/account-types"
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
                        <AppPageEdit
                            :id="scope.row.uuid"
                            :endpoint="`/api/accounting/account-types/${scope.row.uuid}`"
                            width="60%"
                            width-lg="60%"
                        >
                            <template #form="{ errors, form, schema }">
                                <Form
                                    :key="scope.row.uuid"
                                    :errors="errors"
                                    :form="form"
                                    :schema="schema"
                                    :data="{
                                        ...scope.row,
                                    }"
                                />
                            </template>
                            <template #drawerFooter="{ submit, cancel }">
                                <Button
                                    variant="light"
                                    class="btn btn-light ms-auto fw-semibold"
                                    icon="black-left"
                                    @click="cancel()"
                                >
                                    <span>Cancel</span>
                                </Button>
                                <Button
                                    variant="primary"
                                    class="btn btn-primary fw-semibold"
                                    icon="add-folder"
                                    @click="submit"
                                >
                                    <span>Submit</span>
                                </Button>
                            </template>
                        </AppPageEdit>
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
import Form from "./form.vue";
import LazyDeactivate from "./deactivate.vue";
</script>
