<template>
    <form class="space-y-4 px-1 py-1">
        <div class="flex flex-col flex-1">
            <span class="mb-2 font-semibold text-sm">Calendar</span>
            <Select
                v-model:data="calendars"
                v-model:selected="form.calendar"
                url="/api/accounting/calendars"
                :map-result="
                    (result: any) =>
                        result.data.map((row: calendar) => ({
                            id: row.uuid,
                            value: row.uuid,
                            label: row.year,
                            template: row.year,
                        }))
                "
                :map-query="
                    (search: any) => ({
                        query: { year: search },
                    })
                "
                clearable
                remote
                loading
                placeholder="Select..."
                :is-valid="isValid('calendar')"
            />
        </div>
        <div class="flex flex-col flex-1">
            <span class="mb-2 font-semibold text-sm">Tax</span>
            <Select
                v-model="form.tax"
                url="/api/taxes/tax"
                :map-result="
                    (result: any) =>
                        result.data.map((row: Tax) => {
                            return {
                                id: row.uuid,
                                value: row.uuid,
                                label: `${row.name} ${row.code} ${row.rate}`,
                                code: row.code,
                                name: row.name,
                                rate: row.rate,
                            };
                        })
                "
                :map-query="
                    (search: any) => ({
                        query: { name: search },
                    })
                "
                remote
                loading
                column
                custom-column
                placeholder="Select..."
                :is-valid="isValid(`tax`)"
            >
                <template #customColumn="{ data: items }">
                    <TaxColumn :data="items" />
                </template>
            </Select>
        </div>
        <div class="flex flex-col flex-1">
            <span class="mb-2 font-semibold text-sm">Period</span>
            <Select
                v-model:data="periods"
                v-model:selected="form.period_obj"
                value-key="value"
                url="/api/common/index"
                :map-result="
                    (result: {
                        period: Array<{ name: string; value: string }>;
                    }) =>
                        result.period.map((row) => ({
                            id: row.value,
                            value: row.value,
                            label: row.name,
                            template: row.name,
                        }))
                "
                :map-query="
                    () => ({
                        queries: {
                            period: true,
                        },
                    })
                "
                method="POST"
                clearable
                remote
                loading
                placeholder="Select..."
                :is-valid="isValid('period_obj')"
            />
        </div>
        <div class="flex flex-col flex-1">
            <span class="mb-2 font-semibold text-sm">Start Tax At</span>
            <el-date-picker
                v-model="form.start_tax_at"
                type="date"
                size="large"
                placeholder="Date"
                label="Date"
                format="MM/DD/YYYY"
                value-format="YYYY/MM/DD"
                :is-valid="isValid('start_tax_at')"
            />
        </div>
        <div class="flex flex-col flex-1">
            <span class="mb-2 font-semibold text-sm">Start Tax Period</span>
            <Select
                v-model:data="months"
                v-model:selected="form.start_tax_period_obj"
                value-key="value"
                url="/api/common/index"
                :map-result="
                    (result: {
                        months: Array<{ name: string; value: number }>;
                    }) =>
                        result.months.map((row) => ({
                            id: row.value,
                            value: row.value,
                            label:
                                row.name.charAt(0) +
                                row.name.slice(1).toLowerCase(),
                            template:
                                row.name.charAt(0) +
                                row.name.slice(1).toLowerCase(),
                        }))
                "
                :map-query="
                    () => ({
                        queries: {
                            months: true,
                        },
                    })
                "
                method="POST"
                clearable
                remote
                loading
                placeholder="Select..."
                :is-valid="isValid('start_tax_period')"
            />
        </div>
        <div class="flex flex-col flex-1">
            <span class="mb-2 font-semibold text-sm">Reporting Method</span>
            <Select
                v-model:data="methods"
                v-model:selected="form.reporting_method_obj"
                value-key="value"
                url="/api/common/index"
                :map-result="
                    (result: {
                        method: Array<{ name: string; value: string }>;
                    }) =>
                        result.method.map((row) => ({
                            id: row.value,
                            value: row.value,
                            label: row.name,
                            template: row.name,
                        }))
                "
                :map-query="
                    () => ({
                        queries: {
                            method: true,
                        },
                    })
                "
                method="POST"
                clearable
                remote
                loading
                placeholder="Select..."
                :is-valid="isValid('reporting_method_obj')"
            />
        </div>
        <div class="flex flex-col flex-1">
            <span class="mb-2 font-semibold text-sm">Reg No.</span>
            <Input
                v-model="form.regno"
                placeholder="Enter Label"
                :is-valid="isValid('regno')"
            />
        </div>
    </form>
</template>

<script lang="ts" setup>
import type { Option } from "~/types/form";
import type { calendar } from "~/types/calendar";
import type { TaxesSetup } from "~/types/taxes-setup";
import type { Tax } from "~/types/Tax";

const { yup } = useYup();
const Yup = yup();
import TaxColumn from "../components/tax-column.vue";

const calendars = ref<Option[]>();
const taxes = ref<Option[]>();
const months = ref<Option[]>();
const periods = ref<Option[]>();
const methods = ref<Option[]>();

interface Props {
    errors?: object;
    schema: Function;
    form: Function;
    dataRef: Ref<TaxesSetup>;
}

const props = defineProps<Props>();

const form = ref<TaxesSetup>({
    uuid: "",
    calendar: null,
    tax: null,
    period: null,
    start_tax_at: null,
    start_tax_period: null,
    reporting_method: null,
    regno: null,
    start_tax_period_obj: null,
    reporting_method_obj: null,
    period_obj: null,
});

const isValid = (key: string) =>
    props.errors
        ? Object.keys(props.errors).includes(key)
            ? (props?.errors as any)[key]?.length <= 0
            : null
        : null;

const setForm = (value: any) => {
    form.value = {
        uuid: value.uuid ?? "",
        tax: value.tax ?? null,
        calendar: value.calendar ?? null,
        period: value.period ?? null,
        start_tax_at: value.start_tax_at ?? null,
        start_tax_period: value.start_tax_period ?? null,
        reporting_method: value.reporting_method ?? null,
        regno: value.regno ?? null,
        start_tax_period_obj: value.start_tax_period_obj ?? null,
        reporting_method_obj: value.reporting_method_obj ?? null,
        period_obj: value.period_obj ?? null,
    };

    if (value.calendar) {
        calendars.value = [
            {
                id: value.calendar.uuid,
                value: value.calendar.uuid,
                label: value.calendar.year,
            } as Option,
        ];

        form.value.calendar = calendars.value.find(
            (d) => d.id === value.calendar.uuid,
        ) as Option;
    }

    if (value.tax) {
        taxes.value = [
            {
                id: value.tax.uuid,
                value: value.tax.uuid,
                label: `${value.tax.name} ${value.tax.code} ${value.tax.rate}`,
            } as Option,
        ];

        form.value.tax = taxes.value.find(
            (d) => d.id === value.tax.uuid,
        ) as Option;
    }

    if (value.start_tax_period_obj) {
        months.value = [
            {
                value: value.start_tax_period_obj.value,
                label: value.start_tax_period_obj.label,
            } as Option,
        ];

        form.value.start_tax_period_obj = months.value.find(
            (d) => d.value === value.start_tax_period_obj.value,
        ) as Option;
    }
    if (value.reporting_method_obj) {
        methods.value = [
            {
                value: value.reporting_method_obj.value,
                label: value.reporting_method_obj.label,
            } as Option,
        ];

        form.value.reporting_method_obj = methods.value.find(
            (d) => d.value === value.reporting_method_obj.value,
        ) as Option;
    }
    if (value.period_obj) {
        periods.value = [
            {
                value: value.period_obj.value,
                label: value.period_obj.label,
            } as Option,
        ];

        form.value.period_obj = periods.value.find(
            (d) => d.value === value.period_obj.value,
        ) as Option;
    }
};

watch(
    form,
    (value) => {
        const formData = {
            ...value,
            calendar: value.calendar?.value ?? null,
            tax: value.tax?.value ?? null,
        };
        props.form(formData);
    },
    { deep: true },
);

onMounted(() => {
    if (props.dataRef) {
        setForm(props.dataRef);
    }

    props.schema(
        Yup.object().shape({
            regno: Yup.string().required(),
        }),
    );
});
</script>
