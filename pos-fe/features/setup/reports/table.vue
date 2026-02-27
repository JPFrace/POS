<template>
    <AppPageTable endpoint="api/setup/reports">
        <template #columns>
            <el-table-column type="selection" width="55" />
            <el-table-column prop="name" label="Name" />
            <el-table-column prop="description" label="Description" />
            <el-table-column prop="is_inactive" label="Status" width="150">
                <template #default="scope">
                    <el-badge
                        :value="scope.row.is_inactive ? 'Inactive' : 'Active'"
                        :type="scope.row.is_inactive ? 'danger' : 'success'"
                    />
                </template>
            </el-table-column>
            <el-table-column prop="created_at" label="Created" />
            <el-table-column label="Actions" width="150" class-name="!static">
                <template #default="scope">
                    <div class="space-x-standard">
                        <AppPageDelete
                            endpoint="/api/setup/reports"
                            :uuid="scope.row.uuid"
                            :title="scope.row.name"
                        />
                        <AppPageEdit
                            :id="scope.row.uuid"
                            :endpoint="`/api/setup/reports/${scope.row.uuid}`"
                            width="40%"
                            width-lg="40%"
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
                                    class="btn btn-light w-25 ms-auto fw-semibold"
                                    icon="black-left"
                                    @click="cancel()"
                                >
                                    <span>Cancel</span>
                                </Button>
                                <Button
                                    variant="primary"
                                    class="btn btn-primary w-25 fw-semibold"
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
