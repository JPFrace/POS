<template>
    <AppPageTable
        :prefix-key="tab"
        endpoint="/api/accounting/chart-accounts"
        :params="{
            query: {
                category_uuid: category?.uuid,
                type: true,
                class: true,
                department: true,
                category: true,
                usage_type: true,
                children: true,
                children_class: true,
                children_type: true,
                children_category: true,
                parent_only: true,
            },
        }"
        class="min-w-[1200px]"
    >
        <template #columns>
            <el-table-column type="selection" width="55" />
            <el-table-column label="Account" min-width="150">
                <template #default="scope">
                    <div class="flex flex-col">
                        <span class="text-blue-500 font-bold">{{
                            scope.row.name
                        }}</span>

                        <span class="text-slate-400 text-xs italic">{{
                            scope.row.description
                        }}</span>

                        <span class="text-sm font-bold">
                            {{ scope.row.code }}
                        </span>
                        <span class="text-xs">{{
                            scope.row.type.category.name
                        }}</span>
                    </div>
                </template>
            </el-table-column>
            <el-table-column
                prop="type.name"
                label="Account Type"
                min-width="120"
            />
            <el-table-column
                prop="class.name"
                label="Account Class"
                min-width="120"
            />       
            <el-table-column prop="budget" label="Budget" min-width="90" />
            <el-table-column
                prop="balance"
                label="Beginning Balance"
                min-width="100"
            >
                <template #default="scope">
                    {{
                        null === scope.row.balance
                            ? ""
                            : money(scope.row.balance, 2)
                    }}
                </template>
            </el-table-column>
            <el-table-column
                prop="running_balance"
                label="Running Balance"
                min-width="100"
            >
                <template #default="scope">
                    {{
                        null === scope.row.running_balance
                            ? ""
                            : money(scope.row.running_balance, 2)
                    }}
                </template>
            </el-table-column>
            <el-table-column prop="is_inactive" label="Status" min-width="50">
                <template #default="scope">
                    <label v-if="scope.row.is_inactive" class="badge badge-info"
                        >In-active</label
                    >
                    <label v-else class="badge badge-success">Active</label>
                </template>
            </el-table-column>
            <el-table-column label="Actions" width="100" class-name="!static">
                <template #default="scope">
                    <div class="space-x-standard justify-end">
                        <AppPageDelete
                            v-if="!scope.row?.children?.length"
                            endpoint="/api/accounting/chart-accounts"
                            :uuid="scope.row.uuid"
                            :title="scope.row.name"
                            :after="() => send('all')"
                        />
                        <AppPageEdit
                            :id="scope.row.uuid"
                            :endpoint="`/api/accounting/chart-accounts/${scope.row.uuid}`"
                            width="60%"
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
                                    @click="submit(() => send('all'))"
                                >
                                    <span>Submit</span>
                                </Button>
                            </template>
                        </AppPageEdit>
                    </div>
                </template>
            </el-table-column>
        </template>
    </AppPageTable>
</template>

<script setup lang="tsx">
import Form from "./form.vue";
import type { AccountCategory } from "~/types/account-category";
interface Props {
    category: AccountCategory;
}
const { receive, dismiss, send } = usePageEvent();

const props = defineProps<Partial<Props>>();
const tab = computed(() => props.category?.uuid ?? "all");

onMounted(() => {
    receive(tab.value, (data: any) => {
        send(tab.value + "refresh");
    });
});

onBeforeUnmount(() => {
    dismiss(tab.value);
});
</script>
