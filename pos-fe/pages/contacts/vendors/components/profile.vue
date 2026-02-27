<template>
    <!-- PROFILE TAB -->
    <div class="card p-4 mb-6 overflow-x-auto">
        <form class="space-y-4 py-1 px-1 w-full">
            <!-- Group: Vendor Personal Information -->
            <div>
                <div class="mb-2">
                    <div class="flex gap-4 w-full">
                        <!-- Vendor Type -->
                        <div class="flex flex-col w-auto">
                            <span class="text-sm font-semibold pb-2"
                                >Sub Type</span
                            >
                            <Select
                                v-model:selected="localSubType"
                                url="/api/contacts/contact-sub-types"
                                :data="subTypes"
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
                        <div class="flex flex-col w-auto">
                            <span class="text-sm font-semibold pb-2"
                                >Classification</span
                            >
                            <Select
                                v-model:selected="localClass"
                                url="/api/contacts/contact-classes"
                                :data="classes"
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
                        <div class="flex flex-col w-auto">
                            <span class="text-sm font-semibold pb-2"
                                >Tax Code</span
                            >
                            <Select
                                v-model:selected="localTax"
                                column
                                custom-column
                                url="/api/setup/taxes"
                                :data="taxes"
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
                </div>

                <!-- Individual fields -->
                <div v-if="isIndividual" class="mt-4 space-y-4">
                    <div class="flex gap-4 w-full">
                        <div class="flex-1">
                            <span class="block text-sm font-semibold mb-2"
                                >First Name</span
                            >
                            <Input
                                v-model="form.first_name"
                                placeholder="Enter First Name"
                                :is-valid="isValid('first_name')"
                            />
                        </div>

                        <div class="flex-1">
                            <span class="block text-sm font-semibold mb-2"
                                >Last Name</span
                            >
                            <Input
                                v-model="form.last_name"
                                placeholder="Enter Last Name"
                                :is-valid="isValid('last_name')"
                            />
                        </div>

                        <div class="w-32">
                            <div class="relative">
                                <span class="block text-sm font-semibold mb-2"
                                    >Suffix</span
                                >
                                <Select
                                    v-model:data="suffixes"
                                    v-model:selected="suffixValue"
                                    :filterable="true"
                                    :remote="false"
                                    placeholder="Select..."
                                    clearable
                                />
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4 w-full">
                        <div class="flex-1">
                            <span class="block text-sm font-semibold mb-2"
                                >Middle Name</span
                            >
                            <Input
                                v-model="form.middle_name"
                                placeholder="Enter Middle Name"
                                :is-valid="isValid('middle_name')"
                            />
                        </div>

                        <div class="flex-1">
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
                <div v-else class="w-full mt-4">
                    <div class="flex flex-col">
                        <span class="block text-sm font-semibold mb-2">
                            Name</span
                        >
                        <Input
                            v-model="form.name"
                            placeholder="Enter Name"
                            :is-valid="isValid('name')"
                        />

                        <div class="w-full mt-4">
                            <span class="block text-sm font-semibold mb-2">
                                Email Address</span
                            >
                            <Input
                                v-model="form.email"
                                placeholder="Enter Email Address"
                                :is-valid="isValid('email')"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script lang="ts" setup>
import type { Option } from "~/types/form";
import type { ContactClass } from "~/types/contact-class";
import type { ContactSubType } from "~/types/contact-type";
import type { WithholdingTax } from "~/types/withholding-tax";
import TaxColumn from "./tax-column.vue";
import type { Tax } from "~/types/Tax";

const props = defineProps<{
    form: any;
    subTypes?: Option[] | null;
    classes?: Option[] | null;
    taxes?: Option[] | null;
    selectedSubType: Option | null;
    selectedClass: Option | null;
    selectedTax: Option | null;
    isIndividual: boolean | null;
    isValid: (k: string) => any;
}>();

const emit = defineEmits([
    "update:subTypes",
    "update:classes",
    "update:taxes",
    "update:selectedSubType",
    "update:selectedClass",
    "update:selectedTax",
]);

const { form, isValid, isIndividual } = toRefs(props);

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
    res.data.map((row: Tax) => ({
        id: row.uuid,
        value: row.uuid,
        label: `${row.name} - ${row.rate}%`,
        code: row.code,
        rate: `${row.rate}%`,
    }));

const mapQueryName = (search: any) => ({
    query: { name: search },
});

const mapQueryCodeDesc = (search: any) => ({
    query: { codeDesc: search, vat_only: true },
});

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
</script>
