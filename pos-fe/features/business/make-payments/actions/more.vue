<template>
    <el-dropdown>
        <Button
            light
            icon="dots-square-vertical"
            :icon-color="{
                color: 'primary',
                active: 'secondary',
            }"
            class="!uppercase dropdown-toggle btn-sm"
            data-bs-toggle="dropdown"
            aria-expanded="false"
        />
        <template #dropdown>
            <el-dropdown-menu>
                <Save
                    :data="data"
                    :validate-form="validateForm"
                    :save-payment="savePayment"
                />

                <SaveNew
                    :data="data"
                    :validate-form="validateForm"
                    :save-payment="savePayment"
                />

                <PrintVoucher v-if="isUpdate" :uuid="uuid" />

                <PrintCheck v-if="isUpdate" :uuid="uuid" />

                <el-dropdown-item>
                    <PreviewJournals :data="data" />
                </el-dropdown-item>
            </el-dropdown-menu>
        </template>
    </el-dropdown>
</template>

<script lang="ts" setup>
import Save from "./save.vue";
import SaveNew from "./save-new.vue";
import PrintVoucher from "./print-voucher.vue";
import PrintCheck from "./print-check.vue";
import PreviewJournals from "./preview-journals.vue";
import type { Payment } from "~/types/payment";

defineProps<{
    data?: Partial<Payment>;
    validateForm: () => Promise<void>;
    savePayment: () => Promise<any>;
    uuid?: string;
}>();

const route = useRoute();
const isUpdate = computed(() => Boolean(route.params.uuid));
</script>
