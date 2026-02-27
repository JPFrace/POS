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
            <th width="18%">ITEM</th>
            <th width="8%">QUANTITY</th>
            <th width="10%">AMOUNT</th>
            <th width="10%">TAX</th>
            <th width="18%">CONTACT</th>
            <th width="8%">SUB TOTAL</th>
            <th width="15%">NAME</th>
            <th width="15%">DESCRIPTION</th>
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
              class="cursor-pointer align-middle text-center"
              @click="productActive(index)"
            >
              <div class="flex flex-col gap-y-2 justify-baseline items-start">
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
                        expense_account: row.expense_account,
                        payable_account: row.payable_account,
                        columns: ['name', 'chart of account'],
                        withholding_tax: row.withholding_tax,
                      }))
                  "
                  :mapQuery="
                    (search: any) => ({
                      query: {
                        name_sku: search,
                        category: true,
                        expense_account: true,
                        payable: true,
                        withholding_tax: true,
                        sales_tax: true,
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
                  class="flex flex-col gap-y-1 items-start justify-center"
                >
                  <span
                    ><span class="text-blue-400 font-bold">{{
                      item.product.sku
                    }}</span
                    >#{{ item.product.name }}</span
                  >
                  <span class="text-slate-400 text-xs italic"
                    >{{ item.product?.expense_account?.code }}
                    -
                    {{ item.product?.expense_account?.name }}</span
                  >
                </div>
              </div>
            </td>
            <td @click="active(index)" class="align-middle text-center">
              <Currency
                class="text-center"
                v-if="item.active"
                v-model="item.quantity"
                :is-valid="isValid(index, 'quantity')"
                @change="
                  ($event: any) =>
                    calculateTax(
                      item,
                      item.product?.withholding_tax,
                      numberOnly(item?.rate ?? 1) *
                        numberOnly($event.target.value),
                    )
                "
              />
            </td>
            <td @click="active(index)" class="align-middle text-center">
              <Currency
                class="text-center"
                v-if="item.active"
                v-model="item.rate"
                :is-valid="isValid(index, 'rate')"
                :allow-negative="true"
                @change="
                  ($event: any) =>
                    calculateTax(
                      item,
                      item.product?.withholding_tax,
                      (item?.quantity ?? 0) * numberOnly($event.target.value),
                    )
                "
              />
            </td>
            <td
              @click="active(index)"
              class="align-middle text-center"
              :title="
                item.product?.withholding_tax
                  ? `Withholding Tax Rate Applied: ${item.product?.withholding_tax.code} ${item.product?.withholding_tax?.rate_label ?? 0}`
                  : 'No Withholding Tax Rate'
              "
            >
              <Currency
                class="text-center"
                v-if="item.active"
                v-model="item.withholding_tax_rate"
                :is-valid="isValid(index, 'withholding_tax_rate')"
              />
            </td>
            <td
              class="cursor-pointer align-middle text-left px-2"
              @click="contactActive(index)"
            >
              <div class="flex flex-col gap-y-2">
                <Select
                  v-if="item.sub_contact_active"
                  ref="contacts"
                  column
                  custom-column
                  url="/api/contacts/contacts"
                  v-model:data="contacts"
                  v-model:selected="item!.sub_contact"
                  :mapResult="
                    (result: any) =>
                      result.data.map((row: any) => ({
                        id: row.uuid,
                        value: row.uuid,
                        label: row.full_name,
                        type: row.type_label,
                        id_no: row.id_no,
                        email: row.email,
                        columns: ['Name', 'type'],
                      }))
                  "
                  :mapQuery="
                    (search: any) => ({
                      query: { name: search },
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

                <div
                  v-if="item.sub_contact && !item.sub_contact_active"
                  class="flex flex-col gap-y-1"
                >
                  <span>
                    <span class="text-blue-400 font-bold">
                      {{ item.sub_contact.id_no }}
                    </span>
                    #{{ item.sub_contact.label }}
                  </span>
                  <span class="text-slate-400 text-xs italic">
                    {{ item.sub_contact.type }}
                  </span>
                </div>
              </div>
            </td>
            <td @click="active(index)" class="align-middle text-center">
              {{
                money(
                  numberOnly(item.quantity) * numberOnly(item.rate) -
                    numberOnly(item.withholding_tax_rate ?? 0.0),
                  2,
                )
              }}
            </td>
            <td @click="active(index)" class="align-middle text-center">
              <Input v-if="item.active" v-model="item.product_name" />
            </td>
            <td @click="active(index)" class="align-middle text-center">
              <Input v-if="item.active" v-model="item.product_description" />
            </td>
            <td class="align-middle text-center space-x-2">
              <KTIcon
                icon-name="trash"
                icon-class="fs-2x cursor-pointer !text-red-400"
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
                <span class="font-bold italic ml-4"> TOTAL:</span>
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
            <td></td>
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
import type { Payment, PaymentItem } from "~/types/payment";
import { boolean, number } from "yup";
import type { Product } from "~/types/products";
import type { Tax } from "~/types/Tax";
import { numberOnly } from "../../../../utils/helper";
import ContactColumn from "./contact-column.vue";
import PreviewJournals from "../actions/preview-journals.vue";
import type { Bill, BillItem } from "~/types/bill";

interface Props {
  errors: any;
}

const props = defineProps<Props>();
const { send, receive } = usePageEvent();

const products = ref();
const contacts = ref();

const data = defineModel<Partial<Payment>>();

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

const contactActive = (index: number) => {
  var row = data.value.items[index];

  if (!row.active) {
    row.active = !row.active;
  }

  row.sub_contact_active = !row.sub_contact_active;

  data.value!.items = [...(data.value?.items ?? [])];

  return row;
};

const remove = (index: number) => {
  delete data.value!.items[index];

  data.value!.items = data.value!.items?.filter(boolean);

  refill();

  data.value!.items = [...(data.value?.items ?? [])];
};

const total = (key: string, items?: PaymentItem[]): number =>
  (items ?? []).reduce((sum: any, item: any) => {
    if (item[key]) {
      return numberOnly(sum) + numberOnly(item[key]);
    }

    return sum;
  }, 0);

const subTotal = computed(() =>
  (data.value?.items ?? []).reduce((sum: any, item: any) => {
    sum +=
      numberOnly(item.quantity) * numberOnly(item.rate) -
      numberOnly(item.withholding_tax_rate);

    return sum;
  }, 0),
);

const refill = () => {
  for (var i = (data.value?.items ?? []).length - 1; i < 3; i++) {
    data.value!.items = (
      (data.value!.items ?? []) as unknown as PaymentItem[]
    ).concat({
      uuid: uuid(),
      product: null,
      rate: 0,
      withholding_tax_rate: 0,
      quantity: 0,
      product_name: null,
      product_description: null,
      sub_contact: null,
      sub_contact_active: false,
      active: false,
    });
  }
};

const onChangeAccount = (value: any) => {
  data.value?.items?.map((row: PaymentItem) => {
    if (row.product && value.value == row.product.value) {
      row.product_active = false;
      row.quantity = 1;
      row.withholding_tax_rate = row.product.withholding_tax?.rate ?? 0;
      row.rate = row.product.price;
      row.product_name = row.product.name;
      row.product_description = row.product.description;
      row.sub_contact = null as any;
      row.sub_contact_active = false;

      calculateTax(row, row.product.withholding_tax, row.rate * row.quantity);
    }

    return row;
  });

  data.value!.items = [...(data.value?.items ?? [])];
};

const calculateTax = (item: PaymentItem, tax: Tax, amount: number) => {
  item.withholding_tax_rate = calculateTaxRate(tax, amount);
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
  (value: Payment) => {
    quantities.value = total("quantity", value?.items ?? []);
    rates.value = total("rate", value?.items ?? []);
    taxes.value = total("withholding_tax_rate", value?.items ?? []);
  },
  {
    deep: true,
  },
);

onMounted(() => {
  receive("add:bill-items", (bills: Bill[]) => {
    data.value!.items = [];

    var items = [];

    for (var bill of bills ?? []) {
      for (var item of bill?.details ?? ([] as BillItem[])) {
        items.push({
          uuid: uuid(),
          product: {
            ...item.product,
            id: item.product?.uuid,
            value: item.product?.uuid,
            label: `${item.product?.sku}#${item.product?.name}`,
          },
          rate: item.rate,
          quantity: item.quantity ?? 0,
          product_name: item.product_name ?? "",
          product_description: item.product_description ?? "",
          withholding_tax_rate: calculateTaxRate(
            item.product?.withholding_tax,
            item.rate * item.quantity,
          ),
          active: true,
          product_active: false,
          sub_contact_active: false,
        });
      }
    }

    data.value!.items = [...(data.value?.items ?? []).concat(items)];

    if (!bills?.length) {
      data.value!.remarks = null;
    } else {
      data.value!.remarks =
        "Payment to " + (data.value?.payee_name ?? "") + " for " + (bills[0]?.remarks ?? "") + " (Bill #" + (bills[0]?.bill_no ?? "") + ")";
      }

      refill();
      return items;
    },
  );
});
</script>
