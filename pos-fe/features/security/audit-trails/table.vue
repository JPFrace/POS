<template>
    <AppPageTable
        endpoint="/api/security/audit-trails"
        :params="{
            query: {
                user: true,
            },
        }"
    >
        <template #columns>
            <el-table-column prop="user_type" label="Name" />
            <el-table-column prop="event" label="Event" />
            <el-table-column prop="auditable_type" label="Type" />
            <el-table-column prop="created_at" label="Created At" />
            <el-table-column label="Actions" width="100" class-name="!static">
                <template #default="scope">
                    <div class="space-x-standard">
                        <KTIcon
                            title="View"
                            icon-name="eye"
                            icon-class="ml-3 fs-2hx cursor-pointer hover:dark:text-white hover:text-slate-900"
                            @click="openModal(scope.row)"
                        />
                    </div>
                </template>
            </el-table-column>
        </template>
    </AppPageTable>
    <Form v-if="showModal" :data="selectedRow" @close="showModal = false" />
</template>

<script lang="ts" setup>
import { ref } from "vue";
import Form from "./form.vue";
import type { AuditTrails } from "~/types/audit-trails";

const showModal = ref(false);
const selectedRow = ref<AuditTrails | null>(null);

function openModal(row: AuditTrails) {
    selectedRow.value = row;
    showModal.value = true;
}
</script>
