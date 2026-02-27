<template>
    <!-- BILLING ADDRESS TAB -->
    <div class="card p-4 mb-6">
        <!-- Billing Address -->
        <div class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[300px]">
                <span class="block text-sm font-semibold mb-2">Address</span>
                <Input
                    v-model="form.billing_address"
                    placeholder="Enter Address"
                    :is-valid="isValid('billing_address')"
                />
            </div>
        </div>

        <div class="flex flex-wrap gap-4 mt-4">
            <!-- Zip Code -->
            <div class="flex-[1] min-w-[100px] max-w-[130px]">
                <span class="block text-sm font-semibold mb-2">Zip Code</span>
                <Input
                    v-model="form.zip_code"
                    placeholder="Enter Zip Code"
                    :is-valid="isValid('zip_code')"
                />
            </div>

            <!-- Contact No. -->
            <div class="flex-[1.5] min-w-[140px] max-w-[180px]">
                <span class="block text-sm font-semibold mb-2"
                    >Contact No.</span
                >
                <Input
                    v-model="form.contact_number"
                    placeholder="Enter Contact No."
                    :is-valid="isValid('contact_number')"
                />
            </div>

            <!-- Country -->
            <div class="flex-[3] min-w-[250px]">
                <div class="relative">
                    <span class="block text-sm font-semibold mb-2"
                        >Country</span
                    >
                    <Select
                        v-model:selected="localCountry"
                        url="/api/contacts/countries"
                        :data="props.countries ?? []"
                        :map-result="mapCountries"
                        :map-query="mapQueryName"
                        clearable
                        remote
                        loading
                        placeholder="Select..."
                        :is-valid="isValid('country')"
                        @update:data="(v) => emit('update:countries', v)"
                    >
                        <template #default>
                            <el-option
                                v-for="item in (props.countries ?? [])"
                                :key="item.id"
                                :label="item.label"
                                :value="item"
                            >
                                <div class="flex items-center gap-2">
                                    <img
                                        :src="item.flag"
                                        :alt="item.label"
                                        class="w-6 h-4 object-cover"
                                        loading="lazy"
                                    />
                                    <span>{{ item.label }}</span>
                                </div>
                            </el-option>
                        </template>
                    </Select>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import type { Option } from "~/types/form";
import type { Country } from "~/types/country";

const props = withDefaults(
    defineProps<{
        form: any;
        isValid: (k: string) => any;
        countries?: Option[] | null;
        selectedCountry: Option | null;
    }>(),
    { countries: () => [] }
);

const emit = defineEmits(["update:countries", "update:selectedCountry"]);

const { form, isValid } = toRefs(props);

const localCountry = computed({
    get: () => props.selectedCountry,
    set: (v) => emit("update:selectedCountry", v),
});

const mapCountries = (res: any) =>
    res.data.map((row: Country) => ({
        id: row.uuid,
        value: row.uuid,
        label: row.name,
        flag: row.flag,
    }));

const mapQueryName = (search: any) => ({
    query: {
        name: search,
    },
    size: 300,
});
</script>
