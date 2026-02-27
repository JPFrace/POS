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
                        <th width="25%">ITEM</th>
                        <th width="8%">QUANTITY</th>
                        <th width="12%">AMOUNT</th>
                        <th width="12%">TAX RATE</th>
                        <th width="8%">SUB TOTAL</th>
                        <th width="18%">NAME</th>
                        <th width="19%">DESCRIPTION</th>
                        <th width="3%" class="text-center"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in data!.items">
                        <td class="align-middle text-center">
                            <KTIcon
                                icon-name="abstract-30"
                                icon-class="fs-2 cursor-pointer"
                            />
                        </td>
                        <td class="align-middle text-center">
                            {{ parseInt(index.toString()) + 1 }}
                        </td>
                        <td
                            class="cursor-pointer align-middle text-start"
                            @click="productActive(index)"
                        >
                            <div
                                class="flex flex-col gap-y-2 justify-baseline items-start"
                            >
                                <Select
                                    v-if="item.product_active"
                                    ref="accounts"
                                    column
                                    custom-column
                                    url="/api/products/products"
                                    v-model:data="products"
                                    v-model:selected="item.product"
                                    :mapResult="
                                        (result: any) =>
                                            result.data.map((row: Product) => ({
                                                id: row.uuid,
                                                value: row.uuid,
                                                label: `${row.sku} - ${row.name}`,
                                                sku: row.sku,
                                                category: row.category,
                                                description: row.description,
                                                name: row.name,
                                                price: row.price,
                                                income_account:
                                                    row.income_account,
                                                columns: [
                                                    'name',
                                                    'chart of account',
                                                ],
                                            }))
                                    "
                                    :mapQuery="
                                        (search: any) => ({
                                            query: {
                                                name_sku: search,
                                                category: true,
                                                income_account: true,
                                            },
                                        })
                                    "
                                    @change="onChangeAccount"
                                    clearable
                                    remote
                                    loading
                                >
                                    <template #customColumn="{ data }">
                                        <ProductColumn :data="data" />
                                    </template>
                                </Select>
                                <div
                                    v-if="item.product && !item.product_active"
                                    class="flex flex-col gap-y-1 items-start justify-start"
                                >
                                    <span
                                        ><span
                                            class="text-blue-400 font-bold"
                                            >{{ item.product.sku }}</span
                                        >#{{ item.product.name }}</span
                                    >
                                    <span class="text-slate-400 text-xs italic"
                                        >{{
                                            item.product?.income_account?.code
                                        }}
                                        -
                                        {{
                                            item.product?.income_account?.name
                                        }}</span
                                    >
                                </div>
                            </div>
                        </td>
                        <td
                            @click="active(index)"
                            class="align-middle text-center"
                        >
                            <Currency
                                class="text-center"
                                v-if="item.active"
                                v-model="item.quantity"
                                :is-valid="isValid(index, 'quantity')"
                            />
                        </td>
                        <td
                            @click="active(index)"
                            class="align-middle text-center"
                        >
                            <Currency
                                class="text-center"
                                v-if="item.active"
                                v-model="item.rate"
                                :is-valid="isValid(index, 'rate')"
                            />
                        </td>
                        <td
                            @click="active(index)"
                            class="align-middle text-center"
                        >
                            <Currency
                                class="text-center"
                                v-if="item.active"
                                v-model="item.tax_rate"
                                :is-valid="isValid(index, 'tax_rate')"
                            />
                        </td>
                        <td
                            @click="active(index)"
                            class="align-middle text-center"
                        >
                            {{
                                money(
                                    numberOnly(item.quantity ?? 0.0) *
                                        numberOnly(item.rate ?? 0.0) +
                                        numberOnly(item.tax_rate ?? 0.0),
                                    2
                                )
                            }}
                        </td>
                        <td
                            @click="active(index)"
                            class="align-middle text-center"
                        >
                            <Input
                                v-if="item.active"
                                v-model="item.product_name"
                            />
                        </td>
                        <td
                            @click="active(index)"
                            class="align-middle text-center"
                        >
                            <Input
                                v-if="item.active"
                                v-model="item.product_description"
                            />
                        </td>
                        <td class="align-middle text-center">
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

                        <td class="align-middle text-center">
                            {{ money(quantities) }}
                        </td>
                        <td class="align-middle text-center">
                            {{ money(rates, 2) }}
                        </td>
                        <td class="align-middle text-center">
                            {{ money(taxes, 2) }}
                        </td>
                        <td class="align-middle text-center font-bold">
                            {{ money(subTotal, 2) }}
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
import ProductColumn from "./product-column.vue";
import type { Invoice, InvoiceItem } from "~/types/invoice";
import { boolean } from "yup";
import type { Product } from "~/types/products";

interface Props {
    errors: any;
}

const props = defineProps<Props>();
const { send } = usePageEvent();

const chartAccounts = ref([]);
const contacts = ref([]);
const accounts = ref();
const products = ref();

const data = defineModel<Partial<Invoice>>();

const quantities = ref(0.0);
const rates = ref(0.0);
const taxes = ref(0.0);
const totals = ref(0.0);

const active = (index: number) => {
    var row = data.value.items[index];

    if (!row.active) {
        row.active = !row.active;
    }

    data.value!.items = [...(data.value?.items ?? [])];

    return row;
};

const productActive = (index: number) => {
    var row = data.value.items[index];

    if (!row.active) {
        row.active = !row.active;
    }

    row.product_active = !row.product_active;

    data.value!.items = [...(data.value?.items ?? [])];

    return row;
};

const remove = (index: number) => {
    delete data.value!.items[index];

    data.value!.items = data.value!.items?.filter(boolean);

    refill();

    data.value!.items = [...(data.value?.items ?? [])];
};

const total = (key: string, items?: InvoiceItem[]): number =>
    (items ?? []).reduce((sum: any, item: any) => {
        if (item[key]) {
            return numberOnly(sum) + numberOnly(item[key]);
        }

        return sum;
    }, 0);

const subTotal = computed(() =>
    (data.value?.items ?? []).reduce((sum: any, item: any) => {
        sum +=
            numberOnly(item.quantity ?? 0.0) * numberOnly(item.rate ?? 0.0) +
            numberOnly(item.tax_rate ?? 0.0);

        return sum;
    }, 0)
);

const refill = () => {
    for (var i = (data.value?.items ?? []).length - 1; i < 3; i++) {
        data.value!.items = (
            (data.value!.items ?? []) as unknown as InvoiceItem[]
        ).concat({
            product: null,
            rate: 0,
            tax_rate: 0,
            quantity: 0,
            product_name: null,
            product_description: null,
            active: false,
        });
    }
};

const onChangeAccount = (value: any) => {
    data.value?.items?.map((row: InvoiceItem) => {
        if (row.product && value.value == row.product.value) {
            row.product_active = false;
            row.quantity = 1;
            row.rate = money(row.product.price ?? 0.0, 2);
            row.tax_rate = 0;
            row.product_name = row.product.name;
            row.product_description = row.product.description;
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
    (value: Invoice) => {
        quantities.value = total("quantity", value?.items ?? []);
        rates.value = total("rate", value?.items ?? []);
        taxes.value = total("tax_rate", value?.items ?? []);
    },
    {
        deep: true,
    }
);
</script>
