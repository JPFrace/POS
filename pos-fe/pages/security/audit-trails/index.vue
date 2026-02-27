<template>
    <div>
        <NuxtLayout>
            <template #toolbarLeft>
                <AppPageSearch query="name" no-options />
            </template>
            <div>
                <TableAccess />
            </div>
        </NuxtLayout>
    </div>
</template>

<script lang="ts" setup>
import TableAccess from "~/features/security/audit-trails/table.vue";
import { usePageTitle } from "~/composables/usePageTitle";

usePageTitle();
definePageMeta({
    permission: "Security.Audit Trails.View",
});

const { send } = usePageEvent();
</script>
