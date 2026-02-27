<template>
    <Button
        :disabled="!selected.length"
        class="disabled:bg-slate-500"
        variant="danger"
        icon="trash"
        @click="click"
        >Delete</Button
    >
</template>

<script lang="ts" setup>
import type { Throwable } from "~/types/common";
import type { User } from "~/types/user";

const { $bus, $swal, $message } = useNuxtApp();
const { t } = useI18n();
const selected = ref<User[]>([]);

const click = () => {
    $swal("warning", {
        title: `[${selected.value.length}] selected`,
        text: t("action.delete_selected_confirm"),
        showCancelButton: true,
        showConfirmButton: true,
        confirmButtonText: "Delete",
        cancelButtonText: "Cancel",
    })
        .then(async (res: any) => {
            if (res.isConfirmed) {
                await useClient(`/api/security/users/selected`, {
                    method: "DELETE",
                    body: {
                        users: selected.value.map((row) => row.uuid),
                    },
                });

                $message("success", t("action.deleted_selected"));

                $bus.emit("users:refresh");
            } else if (res.isDismissed) {
                $message("error", t("action.delete_cancelled"));
            }
        })
        .catch((error: Throwable) => {
            $message("error", error?.message ?? t("error.failed_request"));
        });
};

onBeforeMount(() => {
    $bus.off("users:select");
});

onMounted(() => {
    $bus.on("users:select", (users: User[]) => {
        selected.value = users;
    });
});
</script>
