<template>
    <AppPageTable
        endpoint="/api/setup/signatories"
        :params="{
            query: {
                department: true,
                position: true,
            },
        }"
    >
        <template #columns>
            <el-table-column type="selection" width="55" />
            <el-table-column prop="name" label="Name" />
            <el-table-column prop="position.title" label="Position" />
            <el-table-column prop="department.name" label="Department" />
            <el-table-column prop="e_signature" label="E-Signature" />
            <el-table-column prop="created_by.name" label="Created" />
            <el-table-column label="Actions" width="150" class-name="!static">
                <template #default="scope">
                    <div class="space-x-standard">
                        <AppPageDelete
                            endpoint="/api/setup/signatories"
                            :uuid="scope.row.uuid"
                            :title="scope.row.name"
                        />
                        <AppPageEdit
                            :id="scope.row.uuid"
                            :endpoint="`/api/setup/signatories/${scope.row.uuid}`"
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
                                    class="ms-auto btn btn-light fw-semibold"
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
