<template>
    <AppPageTable endpoint="/api/setup/bank-accounts"
      :params="{
            query: {
                chartAccount: true,
            },
        }">
        <template #columns>
            <el-table-column type="selection" width="55" />
            <el-table-column prop="account_name" label="Account Name" />
            <el-table-column prop="account_number" label="Account Number" />
            <el-table-column prop="bank_name" label="Bank Name" />
            <el-table-column prop="bank_code" label="Bank Code" />
            <el-table-column prop="is_inactive" label="Status">
                <template #default="scope">
                    <label v-if="scope.row.is_inactive" class="badge badge-info"
                        >Not active</label
                    >
                    <label v-else class="badge badge-success">Active</label>
                </template>
            </el-table-column>
            <el-table-column prop="created_at" label="Date Created" />
            <el-table-column label="Actions" width="150" class-name="!static">
                <template #default="scope">
                    <div class="space-x-standard">
                        <AppPageDelete
                            endpoint="/api/setup/bank-accounts"
                            :uuid="scope.row.uuid"
                            :title="scope.row.name"
                        />
                        <AppPageEdit
                            :id="scope.row.uuid"
                            :endpoint="`/api/setup/bank-accounts/${scope.row.uuid}`"
                            width="30%"
                            width-lg="30%"
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
