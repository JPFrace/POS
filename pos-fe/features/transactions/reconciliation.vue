<template>
    <template v-if="reconciliation_session.end_at != null">
        <ReconciliationSession :reconcile="reconciliation_session" />
    </template>
    <template v-else>
        <template v-if="setupCondition == 'new'">
            <Setup />
        </template>
        <template v-else-if="setupCondition == 'update'">
            <Setup :data="setup_data" />
        </template>
        <template v-else>
            <ReconciliationList :list="reconciliations" />
        </template>
    </template>
</template>

<script lang="ts" setup>
import ReconciliationSession from "./reconciliation/reconciliation-session.vue";
import type { Reconciliation } from "~/types/reconciliation";
import ReconciliationList from "./reconciliation/reconciliation-list.vue";
import Setup from "./reconciliation/setup.vue";

const setupCondition = ref("");
const { receive, dismiss } = usePageEvent();
const reconciliations = defineModel<Reconciliation[]>({ default: [] });
const default_reconcilation = {
    uuid: null,
    start_at: null,
    end_at: null,
    bank_statement_ending_balance: null,
    ending_balance: null,
    cash_in_bank: null,
    closed_at: null,
    closed_by: null,
};

const reconciliation_session = ref<Reconciliation>(default_reconcilation);
const setup_data = ref<Reconciliation>(default_reconcilation);

const getPendingReconciliations = async () => {
    try {
        const data = await useClient<Reconciliation[]>(
            "/api/accounting/reconciliations/pending-reconciliations",
            {
                method: "GET",
            },
        );
        reconciliations.value = data;
    } catch (error: any) {
        console.log(error);
    }
};

onMounted(async () => {
    await getPendingReconciliations();

    receive("on:setup-reconciliation", (value: unknown[]) => {
        if (value.event == "update") {
            setup_data.value = value.data;
        }
        setupCondition.value = value.event;
        reconciliation_session.value = default_reconcilation;
    });

    receive("start:reconciliation", (value: Reconciliation) => {
        console.log(value);

        if (value) {
            reconciliation_session.value = value;
        }
    });

    receive("close:reconciliation", () => {
        reconciliation_session.value = default_reconcilation;
    });
});

onBeforeUnmount(() => {
    dismiss("on:setup-reconciliation");
    dismiss("start:reconciliation");
    dismiss("close:reconciliation");
});
</script>
