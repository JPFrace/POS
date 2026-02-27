<template>
    <!-- Element plus tag use in Transactions -->
    <el-tag round effect="dark" :type="type" :label="props.label">
        {{ props.label }}
    </el-tag>
</template>

<script setup lang="ts">
import type { TagProps } from "element-plus";

interface Props {
    label: string;
    status?: string;
    type?: TagProps["type"];
}

const props = withDefaults(defineProps<Props>(), {
    type: undefined,
    status: "info",
});

const getStatusType = (
    status: string
): "success" | "warning" | "danger" | "info" | "primary" => {
    const statusMap: Record<
        string,
        "success" | "warning" | "danger" | "info" | "primary"
    > = {
        posted: "success",
        paid: "primary",
        partial: "info",
        pending: "warning",
        unpaid: "warning",
        canceled: "danger",
        voided: "danger",
        draft: "info",
        unposted: "warning",
        inactive: "danger",
        active: "success",
    };
    return statusMap[status?.toLowerCase()] || "info";
};

// Use provided type or derive from status
const type = computed(() => {
    if (props.type) return props.type;
    if (props.status) return getStatusType(props.status);
    return "info";
});
</script>
