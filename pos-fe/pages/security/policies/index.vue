<template>
    <div>
        <NuxtLayout> <Table /> </NuxtLayout>
    </div>
</template>
<script lang="ts" setup>
import Table from "~/features/security/policies/table.vue";
import { usePageTitle } from "~/composables/usePageTitle";

usePageTitle();
// definePageMeta({
//     permission: "Security.Policies.View",
// });
</script>
