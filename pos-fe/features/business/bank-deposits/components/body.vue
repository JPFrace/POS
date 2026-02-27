<template>
    <div class="table-responsive">
        <div class="bg-white pb-4">
            <table
                class="table table-bordered table-hover table-rounded border gy-4 gs-4 table-row-gray-300"
            >
                <thead>
                    <tr
                        class="fw-semibold fs-6 text-gray-800 border-bottom-2 border-gray-200"
                    >
                        <th width="3%" class="text-center"></th>
                        <th width="3%" class="text-center">#</th>
                        <th width="18%">RECEIVE FROM</th>
                        <th width="8%">DATE</th>
                        <th width="15%">PAYMENT METHOD</th>
                        <th width="32%">MEMO</th>
                        <th width="8%">REF NO.</th>
                        <th width="10%">AMOUNT</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in transactions?.data ?? []">
                        <td class="align-middle text-center">
                            <Checkbox
                                v-model="item.checked"
                                :id="item.uuid"
                                variant="success"
                                solid
                            />
                        </td>
                        <td class="align-middle text-center">
                            {{ parseInt(index.toString()) + 1 }}
                        </td>
                        <td class="cursor-pointer align-middle text-center">
                            <div
                                class="flex flex-col gap-y-2 justify-baseline items-start"
                            >
                                {{ item.customer_name }}
                            </div>
                        </td>

                        <td class="align-middle text-center">
                            {{ item.date }}
                        </td>
                        <td class="align-middle text-center">
                            {{ item.payment_method.name }}
                        </td>
                        <td class="cursor-pointer align-middle text-left px-2">
                            <Input v-model="item.remarks" />
                        </td>
                        <td class="align-middle text-center">
                            {{ item.ref_no }}
                        </td>
                        <td class="align-middle text-end">
                            {{ currencyFormat(item.actual_receive_amount, 2) }}
                        </td>
                    </tr>

                    <tr
                        v-if="!(transactions?.data ?? []).length"
                        class="border-t-0"
                    >
                        <td colspan="7" class="text-center border-t-0">
                            No Records Found.
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="border-b-0">
                        <td colspan="7" class="text-end border-b-0">TOTAL</td>
                        <td class="font-bold text-end">
                            {{ currencyFormat(total, 2) }}
                        </td>
                    </tr>
                    <tr class="border-t-0">
                        <td colspan="7" class="text-end border-t-0">
                            SELECTED TOTAL
                        </td>
                        <td class="font-bold text-end">
                            {{
                                currencyFormat(
                                    totalSelected(
                                        "actual_receive_amount",
                                        transactions?.data ?? [],
                                    ),
                                    2,
                                )
                            }}
                        </td>
                    </tr>
                </tfoot>
            </table>
            <div class="flex items-center justify-center mt-2">
                <Pagination
                    v-model:current="page"
                    v-model:size="size"
                    :total="totalPages"
                    :sizes="sizes"
                    :hide-on-single-page="true"
                />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { numberOnly } from "../../../../utils/helper";
import type {
    OfficialReceipt,
    OfficialReceiptItem,
} from "~/types/official-receipts";

interface Props {
    errors: any;
}

const props = defineProps<Props>();
const data = defineModel<Partial<OfficialReceipt>>();
const page = ref(1);
const size = ref(10);
const sizes = ref([10, 30, 50, 100]);
const totalPages = ref(0);

const client = useSanctumClient();
const { receive } = usePageEvent();

const {
    data: transactions,
    refresh,
    status,
} = useAsyncData(
    id("official_receipts"),
    () =>
        client("/api/business/official-receipts", {
            method: "GET",
            params: {
                query: {
                    undeposited_money: true,
                    payment_method: true,
                    not_deposit_transit: true,
                },
                page: page.value,
                size: size.value,
            },
        }),
    {
        server: false,
        lazy: true,
        watch: [page, size],
    },
);

const totalSelected = (key: string, items?: OfficialReceiptItem[]): number =>
    (items ?? []).reduce((sum: any, item: any) => {
        if (!item?.checked) {
            return sum;
        }
        if (item[key]) {
            return numberOnly(sum) + numberOnly(item[key]);
        }

        return sum;
    }, 0);

const total = computed(() =>
    (transactions.value?.data ?? []).reduce((sum: any, item: any) => {
        sum += numberOnly(item.actual_receive_amount);

        return sum;
    }, 0),
);

const transaction = (row: OfficialReceipt) => ({
    uuid: row.uuid,
    payment_method: row.payment_method,
    rate: row.actual_receive_amount,
    contactid_no: row.customer_idno,
    memo: row.remarks,
    ref_no: row.ref_no,
    date: row.date,
});

watch(
    transactions,
    (value) => {
        data.value.items = value.data
            .filter((f: OfficialReceiptItem) => f?.checked == true)
            .map(transaction);
    },
    {
        deep: true,
    },
);

watch(status, (value) => {
    if (value == "success") {
        data.value.items = transactions.value.data.map(transaction);
    }
});

onMounted(() => {
    receive("on:create-new", (_value: any) => {
        refresh();
    });
});
</script>
