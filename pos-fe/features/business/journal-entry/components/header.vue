<template>
    <div class="flex items-end gap-4 -mt-6 w-full">
        <!-- Date Picker -->
        <div class="flex flex-col flex-shrink-0 w-60">
            <label class="form-label">Date</label>
            <el-date-picker
                v-model="data!.date"
                type="date"
                placeholder="MM/DD/YYYY"
                size="large"
                format="MM/DD/YYYY"
                value-format="MM/DD/YYYY"
                :is-valid="isValid('date')"
            />
        </div>

        <!-- Reference No. -->
        <div class="flex flex-col">
            <InputNative
                v-model="data!.ref_no"
                label="Ref No."
                block
                placeholder="Reference No."
                :disabled="data!.ref_no_auto ?? true"
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

        <!-- Journal No. -->
        <div class="flex flex-col">
            <InputNative
                v-model="data!.je_no"
                label="Journal No."
                block
                placeholder="Journal No."
                :disabled="data!.je_no_auto ?? true"
                :is-valid="isValid('je_no')"
            >
                <template #label="{ label }">
                    <div class="flex items-center justify-between">
                        <label class="form-label">{{ label }}</label>
                        <Checkbox
                            id="journal_no"
                            v-model="data!.je_no_auto"
                            label="<Auto Generated>"
                            size="sm"
                        />
                    </div>
                </template>
            </InputNative>
        </div>

        <!-- Save Button -->
        <div class="flex-shrink-0 ml-auto">
            <SaveNew v-model="data" />
        </div>
    </div>
</template>

<script lang="ts" setup>
import type { JournalEntry } from "~/types/journal-entry";
import SaveNew from "../actions/save-print.vue";

const data = defineModel<Partial<JournalEntry>>();

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
