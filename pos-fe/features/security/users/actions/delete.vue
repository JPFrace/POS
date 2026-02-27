<template>
    <KTIcon
        title="Delete Record"
        icon-name="trash"
        icon-class="!text-3xl cursor-pointer !text-red-500 hover:!text-red-700 dark:hover:!text-red-400"
        icon-type="outline"
        @click="click"
    />
</template>

<script lang="ts" setup>
import type { Throwable } from "~/types/common";

const props = defineProps({
    uuid: {
        type: String,
    },
    title: {
        type: String,
    },
});

const { t } = useI18n();
const { $swal, $message, $bus } = useNuxtApp();

const click = () =>
    $swal("warning", {
        title: `User: ${props.title}`,
        text: t("action.delete_confirm"),
        showCancelButton: true,
        showConfirmButton: true,
        confirmButtonText: "Delete",
        cancelButtonText: "Cancel",
        reverseButtons: false,
    })
        .then(async (res: any) => {
            if (res.isConfirmed) {
                await useClient(`/api/security/users/${props.uuid}`, {
                    method: "DELETE",
                });

                $message("success", t("action.deleted"));

                $bus.emit("users:refresh");
            } else if (res.isDismissed) {
                $message("info", t("action.delete_canceled"));
            }
        })
        .catch((error: Throwable) => {
            $message("error", error?.message ?? t("error.failed_request"));
        });
</script>
