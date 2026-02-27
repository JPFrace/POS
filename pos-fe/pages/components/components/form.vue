<template>
    <div class="flex flex-col gap-y-4">
        <h2>Input</h2>
        <div class="flex flex-col gap-y-2">
            <h5>Basic</h5>
            <Input v-model="input.input1" placeholder="Text here..." />
            <span>Result: {{ input.input1 }}</span>
        </div>
        <div class="flex flex-col gap-y-2">
            <h5>Solid</h5>
            <Input v-model="input.input2" solid placeholder="Text here..." />
            <span>Result: {{ input.input2 }}</span>
        </div>
        <div class="flex flex-col gap-y-2">
            <h5>Transparent</h5>
            <Input
                v-model="input.input3"
                transparent
                placeholder="Text here..."
            />
            <span>Result: {{ input.input3 }}</span>
        </div>
        <h2>Floating Input</h2>
        <div class="flex flex-col gap-y-2">
            <Input id="sample-float1" float label="Name" placeholder="Name" />
            <Input
                id="sample-float2"
                float
                label="Name Valid"
                placeholder="Name Valid"
                is-valid
            />
            <Input
                id="sample-float3"
                float
                label="Name Invalid"
                placeholder="Name Invalid"
                :is-valid="false"
            />
        </div>
        <div class="flex flex-col gap-y-2">
            <h2>Checkbox</h2>
            <div class="flex items-start justify-start gap-x-10">
                <div class="flex flex-col gap-y-3">
                    <Checkbox
                        id="regular"
                        v-model="checks.check1"
                        label="Regular"
                    />
                    <span>Result: {{ checks.check1 }}</span>
                    <Checkbox
                        id="solid"
                        v-model="checks.check2"
                        label="Solid"
                        solid
                    />
                    <span>Result: {{ checks.check2 }}</span>
                    <Checkbox
                        id="success"
                        v-model="checks.check3"
                        label="Success"
                        variant="success"
                        solid
                    />
                    <span>Result: {{ checks.check3 }}</span>
                    <Checkbox
                        id="warning"
                        v-model="checks.check4"
                        label="Warning"
                        variant="warning"
                        solid
                    />
                    <span class="mt-2">Result: {{ checks.check4 }}</span>
                    <Checkbox
                        id="danger"
                        v-model="checks.check5"
                        label="Danger"
                        variant="danger"
                        solid
                    />
                    <span class="mt-2">Result: {{ checks.check5 }}</span>
                </div>
                <div class="flex flex-col gap-y-3">
                    <Checkbox
                        id="large-size-regular"
                        v-model="checks.check6"
                        label="Large size"
                        size="lg"
                        solid
                    />
                    <span>Result: {{ checks.check6 }}</span>
                    <Checkbox
                        id="large-size-success"
                        v-model="checks.check7"
                        label="Large size"
                        size="lg"
                        variant="success"
                        solid
                    />
                    <span>Result: {{ checks.check7 }}</span>
                    <Checkbox
                        id="large-size-warning"
                        v-model="checks.check8"
                        label="Large size"
                        size="lg"
                        variant="warning"
                        solid
                    />
                    <span>Result: {{ checks.check8 }}</span>
                    <Checkbox
                        id="large-size-danger"
                        v-model="checks.check9"
                        label="Large size"
                        size="lg"
                        variant="danger"
                        solid
                    />
                    <span>Result: {{ checks.check9 }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="flex flex-col gap-y-4">
        <h2>Select</h2>
        <div class="flex flex-col gap-y-2">
            <h5>Basic</h5>
            <SelectNative
                v-model="nativeSelect.select1"
                placeholder="Select here..."
            >
                <option>Option 1</option>
                <option>Option 2</option>
                <option>Option 3</option>
            </SelectNative>
            <span>Result: {{ nativeSelect.select1 }}</span>
        </div>
        <div class="flex flex-col gap-y-2">
            <h5>Solid</h5>
            <SelectNative v-model="nativeSelect.select2" solid>
                <option>Option 1</option>
                <option>Option 2</option>
                <option>Option 3</option>
            </SelectNative>
            <span>Result: {{ nativeSelect.select2 }}</span>
        </div>
        <div class="flex flex-col gap-y-2">
            <h5>Transparent</h5>
            <SelectNative v-model="nativeSelect.select3" transparent>
                <option>Option 1</option>
                <option>Option 2</option>
                <option>Option 3</option>
            </SelectNative>
            <span>Result: {{ nativeSelect.select3 }}</span>
        </div>
        <div class="flex flex-col gap-y-2">
            <h5>Select Locale and Filterable (No remote)</h5>
            <SelectLocal />
        </div>
        <div class="flex flex-col gap-y-2">
            <h5>Remote</h5>
            <SelectRemoteComponent />
        </div>
        <div class="flex flex-col gap-y-2">
            <h5>Custom</h5>
            <SelectRemoteCustom />
        </div>
        <div class="flex flex-col gap-y-2">
            <h5>Group</h5>
            <SelectRemoteGroup />
        </div>
        <div class="flex flex-col gap-y-2">
            <h5>Group Custom</h5>
            <SelectGroupCustom />
        </div>
        <div class="flex flex-col gap-y-2">
            <span>Column</span>
            <SelectColumn />
        </div>
    </div>
    <div class="flex flex-col gap-y-4">
        <h2>Input Group</h2>
        <div class="flex flex-col gap-y-2">
            <h5>Regular</h5>
            <Input
                v-model="inputGroup.input1"
                group
                placeholder="Regular group"
            >
                <template #prepend> @ </template>
                <template #append> @ </template>
            </Input>
            <span>Result: {{ inputGroup.input1 }}</span>
        </div>
        <div class="flex flex-col gap-y-2">
            <h5>Solid</h5>
            <Input
                v-model="inputGroup.input2"
                group
                solid
                placeholder="Solid group"
            >
                <template #prepend> @ </template>
                <template #append> @ </template>
            </Input>
            <span>Result: {{ inputGroup.input2 }}</span>
        </div>
        <div class="flex flex-col gap-y-2">
            <h5 class="mt-5">Small</h5>
            <Input
                v-model="inputGroup.input3"
                group
                size="sm"
                placeholder="Small size group"
            >
                <template #prepend> @ </template>
                <template #append> @ </template>
            </Input>
            <span>Result: {{ inputGroup.input3 }}</span>
        </div>
        <div class="flex flex-col gap-y-2">
            <h5>Large</h5>
            <Input
                v-model="inputGroup.input4"
                group
                size="lg"
                placeholder="Large size group"
            >
                <template #prepend> @ </template>
                <template #append> @ </template>
            </Input>
            <span>Result: {{ inputGroup.input4 }}</span>
        </div>
        <div class="flex flex-col gap-y-2">
            <h5>Custom input</h5>
            <Input
                v-model="inputGroup.input5"
                group
                size="sm"
                placeholder="Custom group"
                :is-valid="false"
            >
                <template #prepend> @ </template>
                <template #append> @ </template>
            </Input>
            <span>Result: {{ inputGroup.input5 }}</span>
            <Input v-model="inputGroup.input6" group size="sm" :is-valid="true">
                <template #prepend> @ </template>
                <template #append> @ </template>
            </Input>
            <span>Result: {{ inputGroup.input6 }}</span>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { watch } from "vue";
import SelectRemoteComponent from "./select/remote.vue";
import SelectRemoteCustom from "./select/custom.vue";
import SelectRemoteGroup from "./select/group.vue";
import SelectGroupCustom from "./select/group-custom.vue";
import SelectColumn from "./select/column.vue";
import SelectLocal from "./select/local.vue";

const checkbox = defineModel("checkbox");
const open = defineModel("open");
const input = ref({
    input1: "",
    input2: "",
    input3: "",
});
const nativeSelect = ref({
    select1: "",
    select2: "",
    select3: "",
});

const checks = ref({
    check1: true,
    check2: true,
    check3: true,
    check4: true,
    check5: true,
    check6: true,
    check7: true,
    check8: true,
    check9: true,
});

const inputGroup = ref({
    input1: "",
    input2: "",
    input3: "",
    input4: "",
    input5: "",
    input6: "",
});

watch(checkbox, (value) => console.log(value));
</script>
