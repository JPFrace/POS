<template>
    <form class="space-y-4 px-1 py-1">
        <div class="flex flex-col flex-1">
            <span class="mb-2 font-semibold text-sm">Report</span>
            <Select
                v-model:data="reports"
                v-model:selected="form.report"
                url="/api/setup/reports"
                :map-result="
                    (result: any) =>
                        result.data.map((row: Reports) => ({
                            id: row.uuid,
                            value: row.uuid,
                            label: row.name,
                            template: row.template,
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
            />
        </div>
        <div class="flex flex-col flex-1">
            <span class="mb-2 font-semibold text-sm">Label</span>
            <Input
                v-model="form.label"
                placeholder="Enter Label"
                :is-valid="isValid('label')"
            />
        </div>
        <div class="flex flex-col flex-1">
            <span class="mb-2 font-semibold text-sm">Signatory</span>
            <Select
                v-model:data="signatories"
                v-model:selected="form.signatory"
                url="/api/setup/signatories"
                :map-result="
                    (result: any) =>
                        result.data.map((row: any) => ({
                            id: row.uuid,
                            value: row.uuid,
                            label: row.name,
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
            />
        </div>
        <div class="flex flex-col flex-1">
            <span class="mb-2 font-semibold text-sm">Sort</span>
            <Select
                v-model:data="sortOptions"
                v-model:selected="form.sort"
                clearable
                placeholder="Select..."
            />
        </div>
        <div class="flex flex-col flex-1">
            <span class="mb-2 font-semibold text-sm">Year Signatory</span>
            <Select
                v-model:data="yearOptions"
                v-model:selected="form.year_signatory"
                clearable
                placeholder="Select..."
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
import type { Option } from "~/types/form";
import type { ReportSignatories } from "~/types/report-signatories";
import type { Reports } from "~/types/reports";

const { yup } = useYup();
const reports = ref<Option[]>();
const signatories = ref<Option[]>();

const currentYear = new Date().getFullYear();
const yearOptions = ref(
    Array.from({ length: 11 }, (_, i) => {
        const year = currentYear - 5 + i;
        return { id: year, value: year, label: `${year}` };
    })
);

interface Props {
    errors?: object;
    schema: Function;
    form: Function;
    data?: ReportSignatories;
}

const props = defineProps<Props>();

const form = ref<ReportSignatories>({
    label: "",
    report: null,
    signatory: "",
    is_inactive: false,
    sort: null,
    year_signatory: null,
});

const sortOptions = ref([
    { id: 1, value: 1, label: "1" },
    { id: 2, value: 2, label: "2" },
    { id: 3, value: 3, label: "3" },
    { id: 4, value: 4, label: "4" },
    { id: 5, value: 5, label: "5" },
]);

const Yup = yup();

const isValid = (key: string) =>
    props.errors
        ? Object.keys(props.errors).includes(key)
            ? (props?.errors as any)[key]?.length <= 0
            : null
        : null;

const setForm = (value: any) => {
    form.value = {
        label: value.label ?? "",
        report: value.report ?? null,
        signatory: value.signatory ?? "",
        is_inactive: value.is_inactive ?? false,
        sort: value.sort ?? null,
        year_signatory: value.year_signatory ?? null,
    };

    if (value.report) {
        reports.value = [
            {
                id: value.report.uuid,
                value: value.report.uuid,
                label: value.report.name,
            } as Option,
        ];

        form.value.report = reports.value.find(
            (d) => d.id === value.report.uuid
        ) as Option;
    }

    if (value.signatory) {
        signatories.value = [
            {
                id: value.signatory.uuid,
                value: value.signatory.uuid,
                label: value.signatory.name,
            } as Option,
        ];

        form.value.signatory = signatories.value.find(
            (d) => d.id === value.signatory.uuid
        ) as Option;
    }

    if (value.sort) {
        form.value.sort = sortOptions.value.find(
            (option) => option.value === value.sort
        ) as Option;
    }
    if (value.year_signatory) {
        if (
            !yearOptions.value.some((opt) => opt.value === value.year_signatory)
        ) {
            yearOptions.value.push({
                value: value.year_signatory,
                label: `${value.year_signatory}`,
            });
        }

        form.value.year_signatory = yearOptions.value.find(
            (option) => option.value === value.year_signatory
        ) as Option;
    }
};

watch(
    form,
    (value) => {
        const formData = {
            ...value,
            report: value.report?.value ?? null,
            signatory: value.signatory?.value ?? null,
            sort: value.sort?.value ?? null,
            year_signatory: value.year_signatory?.value ?? null,
        };
        props.form(formData);
    },
    { deep: true }
);

onMounted(() => {
    if (props.data) {
        setForm(props.data);
    }

    props.schema(
        Yup.object().shape({
            label: Yup.string().required(),
            report: Yup.string().required(),
            signatory: Yup.string().required(),
            sort: Yup.number().required(),
            year_signatory: Yup.number()
                .required("Year is required")
                .min(1900, "Year must be 4 digits")
                .max(2100, "Year must be realistic"),
        })
    );
});
</script>
