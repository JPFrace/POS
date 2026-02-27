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
            ></Textarea>
        </div>
        <Checkbox
            v-model="form.active"
            label="Active"
            :is-valid="isValid('active')"
            size="sm"
        />
    </form>
</template>

<script lang="ts" setup>
import type { Access } from "~/types/access";

const { yup } = useYup();

interface Props {
    errors?: object;
    schema: Function;
    form: Function;
    data?: Access;
}

const props = defineProps<Props>();

const form = ref<Access>({
    name: "",
    description: "",
    active: true,
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
        name: value.name ?? "",
        description: value.description ?? "",
        active: value.active ?? true,
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
            active: Yup.boolean().notRequired().nullable(),
        })
    );
});
</script>
