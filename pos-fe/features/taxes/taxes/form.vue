<template>
    <div id="taxForm" class="flex items-start justify-between gap-4">
        <div class="flex-1">
            <TaxFilter>
                <template #options>
                    <div class="flex flex-col gap-2">
                        <label class="form-label">Tax Agency</label>
                        <Select
                            v-model:selected="selectedTaxAgency"
                            url="/api/taxes/tax-agency"
                            :map-result="
                                (result: any) =>
                                    result.data.map((row: TaxesAgency) => ({
                                        id: row.uuid,
                                        value: row.uuid,
                                        label: row.name,
                                        template: row.name,
                                    }))
                            "
                            :map-query="
                                (search: any) => ({
                                    query: { name: search },
                                })
                            "
                            clearable
                            remote
                            loading
                            placeholder="Select..."
                            class="w-full md:max-w-sm"
                        />
                    </div>
                </template>
            </TaxFilter>
        </div>

        <div class="flex justify-end content-center">
            <button
                v-if="!showForm"
                type="button"
                class="btn btn-primary whitespace-nowrap"
                @click="toggleForm"
            >
                {{ "Add Rates" }}
            </button>
        </div>
    </div>

    <form v-if="showForm" class="space-y-4 py-1 px-1" @submit.prevent="submit">
        <div class="flex-1 flex flex-row gap-x-6">
            <div class="flex-1 flex flex-col">
                <span class="text-sm font-semibold mb-2">Tax Type</span>
                <Select
                    v-model:data="types"
                    v-model:selected="form.type_obj"
                    value-key="value"
                    url="/api/common/index"
                    :map-result="
                        (result: {
                            taxType: Array<{ name: string; value: string }>;
                        }) =>
                            result.taxType.map((row) => ({
                                id: row.value,
                                value: row.value,
                                label: row.value,
                                template: row.name,
                            }))
                    "
                    :map-query="
                        () => ({
                            queries: { taxType: true },
                        })
                    "
                    method="POST"
                    clearable
                    remote
                    loading
                    placeholder="Select Type..."
                    :is-valid="isValid('type_obj')"
                />
            </div>

            <div class="flex-1 flex flex-col">
                <span class="text-sm font-semibold mb-2">Type Agency</span>
                <Select
                    v-model:data="tax_agencies"
                    v-model:selected="form.tax_agency"
                    url="/api/taxes/tax-agency"
                    disabled
                    :map-result="
                        (result: any) =>
                            result.data.map((row: TaxesAgency) => ({
                                id: row.uuid,
                                value: row.uuid,
                                label: row.name,
                                template: row.name,
                            }))
                    "
                    :map-query="
                        (search: any) => ({
                            query: { name: search },
                        })
                    "
                    clearable
                    remote
                    loading
                    placeholder="Select Agency..."
                    :is-valid="isValid('tax_agency')"
                />
            </div>
        </div>

        <div class="flex-1 flex flex-row gap-x-6">
            <div class="flex-1 flex flex-col">
                <span class="text-sm font-semibold mb-2">Tax Code</span>
                <Input
                    v-model="form.code"
                    placeholder="Enter Code"
                    :is-valid="isValid('code')"
                />
            </div>
            <div class="flex-1 flex flex-col">
                <span class="text-sm font-semibold mb-2">Tax Name</span>
                <Input
                    v-model="form.name"
                    placeholder="Enter Name"
                    :is-valid="isValid('name')"
                />
            </div>
        </div>
        <div class="flex-1 flex flex-col">
            <span class="text-sm font-semibold mb-2">Description</span>

            <Input
                v-model="form.description"
                style="width: auto"
                autosize
                type="text"
                placeholder="Enter Description"
                :is-valid="isValid('description')"
            />
        </div>

        <div class="flex-1 flex flex-row gap-x-6">
            <div class="flex-1 flex flex-col">
                <span class="text-sm font-semibold mb-2">Rate</span>
                <Input
                    type="number"
                    v-model="form.rate"
                    placeholder="Enter Rate"
                    :is-valid="isValid('rate')"
                />
            </div>
            <div class="flex-1 flex flex-col">
                <span class="text-sm font-semibold mb-2">Rate Type</span>
                <Select
                    v-model:data="rate_types"
                    v-model:selected="form.rate_type_obj"
                    value-key="value"
                    url="/api/common/index"
                    :map-result="
                        (result: {
                            rateType: Array<{ name: string; value: string }>;
                        }) =>
                            result.rateType.map((row) => ({
                                id: row.value,
                                value: row.value,
                                label: row.name,
                                template: row.name,
                            }))
                    "
                    :map-query="
                        () => ({
                            queries: { rateType: true },
                        })
                    "
                    method="POST"
                    clearable
                    remote
                    loading
                    placeholder="Select Rate Type..."
                    :is-valid="isValid('rate_type_obj')"
                />
            </div>
        </div>
        <div class="flex-1 flex flex-row gap-x-6">
            <div class="flex-1 flex flex-col">
                <span class="text-sm font-semibold mb-2">Chart Account</span>
                <Select
                    v-model:data="chart_accounts"
                    v-model:selected="form.chart_account"
                    url="/api/accounting/chart-accounts"
                    :map-result="
                        (result: any) =>
                            result.data.map((row: ChartAccount) => ({
                                id: row.uuid,
                                value: row.uuid,
                                label: `${row.code} - ${row.name}`,
                                template: row.name,
                            }))
                    "
                    :map-query="
                        (search: any) => ({
                            query: { name: search },
                        })
                    "
                    clearable
                    remote
                    loading
                    placeholder="Select..."
                    :is-valid="isValid('chart_account')"
                />
            </div>
            <div class="flex-1 flex flex-col">
                <span class="text-sm font-semibold mb-2">Parent</span>
                <Select
                    url="/api/taxes/tax"
                    v-model:data="parents"
                    v-model:selected="form.parent"
                    :mapResult="
                        (result: any) =>
                            result.data.map((row: Tax) => ({
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
                    placeholder="Select Parent..."
                />
            </div>
        </div>
        <div class="flex-1 flex flex-col">
            <span class="text-sm font-semibold mb-2">Class</span>
            <Select
                v-model:data="classes"
                v-model:selected="form.class"
                url="/api/accounting/account-classes"
                :map-result="
                    (result: any) =>
                        result.data.map((row: AccountClass) => ({
                            id: row.uuid,
                            value: row.uuid,
                            label: row.name,
                            template: row.name,
                        }))
                "
                :map-query="
                    (search: any) => ({
                        query: { name: search },
                    })
                "
                clearable
                remote
                loading
                placeholder="Select Class..."
                :is-valid="isValid('class')"
            />
        </div>
        <div class="flex justify-end gap-2 mt-4">
            <Button
                variant="light"
                class="btn btn-light"
                icon="black-left"
                @click="toggleForm"
            >
                <span>Cancel</span>
            </Button>
            <Button
                type="button"
                variant="primary"
                class="btn btn-primary"
                icon="add-folder"
                :disabled="processing"
                @click="onSubmitted"
            >
                <span>Submit</span>
            </Button>
        </div>
    </form>
</template>

<script lang="ts" setup>
const { yup } = useYup();

import type { Tax } from "~/types/Tax";
const showForm = ref(false);
const isEdit = ref(false);
const { $bus } = useNuxtApp();
import type { Option } from "~/types/form";
import type { TaxesAgency } from "~/types/taxes-agency";
import type { ChartAccount } from "~/types/chart-account";
import type { AccountClass } from "~/types/account-class";
const emit = defineEmits(["cancel", "submit"]);
import TaxFilter from "~/features/taxes/components/tax-filter.vue";

interface Props {
    errors?: object;
    schema: Function;
    form: Function;
    data?: Tax;
    submit: () => void;
    editSubmit: () => void;
}
const selectedTaxAgency = ref<Option | null>(null);
const initialized = ref(false);

onMounted(async () => {
    if (initialized.value) return;

    const res = await useClient(`/api/taxes/tax-agency`, {
        method: "GET",
    });
    if (res.data.length) {
        selectedTaxAgency.value = {
            id: res.data[0].uuid,
            value: res.data[0].uuid,
            label: res.data[0].name,
            template: res.data[0].name,
        };
        initialized.value = true;
    }
});

const pendingTaxAgency = ref<TaxesAgency | null>(null);
const parents = ref<Option[]>();

const tax_agencies = ref<Option[]>();
const chart_accounts = ref<Option[]>();
const classes = ref<Option[]>();
const types = ref<Option[]>();
const rate_types = ref<Option[]>();

const props = defineProps<Props>();
const form = ref<Partial<Tax>>({});
const Yup = yup();

const onSubmitted = () => {
    if (!isEdit.value) {
        props.submit();
    } else {
        $bus.emit("tax:update", form.value);
        props.editSubmit();
    }
};

const isValid = (key: string) =>
    props.errors
        ? Object.keys(props.errors).includes(key)
            ? (props?.errors as any)[key]?.length <= 0
            : null
        : null;

const parentChildren = (children: any) => {
    return children.map((row: ChartAccount) => ({
        id: row.uuid,
        value: row.uuid,
        label: row.name,
        children: parentChildren(row?.children ?? []),
    }));
};
const setForm = (value: Tax) => {
    form.value = {
        uuid: value.uuid ?? "",
        type: value.type ?? null,
        tax_agency: value.tax_agency ?? null,
        code: value.code ?? null,
        name: value.name ?? null,
        description: value.description ?? null,
        tax: value.tax ?? null,
        rate: value.rate ?? null,
        rate_type: value.rate_type ?? null,
        parent: value.parent ?? null,
        class_id: value.class_id ?? null,
        chart_account: value.chart_account ?? null,
        type_obj: value.type_obj ?? null,
    };

    if (value.tax_agency) {
        tax_agencies.value = [
            {
                id: value.tax_agency.uuid,
                value: value.tax_agency.uuid,
                label: value.tax_agency.name,
            } as Option,
        ];

        form.value.tax_agency = tax_agencies.value.find(
            (d) => d.id === value.tax_agency.uuid,
        ) as Option;
    }

    if (value.chart_account) {
        chart_accounts.value = [
            {
                id: value.chart_account.uuid,
                value: value.chart_account.uuid,
                label: value.chart_account.name,
            } as Option,
        ];

        form.value.chart_account = chart_accounts.value.find(
            (d) => d.id === value.chart_account.uuid,
        ) as Option;
    }
    if (value.class) {
        classes.value = [
            {
                id: value.class.uuid,
                value: value.class.uuid,
                label: value.class.name,
            } as Option,
        ];

        form.value.class = classes.value.find(
            (d) => d.id === value.class.uuid,
        ) as Option;
    }

    if (value.type_obj) {
        types.value = [
            {
                value: value.type_obj.value,
                label: value.type_obj.label,
            } as Option,
        ];

        form.value.type_obj = types.value.find(
            (d) => d.value === value.type_obj.value,
        ) as Option;
    }

    if (value.rate_type_obj) {
        rate_types.value = [
            {
                value: value.rate_type_obj.value,
                label: value.rate_type_obj.label,
            } as Option,
        ];

        form.value.rate_type_obj = rate_types.value.find(
            (d) => d.value === value.rate_type_obj.value,
        ) as Option;
    }

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

const toggleForm = () => {
    showForm.value = !showForm.value;
    if (showForm.value) {
        isEdit.value = false;
        form.value = {};

        nextTick(() => {
            if (pendingTaxAgency.value) {
                applyPendingTaxAgency(pendingTaxAgency.value);
            }
        });
    } else {
        isEdit.value = false;
        form.value = {};
    }
};

watch(
    () => ({ ...form.value }),
    (value) => {
        const formData = {
            ...value,
            tax_agency: value.tax_agency ?? null,
            chart_account: value.chart_account ?? null,
        };
        props.form(formData);
    },
);
const applyPendingTaxAgency = (row?: TaxesAgency | Option) => {
    if (!row) return;
    const option: Option =
        "uuid" in row
            ? { id: row.uuid, value: row.uuid, label: row.name }
            : (row as Option);

    tax_agencies.value = [option];
    form.value.tax_agency = option;

    props.form({
        ...form.value,
        tax_agency: option.value,
    });
};

watch(selectedTaxAgency, (val) => {
    if (!showForm.value) {
        $bus.emit("tax:agencySelect", selectedTaxAgency);
        pendingTaxAgency.value = val; // save for later if form is hidden
        return;
    }

    applyPendingTaxAgency(val);
});

onMounted(() => {
    $bus.on("tax:edit", (row: Tax) => {
        showForm.value = true;
        isEdit.value = true;
        const el = document.getElementById("taxForm");
        el?.scrollIntoView({
            behavior: "smooth",
            block: "start",
        });
        setForm(row);
    });

    props.schema(
        Yup.object().shape({
            code: Yup.string().required(),
            name: Yup.string().required(),
            tax_agency: Yup.object().required(),
            type_obj: Yup.object().required(),
            description: Yup.string().required(),
            rate_type_obj: Yup.object().required(),
            rate: Yup.number().required(),
            chart_account: Yup.object().required(),
        }),
    );
});
defineExpose({
    toggleForm,
    onSubmitted,
});
</script>
