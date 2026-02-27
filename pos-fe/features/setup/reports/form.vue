<template>
    <form class="space-y-4 py-1 px-1">
        <Input
            v-model="form.name"
            float
            label="Name"
            placeholder="Name"
            :is-valid="isValid('name')"
        />
        <Input
            v-model="form.description"
            float
            label="Description"
            placeholder="Description"
            :is-valid="isValid('description')"
        />
        <Checkbox
            v-model="form.is_inactive"
            label="Inactive"
            :is-valid="isValid('is_inactive')"
        />
    </form>
</template>

<script lang="ts" setup>
const { yup } = useYup();

import type { Reports } from "~/types/reports";

interface Props {
    errors?: object;
    schema: Function;
    form: Function;
    data?: Reports;
}

const user = useSanctumUser();

const props = defineProps<Props>();
const form = ref<Reports>({
    name: "",
    description: "",
    is_inactive: false,
});

const Yup = yup();

const isValid = (key: string) =>
    props.errors
        ? Object.keys(props.errors).includes(key)
            ? props?.errors[key]?.length <= 0
            : null
        : null;

const setForm = (value: any) => {
    form.value = {
        name: value.name || "",
        description: value.description || "",
        is_inactive: value.is_inactive || false,
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
            name: Yup.string().required(),
            description: Yup.string().notRequired(),
            is_inactive: Yup.boolean().notRequired().nullable(),
        })
    );
});
</script>
