<template>
    <el-dropdown-item :disabled="isPosted" @click="handleSave">
        <KTIcon
            icon-name="save-2"
            icon-class="fs-2 cursor-pointer hover:dark:text-white hover:text-slate-700"
        />
        Save
    </el-dropdown-item>
</template>

<script lang="ts" setup>
import type { OfficialReceipt } from "~/types/official-receipt";
import moment from "moment";

const props = defineProps<{
    data?: Partial<OfficialReceipt>;
    validateForm: () => Promise<void>;
    saveReceipt: () => Promise<any>;
}>();

const route = useRoute();
const { t } = useI18n();
const { $message, $swal } = useNuxtApp();
const { send } = usePageEvent();

const isPosted = computed(() => {
    return props.data?.status?.name?.toLowerCase() === "posted";
});

const handleSave = async () => {
    if (isPosted.value) {
        $message("warning", t("money_receive.validation.cannot_edit"));
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

        const result = await props.saveReceipt();

        const receiveMoneyUUID = isUpdate
            ? (route.params.uuid as string)
            : result.uuid;

        await $swal("success", {
            title: "Entry",
            text: t(messageKey),
        });

        navigateTo(`/business/receive-money/${receiveMoneyUUID}`);
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
