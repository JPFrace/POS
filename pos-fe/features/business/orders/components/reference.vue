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

        <!-- Order No. -->
        <div class="flex flex-col w-70">
            <InputNative
                v-model="data!.order_no"
                label="Order No."
                block
                placeholder="Order No."
                :disabled="data!.order_no_auto"
                :is-valid="isValid('order_no')"
            >
                <template #label="{ label }">
                    <div class="flex items-center justify-between">
                        <label class="form-label">{{ label }}</label>
                        <Checkbox
                            id="order_no_auto"
                            v-model="data!.order_no_auto"
                            label="<Auto Generated>"
                            size="sm"
                        />
                    </div>
                </template>
            </InputNative>
        </div>

        <!-- Save/New Button -->
        <div class="flex-shrink-0 ml-auto">
            <SaveNew v-model="data" />
        </div>
    </div>
</template>

<script lang="ts" setup>
import SaveNew from "../actions/save-new.vue";
import type { Order } from "~/types/order";

const data = defineModel<Partial<Order>>();

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
