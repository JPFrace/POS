<template>
    <el-button type="primary" :disabled="isDefault" @click="setDefault">
        Set Default
    </el-button>
</template>

<script lang="ts" setup>
import type { Method, Throwable } from "~/types/common";
const { $message, $bus } = useNuxtApp();

interface Props {
    id: string;
    endpoint: string | Function;
    method?: Method["method"];
    isDefault: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    method: "PATCH",
});

const endpoint = (): string => {
    let endpoint = props.endpoint;
    if (typeof endpoint == "function") {
        endpoint = endpoint();
    }

    return endpoint as string;
};

const execute = (data: any) => {
    return useClient(endpoint(), {
        method: props.method,
        body: data,
    });
};

const processing = ref(false);
const errors = ref([]);
const key = id(useRoute().fullPath);
const { t } = useI18n();

const setDefault = async () => {
    try {
        if (processing.value) return;

        processing.value = true;

        await execute();

        processing.value = false;

        errors.value = [];

        $message("success", t("action.saved"));

        $bus.emit(`${key}:refresh`);
    } catch (error: Throwable) {
        errors.value = error?.errors ?? [];
        processing.value = false;
        $message("error", error.message ?? t("error.failed_request"));
    }
};
</script>
