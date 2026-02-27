<template>
    <el-dropdown-item @click="handlePrintCheck">
        <KTIcon
            icon-name="printer"
            icon-class="fs-2 cursor-pointer hover:dark:text-white hover:text-slate-700"
        />
        Print Check
    </el-dropdown-item>
</template>

<script lang="ts" setup>
import { usePaymentMethod } from "~/composables/business/usePaymentMethod";

const props = defineProps<{
    uuid?: string;
}>();

const { t } = useI18n();
const { $message, $swal } = useNuxtApp();
const { getSavedPaymentMethod } = usePaymentMethod();

const handlePrintCheck = async () => {
    if (!props.uuid) {
        $message("info", t("make_payment.info.no_payment_data"));
        return;
    }

    const savedPaymentMethod = await getSavedPaymentMethod(props.uuid);

    if (savedPaymentMethod !== "check") {
        $swal("error", {
            title: t("Invalid Payment Method"),
            text: "Cheque layout can only be generated for check payments. Please save the payment method as 'Check' first.",
        });
        return;
    }

    const url = `/reports/cheque-layout/${props.uuid}`;
    window.open(url, "_blank");
};
</script>
