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
            <span class="text-sm font-semibold mb-2">Description</span>
            <Textarea
                v-model="form.description"
                placeholder="..."
                :is-valid="isValid('description')"
                id="description"
            ></Textarea>
        </div>
        <div class="flex-1 flex flex-col">
            <span class="text-sm font-semibold mb-2">Type</span>
            <Select
                url="/api/setup/withholding-tax-types"
                v-model:data="types"
                v-model:selected="form.type"
                :mapResult="
                    (result: any) =>
                        result.data.map((row: WithholdingTaxType) => ({
                            id: row.uuid,
                            value: row.uuid,
                            label: `${row.name} (${row.code})`,
                        }))
                "
                :mapQuery="
                    (search: any) => ({
                        query: { name: search },
                    })
                "
                remote
                loading
                placeholder="Select..."
            />
        </div>
        <div class="flex-1 flex flex-col">
            <span class="text-sm font-semibold mb-2">Payer Type</span>
            <Select
                url="/api/contacts/contact-sub-types"
                v-model:data="payerTypes"
                v-model:selected="form.payer_type"
                :mapResult="
                    (result: any) =>
                        result.data.map((row: PayerType) => ({
                            id: row.id,
                            value: row.id,
                            label: row.name,
                        }))
                "
                :mapQuery="
                    (search: any) => ({
                        query: { name: search },
                    })
                "
                remote
                loading
                placeholder="Select..."
            />
        </div>
        <div class="flex-1 flex flex-col">
            <span class="text-sm font-semibold mb-2">Rate</span>
            <div class="input-group w-50">
                <Input
                    v-model="form.rate"
                    type="number"
                    placeholder="Enter Rate"
                    :is-valid="isValid('rate')"
                    min="1"
                    max="100"
                    step="1"
                />
                <span class="input-group-text" id="basic-addon2"> % </span>
            </div>
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
import type { Option } from "~/types/form";
import type {
    PayerType,
    WithholdingTax,
    WithholdingTaxType,
} from "~/types/withholding-tax";

const { yup } = useYup();

const types = ref<Option[]>();
const payerTypes = ref<Option[]>();

interface Props {
    errors?: object;
    schema: Function;
    form: Function;
    data?: WithholdingTax;
}

const props = defineProps<Props>();
const form = ref<Partial<WithholdingTax>>({
    code: "",
    description: "",
    rate: 1,
    type: null,
    payer_type: null,
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
        code: value.code,
        description: value.description,
        rate: value.rate,
        type: value.type
            ? {
                  id: value.type.uuid,
                  value: value.type.uuid,
                  label: `${value.type.name} (${value.type.code})`,
              }
            : null,
        payer_type: value.payer_type
            ? {
                  id: value.payer_type.id,
                  value: value.payer_type.id,
                  label: value.payer_type.name,
              }
            : null,
        is_inactive: value.is_inactive,
    };

    if (value.taxtype) {
        types.value = [
            {
                id: value.type.uuid,
                value: value.type.uuid,
                label: `${value.type.name} (${value.type.code})`,
            } as Option,
        ];
    }
    console.log("Payer Type", value.payer_type);
    if (value.payer_type) {
        payerTypes.value = [
            {
                id: value.payer_type.id,
                value: value.payer_type.id,
                label: value.payer_type.name,
            } as Option,
        ];
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
            code: Yup.string().required("Code is required"),
            description: Yup.string(),
            rate: Yup.number()
                .required("Rate is required")
                .min(1, "Rate must be at least 1%"),
            type: Yup.object().nullable(),
            payer_type: Yup.object().nullable(),
            is_inactive: Yup.boolean(),
        })
    );
});
</script>
