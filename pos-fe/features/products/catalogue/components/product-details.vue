<template>
    <div class="space-y-4">
        <div class="flex gap-x-4 items-start justify-between">
            <div class="w-2/5 flex flex-col">
                <span class="text-sm font-semibold mb-2">SKU</span>
                <Input
                    v-model="form.sku"
                    placeholder="Enter SKU"
                    :is-valid="isValid('sku')"
                />
            </div>
            <div class="flex-1 flex flex-col">
                <span class="text-sm font-semibold mb-2">Name</span>
                <Input
                    v-model="form.name"
                    placeholder="Enter Name"
                    :is-valid="isValid('name')"
                />
            </div>
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

        <div class="flex gap-x-4 items-center justify-between">
            <div class="flex-2 flex flex-col">
                <span class="text-sm font-semibold mb-2">Category</span>
                <Select
                    v-model:data="categories"
                    v-model:selected="form.category"
                    url="/api/products/product-categories"
                    :map-result="mapCategories"
                    :map-query="mapQueryName"
                    clearable
                    remote
                    loading
                    placeholder="Select..."
                    :is-valid="isValid('category')"
                />
            </div>

            <div class="flex-[1.1] flex flex-col">
                <span class="text-sm font-semibold mb-2">Price</span>
                <Currency
                    v-model="form.price"
                    float
                    label="Price"
                    placeholder="Enter Price"
                    :is-valid="isValid('price')"
                />
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import type { ProductCategories } from "~/types/product-categories";
import type { Option } from "~/types/form";

const form = defineModel<Record<string, any>>("form", { required: true });

const props = defineProps<{
    errors?: object;
}>();

const { isValid } = useFormErrors(() => props.errors);

const categories = ref<Option[]>([]);

const mapCategories = (res: any) =>
    res.data.map((row: ProductCategories) => ({
        id: row.uuid,
        value: row.uuid,
        label: row.name,
    }));

const mapQueryName = (search: any) => ({
    query: { name: search },
});
</script>
