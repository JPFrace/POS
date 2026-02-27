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

interface Props {
    endpoint: string;
    transform?: Function | null;
}

const key = id(useRoute().fullPath);
const props = defineProps<Props>();
const { $bus, $swal, $message } = useNuxtApp();
const { t } = useI18n();
const selected = ref<any[]>([]);

const transformSelected = computed(() => {
    if (props.transform) {
        return props.transform(selected);
    }

    return selected.value.map((row) => row.id);
});

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
                await useClient(
                    `${props.endpoint}/${transformSelected.value}`,
                    {
                        method: "DELETE",
                    }
                );

                $message("success", t("action.deleted_selected"));

                $bus.emit(`${key}:refresh`);
            } else if (res.isDismissed) {
                $message("error", t("action.delete_cancelled"));
            }
        })
        .catch((error: Throwable) => {
            $message("error", error?.message ?? t("error.failed_request"));
        });
};

onBeforeMount(() => {
    $bus.off(`${key}:select`);
});

onMounted(() => {
    $bus.on(`${key}:select`, (data: any[]) => {
        selected.value = data;
    });
});
</script>
