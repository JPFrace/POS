<template>
    <div class="overflow-auto">
        <AppPageTable
            endpoint="/api/contacts/vendors"
            :params="{
                query: {
                    createdBy: true,
                    class: true,
                    type: true,
                    tax: true,
                    country: true,
                },
            }"
            class="min-w-[1200px]"
        >
            <template #columns>
                <el-table-column type="selection" width="55" />
                <el-table-column prop="id_no" label=" ID No." min-width="40" />
                <el-table-column label="Name" min-width="80">
                    <template #default="scope">
                        <NuxtLink
                            :to="`/contacts/vendors/${scope.row.uuid}`"
                            class="!text-blue-500 font-bold hover:!underline underline-offset-4"
                        >
                            {{ formatFullName(scope.row) }}
                        </NuxtLink>
                        <div class="text-sm">
                            {{ scope.row.contact_number }}
                        </div>
                        <div class="font-semibold text-sm">
                            {{ scope.row.sub_type.name }}
                        </div>
                    </template>
                </el-table-column>
                <el-table-column
                    prop="email"
                    label="Email Address"
                    min-width="90"
                />
                <el-table-column
                    prop="created_by.name"
                    label="Created By"
                    min-width="40"
                />
                <el-table-column
                    prop="created_at"
                    label="Created At"
                    min-width="40"
                />
                <el-table-column
                    label="Actions"
                    width="150"
                    class-name="!static"
                >
                    <template #default="scope">
                        <div class="space-x-standard">
                            <AppPageDelete
                                endpoint="/api/contacts/vendors"
                                :uuid="scope.row.uuid"
                                :title="scope.row.name"
                                @success="send('clear')"
                            />
                            <NuxtLink
                                :to="`/contacts/vendors/${scope.row.uuid}`"
                            >
                                <KTIcon
                                    title="Edit Record"
                                    icon-name="notepad-edit"
                                    icon-type="outline"
                                    icon-class="!text-3xl cursor-pointer !text-blue-500 hover:!text-blue-700 dark:hover:!text-blue-400"
                                />
                            </NuxtLink>
                            <AppPageMore>
                                <LazyDeactivate />
                            </AppPageMore>
                        </div>
                    </template>
                </el-table-column>
            </template>
        </AppPageTable>
    </div>
</template>

<script lang="ts" setup>
import LazyDeactivate from "./deactivate.vue";

const { send } = usePageEvent();

const formatFullName = (row: any): string => {
    // If has first_name → assume it's an individual
    if (row?.first_name) {
        const names = [
            row.first_name,
            row.middle_name,
            row.last_name,
            row.suffix,
        ].filter(Boolean); // removes null/undefined/empty
        return names.join(" ");
    }

    // Otherwise → assume it's a business
    return row?.name || "";
};
</script>
