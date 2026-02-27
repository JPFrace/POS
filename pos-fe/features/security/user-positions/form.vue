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
            <span class="text-sm font-semibold mb-2">Title</span>
            <Input
                v-model="form.title"
                placeholder="Enter Title"
                :is-valid="isValid('title')"
            />
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
import type { UserPosition } from "~/types/user-position";

interface Props {
    errors?: object;
    schema: Function;
    form: Function;
    data?: UserPosition;
}

const props = defineProps<Props>();

const form = ref<UserPosition>({
    code: "",
    title: "",
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
        title: value.title ?? "",
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
            title: Yup.string().required(),
            is_inactive: Yup.boolean().notRequired().nullable(),
        })
    );
});
</script>
