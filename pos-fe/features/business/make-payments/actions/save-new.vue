<template>
    <el-dropdown-item :disabled="isPosted" @click="handleSaveNew">
        <KTIcon
            icon-name="plus-circle"
            icon-class="fs-2 cursor-pointer hover:dark:text-white hover:text-slate-700"
        />
        Save & New
    </el-dropdown-item>
</template>

<script lang="ts" setup>
import type { Payment } from "~/types/payment";
import moment from "moment";

const props = defineProps<{
    data?: Partial<Payment>;
    validateForm: () => Promise<void>;
    savePayment: () => Promise<any>;
}>();

const route = useRoute();
const { t } = useI18n();
const { $message, $swal } = useNuxtApp();
const { send } = usePageEvent();

const isPosted = computed(() => {
    return props.data?.status?.name?.toLowerCase() === "paid";
});

const handleSaveNew = async () => {
    if (isPosted.value) {
        $message("warning", t("make_payment.validation.cannot_edit"));
        return;
    }

    try {
        await props.validateForm();

        $swal("loading", {
            title: "Processing...",
            text: t("action.loading"),
        });

        const isUpdate = Boolean(route.params.uuid);
        const messageKey = isUpdate ? "action.updated" : "action.created";

        const result = await props.savePayment();

        await $swal("success", {
            title: t(messageKey),
            html: `Reference No.<br/>[ <b>${result.ref_no}</b> ]<br/>DV/Check No.<br/>[ <b>${result.check_no}</b> ]<br/>Trans. Date <br/>[ <b>${moment(result.date).format("MM/DD/YYYY")}</b> ]`,
        });

        send("on:create-new");
        navigateTo("/business/make-payments");
    } catch (error: any) {
        const errors = error?.errors ?? [];

        send("on:error", errors);

        const messages = [];

        for (const e of Object.values(errors)) {
            messages.push((e as string[])[0]);
        }

        let html = "<ol>";
        for (const m of messages) {
            html += `<li>${m}</li>`;
        }

        html += "</ol>";

        $swal("error", {
            title: error.message ?? t("error.failed_request"),
            html,
        });

        throw error;
    }
};
</script>
