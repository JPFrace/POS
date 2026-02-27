<template>
    <div class="flex items-end gap-4 -mt-6 w-full">
        <!-- Date Picker -->
        <div class="flex flex-col flex-shrink-0 w-60">
            <label class="form-label">Date</label>
            <el-date-picker
                v-model="data!.date"
                type="date"
                size="large"
                placeholder="Date"
                label="Date"
                format="MM/DD/YYYY"
                value-format="MM/DD/YYYY"
            />
        </div>

        <!-- Ref No. -->
        <div class="flex flex-col w-70">
            <InputNative
                v-model="data!.ref_no"
                label="Ref No."
                block
                placeholder="Reference No."
                :disabled="data!.ref_no_auto"
                :is-valid="isValid('ref_no')"
            >
                <template #label="{ label }">
                    <div class="flex items-center justify-between">
                        <label class="form-label">{{ label }}</label>
                        <Checkbox
                            id="ref_no"
                            v-model="data!.ref_no_auto"
                            label="<Auto Generated>"
                            size="sm"
                        />
                    </div>
                </template>
            </InputNative>
        </div>

        <!-- Save/Print Button -->
        <div class="flex-shrink-0 ml-auto">
            <Save v-model="data" />
        </div>
    </div>
</template>

<script lang="ts" setup>
import Save from "../actions/save.vue";
import type { Payment } from "~/types/payment";

const { receive } = usePageEvent();
const data = defineModel<Partial<Payment>>();
const methodName = ref("Check");

interface Props {
    errors: any;
}

const props = defineProps<Props>();

const isValid = (key: string) =>
    props.errors
        ? Object.keys(props.errors).includes(key)
            ? (props.errors as any)[key]?.length <= 0
            : null
        : null;

onMounted(() => {
    receive("payment-method-changed", (method: any) => {
        methodName.value = method.name;
    });
});
</script>
