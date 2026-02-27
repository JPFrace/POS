<template>
    <el-dropdown-item @click="handlePrintReceipt">
        <KTIcon
            icon-name="printer"
            icon-type="solid"
            icon-class="fs-2 cursor-pointer hover:dark:text-white hover:text-slate-700"
        />
        Print OR
    </el-dropdown-item>
</template>

<script lang="ts" setup>
const props = defineProps<{
    uuid?: string;
}>();

const { t } = useI18n();
const { $message } = useNuxtApp();

const handlePrintReceipt = async () => {
    if (!props.uuid) {
        $message("info", t("money_receive.info.no_receipt_data"));
        return;
    }

    const url = `/reports/official-receipt/${props.uuid}`;
    window.open(url, "_blank");
};
</script>
