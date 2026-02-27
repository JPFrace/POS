<template>
    <AppPageTable
        endpoint="/api/setup/payment-types"
    >
        <template #columns>
            <el-table-column type="selection" width="55" />
            <el-table-column prop="code" label="Code" />
            <el-table-column prop="name" label="Name" />
            <el-table-column prop="description" label="Description" />
            <el-table-column prop="short_desc" label="Short Description" v-if="false" />
            <el-table-column prop="inactive" label="Status">
                <template #default="scope">
                    <label v-if="scope.row.inactive" class="badge badge-info"
                        >Not Active</label
                    >
                    <label v-else class="badge badge-success">Active</label>
                </template>
            </el-table-column>
            <el-table-column prop="created_by.name" label="Created By" v-if="false" />
            <el-table-column prop="created_at" label="Created At" />
            <el-table-column label="Actions" width="150" class-name="!static">
                <template #default="scope">
                    <div class="space-x-standard">
                        <AppPageDelete
                            endpoint="/api/setup/payment-types"
                            :uuid="scope.row.uuid"
                            :title="scope.row.name"
                            @success="send('clear')"
                        />
                        <AppPageEdit
                            :id="scope.row.uuid"
                            :endpoint="`/api/setup/payment-types/${scope.row.uuid}`"
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
                                    icon="update-folder"
                                    @click="submit"
                                >
                                    <span>Update</span>
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
import { usePageEvent } from "@/composables/usePageEvent";

const { send } = usePageEvent();
</script>
