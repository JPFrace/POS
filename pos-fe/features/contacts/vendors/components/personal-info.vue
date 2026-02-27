<template>
    <div class="flex flex-col gap-4">
        <!-- Auto Generated ID No and ID No field -->
        <div class="mt-4 w-[190px]">
            <div class="flex items-center justify-between mb-2">
                <span class="block text-sm font-semibold">ID No.</span>
                <Checkbox
                    id="regular"
                    v-model="form.id_no_auto"
                    label="<Auto Generated>"
                    size="sm"
                />
            </div>
            <Input
                v-model="form.id_no"
                placeholder="Enter ID No."
                :is-valid="isValid('id_no')"
                :disabled="form.id_no_auto"
            />
        </div>
        <div class="flex items-start gap-6">
            <!-- Vendor Type -->
            <div class="flex flex-col w-[240px]">
                <span class="text-sm font-semibold pb-2">Sub Type</span>
                <Select
                    v-model:selected="localSubType"
                    url="/api/contacts/contact-sub-types"
                    :data="props.subTypes ?? []"
                    :map-result="mapSubTypes"
                    :map-query="mapQueryName"
                    remote
                    loading
                    placeholder="Select..."
                    :is-valid="isValid('sub_type')"
                    @update:data="(v) => emit('update:subTypes', v)"
                />
            </div>

            <!-- Vendor Class -->
            <div class="flex flex-col w-[240px]">
                <span class="text-sm font-semibold pb-2">Classification</span>
                <Select
                    v-model:selected="localClass"
                    url="/api/contacts/contact-classes"
                    :data="props.classes ?? []"
                    :map-result="mapClasses"
                    :map-query="mapQueryName"
                    clearable
                    remote
                    loading
                    placeholder="Select..."
                    :is-valid="isValid('class')"
                    @update:data="(v) => emit('update:classes', v)"
                />
            </div>

            <!-- Tax Code -->
            <div class="flex flex-col w-[240px]">
                <span class="text-sm font-semibold pb-2">Tax Code</span>
                <Select
                    v-model:selected="localTax"
                    column
                    custom-column
                    url="/api/setup/withholding-tax"
                    :data="props.taxes ?? []"
                    :map-result="mapTaxes"
                    :map-query="mapQueryCodeDesc"
                    clearable
                    remote
                    loading
                    placeholder="Select..."
                    :is-valid="isValid('tax')"
                    @update:data="(v) => emit('update:taxes', v)"
                >
                    <template #customColumn="{ data }">
                        <TaxColumn :data="data" />
                    </template>
                </Select>
            </div>
        </div>
        <!-- Individual fields -->
        <div v-if="isIndividual" class="flex flex-col gap-4">
            <div class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[180px]">
                    <span class="block text-sm font-semibold mb-2"
                        >First Name</span
                    >
                    <Input
                        v-model="form.first_name"
                        placeholder="Enter First Name"
                        :is-valid="isValid('first_name')"
                    />
                </div>
                <div class="flex-1 min-w-[150px]">
                    <span class="block text-sm font-semibold mb-2"
                        >Last Name</span
                    >
                    <Input
                        v-model="form.last_name"
                        placeholder="Enter Last Name"
                        :is-valid="isValid('last_name')"
                    />
                </div>
                <div class="w-[70px] relative flex-shrink-0">
                    <span class="block text-sm font-semibold mb-2">Suffix</span>
                    <Select
                        v-model:data="suffixes"
                        v-model:selected="suffixValue"
                        :filterable="true"
                        :remote="false"
                        placeholder="..."
                        clearable
                    />
                </div>
            </div>

            <div class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[180px]">
                    <span class="block text-sm font-semibold mb-2"
                        >Middle Name</span
                    >
                    <Input
                        v-model="form.middle_name"
                        placeholder="Enter Middle Name"
                        :is-valid="isValid('middle_name')"
                    />
                </div>
                <div class="flex-1 min-w-[230px]">
                    <span class="block text-sm font-semibold mb-2"
                        >Email Address</span
                    >
                    <Input
                        v-model="form.email"
                        placeholder="Enter Email Address"
                        :is-valid="isValid('email')"
                    />
                </div>
            </div>
        </div>

        <!-- Business fields -->
        <div v-else class="flex flex-col flex-1 gap-4">
            <div class="flex-1 flex flex-col">
                <span class="text-sm font-semibold mb-2">Name</span>
                <Input
                    v-model="form.name"
                    placeholder="Enter Name"
                    :is-valid="isValid('name')"
                />
            </div>
            <div class="flex-1 flex flex-col">
                <span class="text-sm font-semibold mb-2">Email Address</span>
                <Input
                    v-model="form.email"
                    placeholder="Enter Email"
                    :is-valid="isValid('email')"
                />
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import type { Option } from "~/types/form";
import type { ContactClass } from "~/types/contact-class";
import type { ContactSubType } from "~/types/contact-type";
import type { WithholdingTax } from "~/types/withholding-tax";
import TaxColumn from "./tax-column.vue";

const props = withDefaults(
    defineProps<{
        form: any;
        isValid: (k: string) => any;
        isIndividual: boolean | null;
        subTypes?: Option[] | null;
        classes?: Option[] | null;
        taxes?: Option[] | null;
        selectedSubType: Option | null;
        selectedClass: Option | null;
        selectedTax: Option | null;
    }>(),
    { subTypes: () => [], classes: () => [], taxes: () => [] }
);

const emit = defineEmits([
    "update:subTypes",
    "update:classes",
    "update:taxes",
    "update:selectedSubType",
    "update:selectedClass",
    "update:selectedTax",
]);

const { form, isValid, isIndividual } = toRefs(props);

const suffixes = ref<Option[]>([
    { id: 1, value: "Jr.", label: "Jr." },
    { id: 2, value: "Sr.", label: "Sr." },
    { id: 3, value: "I", label: "I" },
    { id: 4, value: "II", label: "II" },
    { id: 5, value: "III", label: "III" },
    { id: 6, value: "IV", label: "IV" },
    { id: 7, value: "V", label: "V" },
    { id: 8, value: "VI", label: "VI" },
    { id: 9, value: "VII", label: "VII" },
    { id: 10, value: "VIII", label: "VIII" },
]);

const suffixValue = computed({
    get: () => form.value.suffix,
    set: (val) => {
        form.value.suffix =
            typeof val === "object" && val?.value ? val.value : val;
    },
});

const localSubType = computed({
    get: () => props.selectedSubType,
    set: (v) => emit("update:selectedSubType", v),
});

const localClass = computed({
    get: () => props.selectedClass,
    set: (v) => emit("update:selectedClass", v),
});

const localTax = computed({
    get: () => props.selectedTax,
    set: (v) => emit("update:selectedTax", v),
});

const mapSubTypes = (res: any) =>
    res.data.map((row: ContactSubType) => ({
        id: row.id,
        value: row.id,
        label: row.name,
    }));

const mapClasses = (res: any) =>
    res.data.map((row: ContactClass) => ({
        id: row.id,
        value: row.id,
        label: row.name,
    }));

const mapTaxes = (res: any) =>
    res.data.map((row: WithholdingTax) => ({
        id: row.uuid,
        value: row.uuid,
        label: `${row.code} — ${row.rate}%`,
        code: row.code,
        rate: `${row.rate}%`,
    }));

const mapQueryName = (search: any) => ({
    query: { name: search },
});
const mapQueryCodeDesc = (search: any) => ({
    query: { codeDesc: search, vat_only: true },
});
</script>
