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
                    :save-receipt="saveReceipt"
                />

                <SaveNew
                    :data="data"
                    :validate-form="validateForm"
                    :save-receipt="saveReceipt"
                />

                <PrintReceipt v-if="isUpdate" :uuid="uuid" />
            </el-dropdown-menu>
        </template>
    </el-dropdown>
</template>

<script lang="ts" setup>
import type { OfficialReceipt } from "~/types/official-receipts";
import Save from "./save.vue";
import SaveNew from "./save-new.vue";
import PrintReceipt from "./print-receipt.vue";

defineProps<{
    data?: Partial<OfficialReceipt>;
    validateForm: () => Promise<void>;
    saveReceipt: () => Promise<any>;
    uuid?: string;
}>();

const route = useRoute();
const isUpdate = computed(() => Boolean(route.params.uuid));
</script>
