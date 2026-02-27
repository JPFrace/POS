<template>
    <div class="flex items-center justify-between mb-12">
        <h1>On Going Reconciliations</h1>
        <el-button
            type="success"
            @click="send('on:setup-reconciliation', { event: 'new' })"
            >+ New Reconciliation</el-button
        >
    </div>

    <Table :data-source="props.list">
        <template #columns>
            <el-table-column label="Account Name">
                <template #default="scope">
                    {{ scope.row.cash_in_bank.bank.account_name }}
                </template>
            </el-table-column>
            <el-table-column label="Account #">
                <template #default="scope">
                    {{ scope.row.cash_in_bank.bank.account_number }}
                </template>
            </el-table-column>
            <el-table-column label="Beg. Balance">
                <template #default="scope">
                    {{ money(scope.row.bank_statement_ending_balance, 2) }}
                </template>
            </el-table-column>
            <el-table-column label="End. Balance">
                <template #default="scope">
                    {{ money(scope.row.ending_balance, 2) }}
                </template>
            </el-table-column>
            <el-table-column prop="start_at" label="Beginning Date" />
            <el-table-column prop="end_at" label="Ending Date" />
            <el-table-column label="Actions">
                <template #default="scope">
                    <AppPageMore>
                        <AppPageMoreItem
                            @click="send('start:reconciliation', scope.row)"
                        >
                            Continue
                        </AppPageMoreItem>
                        <AppPageMoreItem
                            @click="
                                send('on:setup-reconciliation', {
                                    event: 'update',
                                    data: scope.row,
                                })
                            "
                        >
                            Edit
                        </AppPageMoreItem>
                    </AppPageMore>
                </template>
            </el-table-column>
        </template>
    </Table>
</template>

<script lang="ts" setup>
import Table from "~/components/app/page/datatable.vue";
import type { Reconciliation } from "~/types/reconciliation";

interface Props {
    list: Reconciliation[];
}

const { send } = usePageEvent();
const props = defineProps<Props>();
</script>
