<template>
    <form class="space-y-4 py-1 px-1">
        <template v-if="activeTab === 'general'">
            <ProductImage
                ref="imageUploader"
                @file-change="formData.file = $event"
            />
            <ProductDetails v-model:form="formData" :errors />
            <ProductAccounts v-model:form="formData" :errors />
            <ProductPurchase v-model:form="formData" :errors />
        </template>

        <template v-else-if="activeTab === 'tax-settings'">
            <TaxSettings v-model:form="formData" :errors />
        </template>
    </form>
</template>

<script lang="ts" setup>
import type { Product } from "~/types/product";
import type { Option } from "~/types/form";
import ProductImage from "./components/product-image.vue";
import ProductDetails from "./components/product-details.vue";
import ProductAccounts from "./components/product-accounts.vue";
import ProductPurchase from "./components/product-purchase.vue";
import TaxSettings from "./components/tax-settings.vue";

const { yup } = useYup();

interface Props {
    errors?: object;
    schema: Function;
    form: Function;
    data?: Product;
    activeTab?: string;
}
const formData = ref<Partial<Product>>({
    file: null,
    sku: "",
    name: "",
    description: "",
    category: null,
    price: null,
    income: null,
    receivable: null,
    depository: null,
    purchase_description: "",
    expense: null,
    cost: null,
    payable: null,
    vendor: null,
    sales_tax: null,
    withholding_tax: null,
});

const props = defineProps<Props>();
const Yup = yup();

const imageUploader = ref();

const setForm = (value: any) => {
    formData.value = {
        sku: value.sku ?? "",
        name: value.name ?? "",
        description: value.description ?? "",
        purchase_description: value.purchase_description ?? "",
        price: money(value.price),
        cost: money(value.cost),
        file: value.file ?? "",
        category: value.category
            ? ({
                  id: value.category.uuid,
                  value: value.category.uuid,
                  label: value.category.name,
              } as Option)
            : null,
        income: value.income
            ? ({
                  id: value.income.uuid,
                  value: value.income.uuid,
                  label: value.income.name,
              } as Option)
            : null,
        receivable: value.receivable_account
            ? ({
                  id: value.receivable_account.uuid,
                  value: value.receivable_account.uuid,
                  label: value.receivable_account.name,
              } as Option)
            : null,
        depository: value.depository
            ? ({
                  id: value.depository.uuid,
                  value: value.depository.uuid,
                  label: value.depository.name,
              } as Option)
            : null,
        payable: value.payable
            ? ({
                  id: value.payable.uuid,
                  value: value.payable.uuid,
                  label: value.payable.name,
              } as Option)
            : null,
        expense: value.expense
            ? ({
                  id: value.expense.uuid,
                  value: value.expense.uuid,
                  label: value.expense.name,
              } as Option)
            : null,
        vendor: value.vendor
            ? ({
                  id: value.vendor.uuid,
                  value: value.vendor.uuid,
                  label: value.vendor.name,
              } as Option)
            : null,
        sales_tax: value.sales_tax
            ? ({
                  id: value.sales_tax.uuid,
                  value: value.sales_tax.uuid,
                  label: `${value.sales_tax.rate}% ${value.sales_tax.code}`,
              } as Option)
            : null,
        withholding_tax: value.withholding_tax
            ? ({
                  id: value.withholding_tax.uuid,
                  value: value.withholding_tax.uuid,
                  label: `${value.withholding_tax.rate}% ${value.withholding_tax.code}`,
              } as Option)
            : null,
    };

    if (value.file) {
        nextTick(() => {
            imageUploader.value?.setInitialFile(
                value.file.url,
                value.file.name,
            );
        });
    }
};

watch(
    formData,
    (value) => {
        props.form(value);
    },
    { deep: true },
);

onMounted(() => {
    if (props.data) setForm(props.data);

    props.schema(
        Yup.object().shape({
            sku: Yup.string().required(),
            name: Yup.string().required(),
            description: Yup.string().notRequired(),
            purchase_description: Yup.string().notRequired(),
            price: Yup.number()
                .transform((value, originalValue) =>
                    originalValue === "" ? null : value,
                )
                .nullable()
                .min(0),
            cost: Yup.number()
                .transform((value, originalValue) =>
                    originalValue === "" ? null : value,
                )
                .nullable()
                .min(0),
            income: Yup.object().required(),
            receivable: Yup.object().notRequired(),
            expense: Yup.object().notRequired(),
            category: Yup.object().notRequired(),
            depository: Yup.object().notRequired(),
            payable: Yup.object().notRequired(),
            vendor: Yup.object().notRequired(),
            sales_tax: Yup.object().notRequired(),
            withholding_tax: Yup.object().notRequired(),
        }),
    );
});
</script>
