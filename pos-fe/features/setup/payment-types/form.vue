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
            <span class="text-sm font-semibold mb-2">Code</span>
            <Input
                v-model="form.code"
                placeholder="Enter Code"
                :is-valid="isValid('code')"
            />
        </div>
        <div class="flex-1 flex flex-col">
            <span class="text-sm font-semibold mb-2">Short Description</span>
            <Input
                v-model="form.short_desc"
                placeholder="..."
                :is-valid="isValid('short_desc')"
            />
        </div>
        <div class="flex-1 flex flex-col">
            <span class="text-sm font-semibold mb-2">Description</span>
            <Textarea
                v-model="form.description"
                placeholder="..."
                :is-valid="isValid('description')"
                id="description"
            >
            </Textarea>
        </div>
        <Checkbox
            v-model="form.inactive"
            label="Inactive"
            :is-valid="isValid('inactive')"
            size="sm"
        />
    </form>
</template>

<script lang="ts" setup>
import { watch, ref, onMounted } from "vue";
import type { PaymentTypes } from "~/types/payment-types";
const { yup } = useYup();

interface Props {
    errors?: object;
    schema: Function;
    form: Function;
    data?: PaymentTypes;
    resetKey?: number;
}

const props = defineProps<Props>();

const defaultForm = (): PaymentTypes => ({
    name: "",
    code: "",
    description: "",
    short_desc: "",
    inactive: true,
});

const form = ref<PaymentTypes>(defaultForm());

const Yup = yup();

const isValid = (key: string) =>
    props.errors
        ? Object.keys(props.errors).includes(key)
            ? props?.errors[key]?.length <= 0
            : null
        : null;

const setForm = (value: Partial<PaymentTypes>) => {
    form.value = {
        name: value.name ?? "",
        code: value.code ?? "",
        description: value.description ?? "",
        short_desc: value.short_desc ?? "",
        inactive: value.inactive ?? true,
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

watch(
    () => props.resetKey,
    () => {
        form.value = defaultForm();
    }
);

onMounted(() => {
    if (props.data) {
        setForm(props.data);
    }

    props.schema(
        Yup.object().shape({
            name: Yup.string().required(),
            code: Yup.string().required(),
            description: Yup.string().notRequired(),
            short_desc: Yup.string().notRequired(),
            inactive: Yup.boolean().notRequired(),
        })
    );
});
</script>
