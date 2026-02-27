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
        <div class="flex-1 flex flex-col">
            <span class="text-sm font-semibold mb-2">Category</span>
            <Select
                v-model:data="categories"
                v-model:selected="form.category"
                method="GET"
                url="/api/accounting/account-categories"
                :mapResult="
                    (result: any) =>
                        result.data.map((row: AccountCategory) => ({
                            id: row.uuid,
                            value: row.uuid,
                            label: row.name,
                        }))
                "
                :mapQuery="(query: any) => ({ name: query })"
                clearable
                remote
                loading
                placeholder="Select..."
                :is-valid="isValid('category')"
            />
        </div>
    </form>
</template>

<script lang="ts" setup>
const { yup } = useYup();
import type { AccountCategory } from "~/types/account-category";
import type { AccountType } from "~/types/account-types";
import type { Option } from "~/types/form";

interface Props {
    errors?: object;
    schema: Function;
    form: Function;
    data?: AccountType;
}

const route = useRoute();
const props = defineProps<Props>();

const form = ref<Partial<AccountType>>({
    name: "",
    description: "",
    is_inactive: false,
    category: null,
    seq: 0,
});

const Yup = yup();
const categories = ref<Option[]>([]);

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
        is_inactive: value.is_inactive ?? true,
        category: value.category,
    };

    if (value.category) {
        categories.value = [
            {
                id: value.category.uuid,
                value: value.category.uuid,
                label: value.category.name,
            } as Option,
        ];

        form.value.category = categories.value.filter(
            (d) => d.id == value.category.uuid
        )[0] as Option;
    }
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

    props.schema(
        Yup.object().shape({
            name: Yup.string().required(),
            description: Yup.string().notRequired(),
            is_inactive: Yup.boolean().notRequired().nullable(),
            category: Yup.object().required(),
        })
    );
});
</script>
