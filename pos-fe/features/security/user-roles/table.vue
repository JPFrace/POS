<template>
    <AppPageTable endpoint="/api/security/roles">
        <template #columns>
            <el-table-column type="selection" width="55" />
            <el-table-column prop="name" label="Role" />
            <el-table-column prop="description" label="Description" />
            <el-table-column prop="is_inactive" label="Status">
                <template #default="scope">
                    <div class="flex justify-between items-center">
                        <BadgeElTag
                            :label="scope.row.is_inactive ? 'Inactive' : 'Active'"
                            :status="scope.row.is_inactive ? 'inactive' : 'active'"
                        />
                    </div>
                </template>
            </el-table-column>
            <el-table-column prop="created_at" label="Created" />
            <el-table-column label="Actions" width="150" class-name="!static">
                <template #default="scope">
                    <div class="space-x-standard">
                        <AppPageDelete
                            endpoint="/api/security/roles"
                            :uuid="scope.row.uuid"
                            :title="scope.row.name"
                        />
                        <AppPageEdit
                            :id="scope.row.uuid"
                            :endpoint="`/api/security/roles/${scope.row.uuid}`"
                            width="25%"
                            width-lg="25%"
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
