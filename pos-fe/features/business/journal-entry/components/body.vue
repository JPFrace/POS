<template>
    <div class="table-responsive">
        <div class="bg-white">
            <table
                class="table table-bordered table-hover table-rounded border gy-4 gs-4 table-row-gray-300"
            >
                <thead>
                    <tr
                        class="fw-semibold fs-6 text-gray-800 border-bottom-2 border-gray-200"
                    >
                        <th width="3%" class="text-center"></th>
                        <th width="3%" class="text-center">#</th>
                        <th width="25%">ACCOUNT</th>
                        <th width="10%">DEBITS</th>
                        <th width="10%">CREDITS</th>
                        <th width="27%">DESCRIPTION</th>
                        <th width="19%">NAME</th>
                        <th width="3%" class="text-center"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in data.items">
                        <td class="text-center">
                            <KTIcon
                                icon-name="abstract-30"
                                icon-class="fs-2 cursor-pointer"
                            />
                        </td>
                        <td class="text-center">{{ parseInt(index) + 1 }}</td>
                        <td class="cursor-pointer" @click="active(index)">
                            <div
                                v-if="item.active"
                                class="flex flex-col gap-y-2 justify-baseline items-start"
                            >
                                <Select
                                    ref="accounts"
                                    column
                                    custom-column
                                    url="/api/accounting/chart-accounts"
                                    v-model:data="chartAccounts"
                                    v-model:selected="item.account"
                                    :mapResult="
                                        (result: any) =>
                                            result.data.map(
                                                (row: ChartAccount) => ({
                                                    id: row.uuid,
                                                    value: row.uuid,
                                                    label: `${row.name}`,
                                                    type: row.type.name,
                                                    code: row.code,
                                                    department: row.department,
                                                    description:
                                                        row.description,
                                                    columns: [
                                                        'account',
                                                        'department',
                                                        'type',
                                                    ],
                                                    children: row.children?.map(
                                                        (children) => ({
                                                            id: children.uuid,
                                                            value: children.uuid,
                                                            label: `${children.code} - ${children.name}`,
                                                            type: children.type
                                                                .name,
                                                            code: children.code,
                                                            description:
                                                                children.description,
                                                            department:
                                                                children.department,
                                                        })
                                                    ),
                                                })
                                            )
                                    "
                                    :mapQuery="
                                        (search: any) => ({
                                            query: {
                                                name_code: search,
                                                category: true,
                                                type: true,
                                                department: true,
                                            },
                                        })
                                    "
                                    @change="onChangeAccount"
                                    clearable
                                    remote
                                    loading
                                >
                                    <template #customColumn="{ data }">
                                        <AccountColumn :data="data" />
                                    </template>
                                </Select>
                                <div class="text-xs italic">
                                    {{ item.account?.type ?? "None" }},
                                    {{
                                        item.account?.department?.name ?? "None"
                                    }}
                                </div>
                            </div>
                        </td>
                        <td @click="active(index)">
                            <Currency
                                v-if="item.active"
                                v-model="item.debit"
                                :is-valid="isValid(index, 'debit_value')"
                            />
                        </td>
                        <td @click="active(index)">
                            <Currency
                                v-if="item.active"
                                v-model="item.credit"
                                :is-valid="isValid(index, 'credit_value')"
                            />
                        </td>
                        <td @click="active(index)">
                            <Input
                                v-if="item.active"
                                v-model="item.description"
                            />
                        </td>
                        <td @click="active(index)">
                            <div v-if="item.active">
                                <Select
                                    column
                                    custom-column
                                    url="/api/contacts/contacts"
                                    v-model:data="contacts"
                                    v-model:selected="item.contact"
                                    :mapResult="
                                        (result: any) =>
                                            result.data.map((row: any) => ({
                                                id: row.uuid,
                                                value: row.uuid,
                                                label: row.full_name,
                                                type: row.type_label,
                                                id_no: row.id_no,
                                                columns: ['Name', 'type'],
                                                children: row.children?.map(
                                                    (children: any) => ({
                                                        id: children.uuid,
                                                        value: children.uuid,
                                                        label: children.full_name,
                                                        type: children.type_label,
                                                        id_no: children.id_no,
                                                    })
                                                ),
                                            }))
                                    "
                                    :mapQuery="
                                        (search: any) => ({
                                            query: {
                                                name: search,
                                            },
                                        })
                                    "
                                    clearable
                                    remote
                                    loading
                                >
                                    <template #customColumn="{ data }">
                                        <ContactColumn :data="data" />
                                    </template>
                                </Select>
                            </div>
                        </td>
                        <td class="text-center">
                            <KTIcon
                                icon-name="trash"
                                icon-class="fs-2 cursor-pointer"
                                @click="remove(index)"
                                title="Remove row"
                            />
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">
                            <div class="flex justify-between items-center">
                                <div class="flex gap-x-4">
                                    <Button
                                        variant="secondary"
                                        size="sm"
                                        label="Add New Line"
                                        class="!uppercase btn-sm"
                                        @click="addNewLine"
                                    />
                                    <Button
                                        variant="secondary"
                                        size="sm"
                                        label="Clear All Lines"
                                        class="!uppercase btn-sm"
                                        @click="clearLines"
                                    />
                                </div>
                                <span class="font-bold">Total:</span>
                            </div>
                        </td>

                        <td class="text-center">
                            {{ currencyFormat(debits.toString()) }}
                        </td>
                        <td class="text-center">
                            {{ currencyFormat(credits.toString()) }}
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</template>

<script setup lang="ts">
import type { ChartAccount } from "~/types/chart-account";
import AccountColumn from "./account-column.vue";
import ContactColumn from "./contact-column.vue";
import type { JournalEntry, JournalItem } from "~/types/journal-entry";
import Currency from "./currency.vue";
import { isEmpty, toNumber } from "lodash";
import { boolean } from "yup";

interface Props {
    errors: any;
}

const props = defineProps<Props>();
const { send } = usePageEvent();

const chartAccounts = ref([]);
const contacts = ref([]);
const accounts = ref();

const data = defineModel<Partial<JournalEntry>>();

const debits = ref(0.0);
const credits = ref(0.0);

const active = (index: number) => {
    var row = data.value.items[index];

    if (!row.active) {
        row.active = !row.active;
    }

    data.value!.items = [...(data.value?.items ?? [])];

    return row;
};

const remove = (index: number) => {
    delete data.value.items[index];

    data.value.items = data.value.items.filter(boolean);

    refill();

    data.value!.items = [...(data.value?.items ?? [])];
};

const refill = () => {
    for (var i = (data.value?.items ?? []).length - 1; i < 4; i++) {
        data.value.items = (data.value!.items ?? []).concat({
            account: null,
            debit: null,
            credit: null,
            description: null,
            contact: null,
            active: false,
        });
    }
};

const total = (key: string, items?: JournalItem[]): number =>
    (items ?? []).reduce((sum: any, item: any) => {
        if (item[key]) {
            return (
                parseFloat(sum.toString()) +
                parseFloat(item[key].toString().replace(/[^0-9.]/g, ""))
            );
        }

        return sum;
    }, 0);

const onChangeAccount = (value: ChartAccount) => {
    return true;

    const debit = <number>total("debit", data.value?.items ?? []);
    const credit = <number>total("credit", data.value?.items ?? []);

    var amount = debit - credit;
    if (debit < credit) {
        amount = credit - debit;
    }

    data.value?.items?.map((row: JournalItem) => {
        if (debit > credit) {
            if (
                !isEmpty(row.account) &&
                isEmpty(row.credit) &&
                isEmpty(row.debit)
            ) {
                row.credit = amount;
            }
        }
        if (debit < credit) {
            if (
                !isEmpty(row.account) &&
                isEmpty(row.debit) &&
                isEmpty(row.credit)
            ) {
                row.debit = amount;
            }
        }

        return row;
    });

    data.value!.items = [...(data.value?.items ?? [])];
};

const isValid = (index: number, key: string) => {
    return props.errors
        ? (props.errors as any)[`items[${index}].${key}`] == undefined
            ? null
            : false
        : null;
};

const addNewLine = () => {
    send("on:new-line", 1);
};

const clearLines = () => {
    send("on:clear-lines");
};

watch(
    data,
    (value: JournalEntry) => {
        debits.value = total("debit", value?.items ?? []);
        credits.value = total("credit", value?.items ?? []);
    },
    {
        deep: true,
    }
);
</script>
