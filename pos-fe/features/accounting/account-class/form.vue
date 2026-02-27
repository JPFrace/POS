<template>
    <form class="space-y-4 py-1 px-1">
        <div class="flex-1 flex flex-col">
            <span class="text-sm font-semibold mb-2">Code</span>
            <Input
                v-model="form.code"
                placeholder="Enter Code"
                :is-valid="isValid('code')"
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
        <div class="flex-1 flex flex-col">
            <span class="text-sm font-semibold mb-2"> Short Name</span>
            <Input
                v-model="form.short_name"
                placeholder="Enter Short Name"
                :is-valid="isValid('short_name')"
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
        <Checkbox
            v-model="form.is_inactive"
            label="Inactive"
            :is-valid="isValid('is_inactive')"
            size="sm"
        />
    </form>
</template>

<script lang="ts" setup>
const { yup } = useYup();
import type { AccountClass } from "~/types/account-class";

interface Props {
    errors?: object;
    schema: Function;
    form: Function;
    data?: AccountClass;
}

const props = defineProps<Props>();

const form = ref<AccountClass>({
    code: "",
    name: "",
    short_name: "",
    description: "",
    is_inactive: false,
});

const Yup = yup();

const isValid = (key: string) =>
    props.errors
        ? Object.keys(props.errors).includes(key)
            ? (props?.errors as any)[key]?.length <= 0
            : null
        : null;

const setForm = (value: any) => {
    form.value = {
        code: value.code ?? "",
        name: value.name ?? "",
        short_name: value.short_name ?? "",
        description: value.description ?? "",
        is_inactive: value.is_inactive ?? true,
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

    props.schema(
        Yup.object().shape({
            code: Yup.string().required(),
            name: Yup.string().required(),
            short_name: Yup.string().notRequired(),
            description: Yup.string().notRequired(),
            is_inactive: Yup.boolean().notRequired().nullable(),
        })
    );
});
</script>
