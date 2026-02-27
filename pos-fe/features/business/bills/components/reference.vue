<template>
    <div class="flex items-end gap-4 -mt-6 w-full">
        <!-- Date -->
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

        <!-- Bill No. -->
        <div class="flex flex-col w-70">
            <InputNative
                v-model="data!.bill_no"
                label="Bill No."
                block
                placeholder="Bill No."
                :disabled="data!.bill_no_auto"
                :is-valid="isValid('bill_no')"
            >
                <template #label="{ label }">
                    <div class="flex items-center justify-between">
                        <label class="form-label">{{ label }}</label>
                        <Checkbox
                            id="bill_no_auto"
                            v-model="data!.bill_no_auto"
                            label="<Auto Generated>"
                            size="sm"
                        />
                    </div>
                </template>
            </InputNative>
        </div>

        <!-- Due Date -->
        <div class="flex flex-col flex-shrink-0 w-60">
            <label class="form-label">Due Date</label>
            <el-date-picker
                v-model="data!.due_date"
                type="date"
                size="large"
                placeholder="Due Date"
                label="Due Date"
                format="MM/DD/YYYY"
                value-format="MM/DD/YYYY"
            />
        </div>

        <!-- Save/New Button -->
        <div class="flex-shrink-0 ml-auto">
            <SaveNew v-model="data" />
        </div>
    </div>
</template>

<script lang="ts" setup>
import type { Bill } from "~/types/bill";
import SaveNew from "../actions/save-print.vue";

const data = defineModel<Partial<Bill>>();

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
</script>
