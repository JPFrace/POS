<template>
    <form class="flex flex-col gap-y-4 py-1 px-1">
        <div class="flex gap-x-4 items-start justify-between">
            <div class="w-2/5 flex flex-col">
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
                <span class="text-sm font-semibold mb-2">Account Type</span>
                <Select
                    url="/api/accounting/account-types"
                    v-model:data="types"
                    v-model:selected="form.type"
                    :mapResult="
                        (result: any) =>
                            result.data.map((row: AccountType) => ({
                                id: row.uuid,
                                value: row.uuid,
                                label: row.name,
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
                    :is-valid="isValid('type')"
                />
            </div>
            <div class="flex-1 flex flex-col">
                <span class="text-sm font-semibold mb-2">Account Class</span>
                <Select
                    url="/api/accounting/account-classes"
                    v-model:data="classes"
                    v-model:selected="form.class"
                    :mapResult="
                        (result: any) =>
                            result.data.map((row: AccountClass) => ({
                                id: row.uuid,
                                value: row.uuid,
                                label: row.name,
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
        <div class="flex gap-x-4 items-center justify-between">
            <div class="flex-1 flex flex-col">
                <span class="text-sm font-semibold mb-2">Department</span>
                <Select
                    url="/api/setup/departments"
                    v-model:data="departments"
                    v-model:selected="form.department"
                    :mapResult="
                        (result: any) =>
                            result.data.map((row: AccountClass) => ({
                                id: row.uuid,
                                value: row.uuid,
                                label: row.name,
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
            <div class="flex-1 flex flex-col">
                <span class="text-sm font-semibold mb-2">Usage</span>
                <Select
                    url="/api/accounting/account-usage-types"
                    v-model:data="usages"
                    v-model:selected="form.usage"
                    :mapResult="
                        (result: any) =>
                            result.data.map((row: AccountUsageType) => ({
                                id: row.uuid,
                                value: row.uuid,
                                label: row.name,
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
        <div class="flex gap-x-4 items-center justify-between">
            <div class="flex-1 flex flex-col">
                <span class="text-sm font-semibold mb-2">Budget Type</span>
                <Select
                    url="/api/budgeting/budget-type"
                    v-model:data="budgetTypes"
                    v-model:selected="form.budgetType"
                    :mapResult="
                        (result: any) =>
                            result.data.map((row: BudgetType) => ({
                                id: row.uuid,
                                value: row.uuid,
                                label: row.name,
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
        <div class="flex-1 flex flex-col">
            <span class="text-sm font-semibold mb-2">Budget</span>
            <Currency
                v-model="form.budget"
                placeholder="Enter Amount"
                :is-valid="isValid('budget')"
            />
        </div></div>
        <div class="flex-1 flex flex-col">
            <span class="text-sm font-semibold mb-2">Beginning Balance</span>
            <Currency
                v-model="form.balance"
                placeholder="Enter Amount"
                :is-valid="isValid('balance')"
            />
        </div>
        <div class="flex-1 flex flex-col">
            <span class="text-sm font-semibold mb-2">Parent</span>
            <Select
                url="/api/accounting/chart-accounts"
                v-model:data="parents"
                v-model:selected="form.parent"
                :mapResult="
                    (result: any) =>
                        result.data.map((row: ChartAccount) => ({
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

        <Checkbox
            v-model="form.add_as_product"
            label="Add as Product"
            :is-valid="isValid('add_as_product')"
            size="sm"
        />

        <Checkbox
            v-model="form.is_inactive"
            label="Inactive"
            :is-valid="isValid('is_inactive')"
            size="sm"
        />
    </form>
</template>

<script lang="ts" setup>
import type { AccountClass } from "~/types/account-class";
import type { AccountType } from "~/types/account-types";
import type { AccountUsageType } from "~/types/account-usage-type";
import type { BudgetType } from "~/types/budget";
import type { ChartAccount } from "~/types/chart-account";
import type { Option } from "~/types/form";

const { yup } = useYup();

interface Props {
    errors?: object;
    schema: Function;
    form: Function;
    data?: AccountType;
}

const props = defineProps<Props>();
const form = ref<Partial<ChartAccount>>({
    code: "",
    name: "",
    description: "",
    is_inactive: false,
    add_as_product: false,
});

const types = ref<Option[]>();
const classes = ref<Option[]>();
const parents = ref<Option[]>();
const departments = ref<Option[]>();
const usages = ref<Option[]>();
const budgetTypes = ref<Option[]>();

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
        description: value.description ?? "",
        is_inactive: value.is_inactive ?? true,
        type: value.type,
        class: value.class,
        parent: value.parent,
        budget: currencyFormat(value.budget),
        balance: currencyFormat(value.balance),
        add_as_product: value.add_as_product,
    };

    if (value.type) {
        types.value = [
            {
                id: value.type.uuid,
                value: value.type.uuid,
                label: value.type.name,
            } as Option,
        ];

        form.value.type = types.value.filter(
            (d) => d.id == value.type.uuid,
        )[0] as Option;
    }

    if (value.class) {
        classes.value = [
            {
                id: value.class.uuid,
                value: value.class.uuid,
                label: value.class.name,
            } as Option,
        ];

        form.value.class = classes.value.filter(
            (d) => d.id == value.class.uuid,
        )[0] as Option;
    }

    if (value.department) {
        departments.value = [
            {
                id: value.department.uuid,
                value: value.department.uuid,
                label: value.department.name,
            } as Option,
        ];

        form.value.department = departments.value.filter(
            (d) => d.id == value.department.uuid,
        )[0] as Option;
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

    if (value.usage_type) {
        usages.value = [
            {
                id: value.usage_type.uuid,
                value: value.usage_type.uuid,
                label: value.usage_type.name,
            } as Option,
        ];

        form.value.usage = usages.value.filter(
            (d) => d.id == value.usage_type.uuid,
        )[0] as Option;
    }
};

const parentChildren = (children: any) => {
    return children.map((row: ChartAccount) => ({
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
            code: Yup.string().required(),
            name: Yup.string().required(),
            type: Yup.object().required(),
            budgetType: Yup.object().notRequired().nullable(),
            description: Yup.string().notRequired(),
            is_inactive: Yup.boolean().notRequired().nullable(),
            add_as_product: Yup.boolean().notRequired().nullable(),
        }),
    );
});
</script>
