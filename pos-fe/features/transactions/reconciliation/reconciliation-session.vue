<template>
    <div class="flex flex-col w-full">
        <div class="flex flex-col mb-5">
            <div class="flex justify-between">
                <div class="flex flex-row">
                    <label class="text-4xl font-semibold">
                        Reconcile&nbsp;
                    </label>
                    <span class="text-4xl font-semibold">{{
                        bank_details?.account_name +
                        " - " +
                        bank_details?.account_number
                    }}</span>
                </div>
                <div class="flex flex-row">
                    <div class="mr-2">
                        <el-button size="large">Edit Info</el-button>
                    </div>
                    <div>
                        <el-dropdown
                            split-button
                            size="large"
                            @click="send('close:reconciliation')"
                        >
                            Save for later
                            <template #dropdown>
                                <el-dropdown-menu>
                                    <el-dropdown-item>
                                        Close Reconciliation
                                    </el-dropdown-item>
                                </el-dropdown-menu>
                            </template>
                        </el-dropdown>
                    </div>
                </div>
            </div>
            <div class="flex flex-row">
                <label class="mr-4">Beginning date:</label>
                <span>{{
                    moment(props.reconcile.start_at).format("MMMM DD, YYYY")
                }}</span>
            </div>
            <div class="flex flex-row">
                <label class="mr-4">Statement ending date:</label>
                <span>{{
                    moment(props.reconcile.end_at).format("MMMM DD, YYYY")
                }}</span>
            </div>
        </div>
        <div class="flex flex-row w-full">
            <div class="flex flex-1 flex-col items-center justify-center">
                <div class="flex flex-col items-center mb-10">
                    <div class="flex flex-col">
                        <div class="flex flex-row mb-5">
                            <div class="flex flex-col">
                                <div class="text-center">
                                    <p class="text-5xl font-semibold">
                                        {{ money(balances.ending, 2) }}
                                    </p>
                                </div>
                                <div class="text-center">
                                    BANK STATEMENT ENDING BALANCE
                                </div>
                            </div>
                            <div class="flex flex-col mx-8">
                                <div class="text-center">
                                    <p class="text-5xl">-</p>
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <div class="text-center">
                                    <p class="text-5xl font-semibold">
                                        {{ money(cleared_balance, 2) }}
                                    </p>
                                </div>
                                <div class="text-center">CLEARED BALANCE</div>
                            </div>
                        </div>
                        <div class="flex flex-row">
                            <div class="flex flex-col">
                                <div class="text-center">
                                    <p class="text-3xl font-semibold">
                                        {{ money(balances.beginning, 2) }}
                                    </p>
                                </div>
                                <div class="text-center">BEGINNING BALANCE</div>
                            </div>
                            <div class="flex flex-col mx-8">
                                <div class="text-center">
                                    <p class="text-3xl">-</p>
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <div class="text-center">
                                    <p class="text-3xl font-semibold">
                                        {{
                                            money(
                                                transaction_balances.payments,
                                                2,
                                            )
                                        }}
                                    </p>
                                </div>
                                <div class="text-center">
                                    OUTSTANDING CHECKS
                                </div>
                            </div>
                            <!-- <div class="flex flex-col mx-8">
                            <div class="text-center">
                                <p class="text-3xl">+</p>
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <div class="text-center">
                                <p class="text-3xl font-semibold">
                                    {{ money(transaction_balances.sales, 2) }}
                                </p>
                            </div>
                            <div class="text-center">SALES</div>
                        </div> -->
                            <div class="flex flex-col mx-8">
                                <div class="text-center">
                                    <p class="text-3xl">+</p>
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <div class="text-center">
                                    <p class="text-3xl font-semibold">
                                        {{
                                            money(
                                                transaction_balances.deposits,
                                                2,
                                            )
                                        }}
                                    </p>
                                </div>
                                <div class="text-center">DEPOSITS</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-1 flex-col items-center justify-center">
                <div class="flex flex-col">
                    <div class="text-center">
                        <p class="text-5xl font-semibold">
                            {{ money(difference, 2) }}
                        </p>
                    </div>
                    <div class="text-center">DIFFERENCE</div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <AppPageTable
            endpoint="/api/accounting/reconciliations/transaction-list"
            :params="params"
        >
            <template #columns>
                <el-table-column label="Status">
                    <template #default="{ row }">
                        <el-checkbox
                            size="large"
                            :model-value="row.reconcile_at !== null"
                            @change="
                                reconcile(
                                    row,
                                    props.reconcile?.start_at ?? '',
                                    props.reconcile?.end_at ?? '',
                                )
                            "
                        />
                    </template>
                </el-table-column>
                <el-table-column prop="posted_at" label="Date" />
                <el-table-column prop="trans_type" label="Payment type">
                    <template #default="scope">
                        {{ transactionType(scope.row.trans_type) }}
                    </template>
                </el-table-column>
                <el-table-column label="Payment method">
                    <template #default="scope">
                        {{ scope?.row?.transactable?.payment_method?.name }}
                    </template>
                </el-table-column>
                <el-table-column prop="ref_no" label="Ref No.">
                    <template #default="scope">
                        {{
                            scope.row.trans_type == 70 ? "-" : scope.row.ref_no
                        }}
                    </template>
                </el-table-column>
                <el-table-column prop="contact_name" label="Payee" />
                <el-table-column label="Payment (PHP)">
                    <template #default="scope">{{
                        money(
                            transactionPayment(
                                scope.row.debit,
                                scope.row.credit,
                                scope.row.trans_type,
                            ),
                            2,
                        )
                    }}</template>
                </el-table-column>
            </template>
        </AppPageTable>
    </div>
</template>
<script lang="ts" setup>
import moment from "moment";
import type { Reconciliation } from "~/types/reconciliation";
import type { ReconciliationSetup } from "~/types/reconciliation-setup";
import { Search, ArrowDown } from "@element-plus/icons-vue";

interface Props {
    reconcile: Reconciliation;
}

const { send } = usePageEvent();
const props = defineProps<Props>();
const setup = defineModel<ReconciliationSetup>();
const balances = ref({
    beginning: props.reconcile.bank_statement_ending_balance,
    ending: props.reconcile.ending_balance,
});

var bank_details = props.reconcile.cash_in_bank?.bank;
var transaction_balances = ref({
    sales: 0,
    payments: 0,
    deposits: 0,
});
var params = ref({
    bank_account: props.reconcile.cash_in_bank?.uuid,
    end_date: moment(props.reconcile.end_at).format("YYYY-MM-DD"),
    beginning_date: moment(props.reconcile.start_at).format("YYYY-MM-DD"),
});

const cleared_balance = computed(
    () =>
        Number(balances.value.beginning) +
        Number(transaction_balances.value.sales) +
        Number(transaction_balances.value.deposits) -
        Number(transaction_balances.value.payments),
);

const difference = computed(
    () => Number(balances.value.ending) - Number(cleared_balance.value),
);

const reconcile = async (
    row: any,
    beginning_date: string,
    end_date: string,
) => {
    const formData = new FormData();
    formData.append("journal_id", row.uuid);
    formData.append(
        "beginning_at",
        moment(beginning_date).format("YYYY-MM-DD"),
    );
    formData.append("ending_at", moment(end_date).format("YYYY-MM-DD"));
    formData.append("event", row.reconcile_at ? "unpost" : "post");
    formData.append("transaction_type", row.trans_type);
    const result = await useClient(
        "/api/accounting/reconciliations/reconcile-transaction",
        {
            method: "POST",
            body: formData,
        },
    );
    row.reconcile_at = result.reconcile_at;

    switch (row.trans_type) {
        case "10": // Journal Entries
            break;
        // case "20": // Sales
        //     transaction_balances.value.sales = Number(result.trans_total);
        //     break;
        case "30": // Payments
            transaction_balances.value.payments = Number(result.trans_total);
            break;
        case "70": // Deposits
            transaction_balances.value.payments = Number(result.trans_total);
            break;
    }
};

const reconciledTransactions = async () => {
    const formData = new FormData();
    formData.append("account_id", props.reconcile.cash_in_bank?.uuid ?? "");
    formData.append(
        "beginning_at",
        moment(props.reconcile.start_at, "MM/DD/YYYY").format("YYYY-MM-DD"),
    );
    formData.append(
        "ending_at",
        moment(props.reconcile.end_at, "MM/DD/YYYY").format("YYYY-MM-DD"),
    );
    try {
        const data = await useClient(
            "/api/accounting/reconciliations/reconciled-transactions",
            {
                method: "POST",
                body: formData,
            },
        );
        transaction_balances.value.sales = Number(data.sales);
        transaction_balances.value.payments = Number(data.payments);
        transaction_balances.value.deposits = Number(data.deposits);
    } catch (error: any) {
        console.log(error);
    }
};

onMounted(async () => {
    await reconciledTransactions();
});
</script>
