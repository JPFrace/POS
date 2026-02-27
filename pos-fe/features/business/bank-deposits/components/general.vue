<template>
    <div class="w-full flex flex-col items-baseline justify-between gap-y-9">
        <div class="flex gap-x-4 w-full">
            <div>
                <label class="form-label"
                    >Account
                    <span class="text-xs italic">(Search records)</span></label
                >
                <Select
                    ref="accounts"
                    url="/api/accounting/chart-accounts"
                    v-model:data="cash_in_banks"
                    v-model:selected="data!.cash_in_bank"
                    :mapResult="
                        (result: any) =>
                            result.data.map((row: ChartAccount) => ({
                                id: row.uuid,
                                value: row.uuid,
                                label: `${row.code} - ${row.name}`,
                                type: row.type.name,
                                code: row.code,
                                description: row.description,
                            }))
                    "
                    :mapQuery="
                        (search: any) => ({
                            query: {
                                name_code: search,
                                category: true,
                                type: true,
                                'cash_in_bank.undeposited': true,
                            },
                        })
                    "
                    :is-valid="isValid('cash_in_bank')"
                    clearable
                    remote
                    loading
                />
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import type { ChartAccount } from "~/types/chart-account";
import type { OfficialReceipt } from "~/types/official-receipts";

interface Props {
    errors: any;
}
const props = defineProps<Props>();

const data = defineModel<Partial<OfficialReceipt>>();

const cash_in_banks = defineModel("cash_in_banks");

const isValid = (key: string) =>
    props.errors
        ? Object.keys(props.errors).includes(key)
            ? (props.errors as any)[key]?.length <= 0
            : null
        : null;
</script>
