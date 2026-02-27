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
                v-model="form.description"
                placeholder="..."
                :is-valid="isValid('description')"
                id="description"
            ></Textarea>
        </div>
        <div class="flex gap-x-4 items-center justify-between">
            <div class="flex-1 flex flex-col">
                <span class="text-sm font-semibold mb-2">Parent</span>
                <Select
                    url="/api/products/product-categories"
                    v-model:data="parents"
                    v-model:selected="form.parent"
                    :mapResult="
                        (result: any) =>
                            result.data.map((row: ProductCategories) => ({
                                id: row.uuid,
                                value: row.uuid,
                                label: row.name,
                                children: row.children?.map((children) => ({
                                    id: children.uuid,
                                    value: children.uuid,
                                    label: children.name,
                                    children: parentChildren(
                                        children?.children ?? [],
                                    ),
                                })),
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
                    placeholder="Select..."
                />
            </div>
        </div>
    </form>
</template>

<script lang="ts" setup>
const { yup } = useYup();
import type { ProductCategories } from "~/types/product-categories";
import type { Option } from "~/types/form";

interface Props {
    errors?: object;
    schema: Function;
    form: Function;
    data?: ProductCategories;
}

const props = defineProps<Props>();
const form = ref<Partial<ProductCategories>>({
    name: "",
    description: "",
});

const parents = ref<Option[]>();

const Yup = yup();

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
        parent: value.parent,
    };

    if (value.parent) {
        parents.value = [
            {
                id: value.parent.uuid,
                value: value.parent.uuid,
                label: value.parent.name,
            } as Option,
        ];

        form.value.parent = parents.value.filter(
            (d) => d.id == value.parent.uuid,
        )[0] as Option;
    }
};

const parentChildren = (children: any) => {
    return children.map((row: ProductCategories) => ({
        id: row.uuid,
        value: row.uuid,
        label: row.name,
        children: parentChildren(row?.children ?? []),
    }));
};

watch(
    form,
    (value) => {
        props.form(value);
    },
    {
        deep: true,
    },
);

onMounted(() => {
    if (props.data) {
        setForm(props.data);
    }

    props.schema(
        Yup.object().shape({
            name: Yup.string().required(),
            description: Yup.string().notRequired(),
        }),
    );
});
</script>
