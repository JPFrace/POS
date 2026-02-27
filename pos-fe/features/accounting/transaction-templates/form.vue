<template>
    <form class="space-y-4 py-1 px-1">
        <div class="flex-1 flex flex-col">
            <span class="text-sm font-semibold mb-2">Name</span>
            <Input
                v-model="form.name"
                placeholder="Enter Name"
                :is-valid="isValid('name')"
            />
        </div>
        <div class="flex-1 flex flex-col">
            <span class="text-sm font-semibold mb-2">Description</span>
            <Textarea
                id="description"
                v-model="form.description"
                placeholder="..."
                :is-valid="isValid('description')"
            />
        </div>
        <Checkbox
            v-model="form.is_inactive"
            label="Inactive"
            :is-valid="isValid('is_inactive')"
            size="sm"
        />
        <div
            v-for="(detail, index) in form.details"
            :key="index"
            :ref="(el) => (productCards[index] = el)"
        >
            <el-card class="mt-4" shadow="hover">
                <div class="flex items-center justify-between mb-4">
                    <span class="font-semibold text-gray-600">
                        Product {{ index + 1 }}
                    </span>
                    <el-icon
                        v-if="(form.details?.length ?? 0) > 1"
                        class="!text-red-500 hover:!text-red-700 cursor-pointer mb-1"
                        :size="17"
                        @click="removeProduct(index)"
                        ><Delete
                    /></el-icon>
                </div>
                <div class="grid grid-cols-[1fr_auto_auto] gap-4 items-end">
                    <div class="flex flex-col">
                        <span
                            class="text-sm font-semibold mb-2 flex items-center gap-2"
                        >
                            <el-icon class="mb-1" :size="15"
                                ><ShoppingCart
                            /></el-icon>
                            Product
                        </span>
                        <Select
                            v-model="detail.product"
                            url="/api/products/products"
                            :map-result="mapProducts"
                            :map-query="mapQueryNameSku"
                            remote
                            loading
                            column
                            custom-column
                            placeholder="Select..."
                            :is-valid="isValid(`details.${index}.product`)"
                        >
                            <template #customColumn="{ data: items }">
                                <ProductColumn :data="items" />
                            </template>
                        </Select>
                    </div>

                    <div class="flex flex-col w-28">
                        <span class="text-sm font-semibold mb-2">Quantity</span>
                        <Input
                            v-model="detail.quantity"
                            type="number"
                            :is-valid="isValid(`details.${index}.quantity`)"
                        />
                    </div>

                    <div class="flex flex-col w-32">
                        <span class="text-sm font-semibold mb-2">Amount</span>
                        <Input
                            v-model="detail.amount"
                            type="number"
                            placeholder="0.00"
                            :is-valid="isValid(`details.${index}.amount`)"
                        />
                    </div>
                </div>
            </el-card>
        </div>
        <Button
            type="button"
            variant="secondary"
            label="Secondary"
            class="w-full mt-1"
            @click="
                (event: any) => {
                    addProduct();
                    (event.currentTarget as HTMLElement)?.blur();
                }
            "
        >
            <KTIcon icon-name="plus" icon-class="fs-3 text-black" />
            Add Another Product
        </Button>
    </form>
</template>

<script lang="ts" setup>
import type { Product } from "~/types/product";
import ProductColumn from "./components/product-column.vue";
import type { TransactionTemplate } from "~/types/transaction-template";
import { ShoppingCart, Delete } from "@element-plus/icons-vue";

const { yup } = useYup();
const Yup = yup();
const { receive } = usePageEvent();

interface Props {
    errors?: object;
    schema: Function;
    form: Function;
    data?: TransactionTemplate;
}

const productCards = ref<Array<HTMLElement | null>>([]);

const props = defineProps<Props>();

const defaultForm: Partial<TransactionTemplate> = {
    name: "",
    description: "",
    is_inactive: false,
    details: [
        {
            product: null,
            quantity: 1,
            amount: null,
        },
    ],
};

const form = ref<Partial<TransactionTemplate>>({ ...defaultForm });

const addProduct = async () => {
    form.value.details?.push({
        product: null,
        quantity: 1,
        amount: null,
    });

    await nextTick();

    const el = productCards.value.at(-1);
    if (el) {
        el.scrollIntoView({
            behavior: "smooth",
            block: "start",
        });
    }
};

const removeProduct = (index: number) => {
    form.value.details?.splice(index, 1);
};

const mapProducts = (res: any) =>
    res.data.map((row: Product) => ({
        id: row.uuid,
        value: row.uuid,
        label: `${row.sku} — ${row.name}`,
        sku: row.sku,
        name: row.name,
        income_account: row.income,
    }));

const mapQueryNameSku = (search: any) => ({
    query: { name_sku: search, income: true },
});

const isValid = (key: string) =>
    props.errors
        ? Object.keys(props.errors).includes(key)
            ? (props?.errors as any)[key]?.length <= 0
            : null
        : null;

const setForm = (value: any) => {
    form.value = {
        name: value.name ?? "",
        description: value.description ?? "",
        is_inactive: value.is_inactive ?? false,
        details:
            Array.isArray(value.details) && value.details.length > 0
                ? value.details.map((detail: any) => ({
                      product: detail.product
                          ? {
                                id: detail.product.uuid,
                                value: detail.product.uuid,
                                label: `${detail.product.sku} — ${detail.product.name}`,
                                sku: detail.product.sku,
                                name: detail.product.name,
                                income_account: detail.product.income_account,
                            }
                          : null,
                      quantity: detail.quantity ?? 1,
                      amount: detail.amount ?? null,
                  }))
                : [
                      {
                          product: null,
                          quantity: 1,
                          amount: null,
                      },
                  ],
    };
};

watch(
    form,
    (value) => {
        props.form(value);
    },
    {
        deep: true,
    }
);

onMounted(() => {
    if (props.data) {
        setForm(props.data);
    }

    receive("clear", () => {
        form.value = {
            ...defaultForm,
            details: [
                {
                    product: null,
                    quantity: 1,
                    amount: null,
                },
            ],
        };
    });

    props.schema(
        Yup.object().shape({
            name: Yup.string().required(),
            description: Yup.string().notRequired(),
            is_inactive: Yup.boolean().nullable(),
            details: Yup.array()
                .of(
                    Yup.object().shape({
                        product: Yup.object().required(),
                        quantity: Yup.number().required().min(1).integer(),
                        amount: Yup.number()
                            .transform((value, originalValue) =>
                                originalValue === "" ? null : value
                            )
                            .nullable()
                            .min(0),
                    })
                )
                .min(1),
        })
    );
});
</script>
