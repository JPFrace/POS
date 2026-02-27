<template>
    <AppPageTable
        endpoint="/api/setup/withholding-tax"
        :params="{
            query: {
                tax_type: true,
                payer_type: true,
            },
        }"
    >
        <template #columns>
            <el-table-column type="selection" width="55" />
            <el-table-column prop="code" label="Code" />
            <el-table-column
                prop="description"
                width="255"
                label="Description"
            />
            <el-table-column prop="rate" width="75" label="Rate">
                <template #default="scope">
                    <span>{{ scope.row.rate }}%</span>
                </template>
            </el-table-column>
            <el-table-column label="Tax Type">
                <template #default="scope">
                    <el-tooltip
                        :content="`${scope.row.type.name} (${scope.row.type.code})`"
                        placement="top"
                    >
                        <span>{{ scope.row.type.code }}</span>
                    </el-tooltip>
                </template>
            </el-table-column>
            <el-table-column
                prop="payer_type.name"
                width="150"
                label="Payer Type"
            />
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
                            endpoint="/api/setup/withholding-tax"
                            :uuid="scope.row.uuid"
                            :title="scope.row.name"
                        />
                        <AppPageEdit
                            :id="scope.row.uuid"
                            :endpoint="`/api/setup/withholding-tax/${scope.row.uuid}`"
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
