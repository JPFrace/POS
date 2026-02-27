<template>
    <KTIcon
        title="Unpost"
        icon-name="file-left"
        icon-type="outline"
        :icon-class="iconClass"
        @click="handleUnpost"
    />
</template>

<script setup lang="ts">
const { t } = useI18n();
const { $message, $swal } = useNuxtApp();

interface Props {
    row: any;
    onSuccess?: () => void;
}

const props = defineProps<Props>();

const isPosted = computed(
    () => props.row.status.name.toLowerCase() === "posted"
);

const iconClass = computed(() =>
    isPosted.value
        ? "!text-3xl cursor-pointer !text-yellow-500 hover:!text-yellow-700 dark:hover:!text-yellow-400"
        : "!text-3xl cursor-not-allowed !text-gray-400"
);

const handleUnpost = async () => {
    if (!isPosted.value) {
        $message("info", t("make_payment.validation.not_posted"));
        return;
    }

    const result = await $swal("warning", {
        html: `Are you sure you want to unpost <strong>Reference No. ${props.row.ref_no}?</strong>`,
        showCancelButton: true,
        confirmButtonText: "Yes",
        cancelButtonText: "Cancel",
        reverseButtons: false,
    });

    if (!result.isConfirmed) {
        return;
    }

    try {
        await useClient("/api/business/payments/update-status", {
            method: "PATCH",
            body: {
                uuids: [props.row.uuid],
                status: "unposted",
            },
        });

        $message("success", `${t("action.unposted")}`);
        props.onSuccess?.();
    } catch (error: any) {
        $message(
            "error",
            error?.data?.message ??
                t("make_payment.error.failed_to_unpost")
        );
    }
};
</script>