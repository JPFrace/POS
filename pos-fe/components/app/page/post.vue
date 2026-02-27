<template>
    <KTIcon
        :title="posted ? 'Posted' : 'Post'"
        icon-name="verify"
        :icon-class="iconClasses"
        @click="handleClick"
    />
</template>

<script lang="ts" setup>
import type { Throwable } from "~/types/common";

interface Props {
    id?: string;
    uuid: string;
    title: string;
    endpoint: string | ((data?: any) => string);
    data?: object | null;
    posted: boolean;
    after?: Function;
}

const props = defineProps<Props>();

const { t } = useI18n();
const { $swal, $message } = useNuxtApp();
const { send } = usePageEvent();

const loading = ref(false);

const iconClasses = computed(() => {
    if (props.posted) {
        return "!text-3xl cursor-not-allowed !text-green-700";
    }

    if (loading.value) {
        return "!text-3xl cursor-not-allowed !text-gray-400";
    }

    return "!text-3xl cursor-pointer !text-gray-400 hover:!text-green-600";
});

const resolveEndpoint = (data?: any) => {
    if (typeof props.endpoint === "function") {
        return props.endpoint(data);
    }

    return `${props.endpoint.replace(/\/$/, "")}/${props.uuid}`;
};

const handleClick = async () => {
    if (props.posted || loading.value) return;

    const result = await $swal("warning", {
        title: props.title,
        text: t("action.post_confirm"),
        showCancelButton: true,
        confirmButtonText: "Post",
        cancelButtonText: "Cancel",
        reverseButtons: false,
    });

    if (!result.isConfirmed) {
        $message("info", t("action.post_canceled"));
        return;
    }

    try {
        loading.value = true;

        await useClient(resolveEndpoint(props.data), {
            method: "POST",
        });

        $message("success", t("action.posted"));

        props.id && send("refresh", props.id);
        props.after?.();
    } catch (error: Throwable) {
        $message("error", error?.message ?? t("error.failed_request"));
    } finally {
        loading.value = false;
    }
};
</script>
