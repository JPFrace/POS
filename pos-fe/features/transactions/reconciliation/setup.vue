<template>
    <div class="flex flex-col mb-15">
        <div class="flex flex-row mb-8">
            <h1
                class="d-flex flex-column justify-content-center my-0 text-gray-900 fw-bold"
            >
                {{ props.data ? "Edit" : "Choose" }} Bank Account to Reconcile
            </h1>
        </div>

        <div v-if="!props.data" class="mb-4">
            <div class="mb-2">
                <label class="fs-5">Bank Account</label>
            </div>
            <div>
                <Select
                    style="width: 500px"
                    v-model:data="bankAccount"
                    v-model:selected="selectedBankAccount"
                    column
                    custom-column
                    url="/api/setup/bank-accounts"
                    :map-result="
                        (result: any) =>
                            result.data.map((row: any) => ({
                                id: row.uuid,
                                value: row.uuid,
                                label: row.account_name,
                                account_number: row.account_number,
                                bank_name: row.bank_name,
                                account_name: row.account_name,
                                columns: [
                                    'Account Code',
                                    'Account Name',
                                    'Bank Name',
                                ],
                            }))
                    "
                    :map-query="
                        (search: any) => ({
                            query: {
                                account_name: search,
                            },
                        })
                    "
                    :@is-valid="isValid('bank_account')"
                    clearable
                    remote
                    loading
                    @change="bankAccountOnChange"
                    :disabled="props.data ? true : undefined"
                >
                    <template #customColumn="{ data }">
                        <BankColumn :data="data" />
                    </template>
                </Select>
            </div>
        </div>
        <div v-if="setup_data?.cash_in_bank?.bank">
            <div>
                <h3
                    class="d-flex flex-column justify-content-center my-0 text-gray-900 fw-bold"
                >
                    Bank Details
                </h3>
            </div>
            <div>
                <label>
                    <span>Bank Name: </span>
                    <span>{{ setup_data?.cash_in_bank?.bank.bank_name }}</span>
                </label>
            </div>
            <div>
                <label>
                    <span>Account Name: </span>
                    <span>{{
                        setup_data?.cash_in_bank?.bank.account_name
                    }}</span>
                </label>
            </div>
            <div>
                <label>
                    <span>Account Number: </span>
                    <span>{{
                        setup_data?.cash_in_bank?.bank.account_number
                    }}</span>
                </label>
            </div>
        </div>
    </div>

    <div v-if="setup_data?.bank_account" class="flex flex-col">
        <div class="flex flex-row mb-5">
            <h2
                class="d-flex flex-column justify-content-center my-0 text-gray-900 fw-bold"
            >
                Fill up the supporting information
            </h2>
        </div>
        <div class="mb-8">
            <div class="flex flex-row">
                <div>
                    <label class="text-blue-600 mr-4"
                        >Last statement ending date</label
                    >
                </div>
                <div class="flex flex row">
                    <div v-if="previous_reconciliation.end_at || props.data">
                        <span class="font-bold">{{
                            moment(
                                previous_reconciliation?.end_at ??
                                    props.data?.end_at,
                            ).format("MM/DD/YYYY")
                        }}</span>
                    </div>
                    <div v-else>
                        <div>
                            <span
                                >Previous reconciliation not found, select a
                                <b>Date</b> to initialize the
                                reconciliation</span
                            >
                        </div>
                        <div class="flex flex-row">
                            <el-date-picker
                                style="width: 200px"
                                size="large"
                                v-model="setup_data!.start_at"
                                format="MM/DD/YYYY"
                                value-format="MM/DD/YYYY"
                            ></el-date-picker>
                            <span class="ml-4 pt-2"
                                ><--Select a Beginning Date</span
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-row mb-12">
            <div class="mr-12">
                <div><label class="fs-5">Beginning Balance</label></div>
                <div
                    v-if="
                        previous_reconciliation?.ending_balance ||
                        props.data != null
                    "
                >
                    <label class="fs-2">{{
                        currencyFormat(
                            previous_reconciliation?.ending_balance ??
                                props.data?.bank_statement_ending_balance,
                            2,
                        )
                    }}</label>
                </div>
                <div v-else>
                    <Currency
                        v-model="setup_data!.bank_statement_ending_balance"
                        placeholder="Enter Amount"
                        :@is-valid="isValid('bank_statement_ending_balance')"
                        float
                    ></Currency>
                </div>
            </div>
            <div class="mr-12">
                <div><label class="fs-5">Ending Balance</label></div>
                <div>
                    <Currency
                        v-model="setup_data!.ending_balance"
                        placeholder="Enter Amount"
                        :@is-valid="isValid('ending_balance')"
                        float
                    ></Currency>
                </div>
            </div>

            <div class="mr-12">
                <div><label class="fs-5">Ending Date</label></div>
                <div>
                    <el-date-picker
                        size="large"
                        v-model="setup_data!.end_at"
                        format="MM/DD/YYYY"
                        value-format="MM/DD/YYYY"
                    ></el-date-picker>
                </div>
            </div>
        </div>
        <div>
            <ReconcileNow v-model="setup_data" :is_new="!props.data" />
            <el-button
                size="large"
                type="danger"
                @click="send('on:setup-reconciliation', {})"
            >
                Back
            </el-button>
        </div>
    </div>
    <div v-else>
        <el-button
            size="large"
            type="danger"
            @click="send('on:setup-reconciliation', {})"
        >
            Back
        </el-button>
    </div>
</template>

<script lang="ts" setup>
import BankColumn from "./components/bank-column.vue";
import moment from "moment";
import ReconcileNow from "./components/reconcile-now.vue";
import type { Reconciliation } from "~/types/reconciliation";
import type { ChartAccount } from "~/types/chart-account";
import { currencyFormat } from "~/utils/helper";

interface Props {
    data?: Reconciliation;
}

const { send, receive, dismiss } = usePageEvent();
const props = defineProps<Props>();
const errors = ref();

const default_value = ref<Reconciliation>({
    uuid: null,
    start_at: null,
    end_at: null,
    bank_statement_ending_balance: null,
    ending_balance: null,
    cash_in_bank: null,
    closed_at: null,
    closed_by: null,
});

const setup_data = ref<Reconciliation>(default_value.value);
const previous_reconciliation = ref<Reconciliation>(default_value.value);

const bankAccount = ref();
const selectedBankAccount = ref([]);

const isValid = (key: string) =>
    errors.value
        ? Object.keys(errors.value).includes(key)
            ? (errors.value as any)[key]?.length <= 0
            : null
        : null;

const getprevious_reconciliation = async (bank: any) => {
    const result = await useClient(
        "/api/accounting/reconciliations/previous-reconciliation/" + bank.id,
        {
            method: "POST",
            body: {},
        },
    );

    if (result) {
        previous_reconciliation.value = result;
        setup_data.value.bank_statement_ending_balance =
            result.data?.ending_balance;
        setup_data.value.start_at = result.data?.end_at;
    } else {
        previous_reconciliation.value = default_value.value;
    }
};

const bankAccountOnChange = async () => {
    const selected_bank = selectedBankAccount.value;

    setup_data.value = {
        ...setup_data.value,
        bank_account: selected_bank,
    };

    await getprevious_reconciliation(selected_bank);
};

onMounted(() => {
    if (props.data) {
        selectedBankAccount.value = {
            id: props.data.cash_in_bank?.bank?.uuid,
            value: props.data.cash_in_bank?.bank?.uuid,
            label: props.data.cash_in_bank?.bank?.account_name,
            ...props.data.cash_in_bank?.bank,
        };
        setup_data.value = {
            ...props.data,
            bank_account: selectedBankAccount.value,
        };
        console.log(setup_data.value);
    }

    receive("on:setup-error", (value: any) => {
        errors.value = value;
    });
});

onBeforeUnmount(() => {
    dismiss("on:setup-error");
});
</script>
