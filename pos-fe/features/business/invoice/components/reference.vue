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

        <!-- Invoice No. -->
        <div class="flex flex-col w-70">
            <InputNative
                v-model="data!.invoice_no"
                label="Invoice No."
                block
                placeholder="Invoice No."
                :disabled="data!.invoice_no_auto"
                :is-valid="isValid('invoice_no')"
            >
                <template #label="{ label }">
                    <div class="flex items-center justify-between">
                        <label class="form-label">{{ label }}</label>
                        <Checkbox
                            id="invoice_no_auto"
                            v-model="data!.invoice_no_auto"
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

        <!-- Save New Button -->
        <div class="flex-shrink-0 ml-auto">
            <SaveNew v-model="data" />
        </div>
    </div>
</template>

<script lang="ts" setup>
import type { Invoice } from "~/types/invoice";
import SaveNew from "../actions/save-print.vue";

const data = defineModel<Partial<Invoice>>();

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
