<template>
    <div>
        <NuxtLayout>
            <Tabs />
        </NuxtLayout>
    </div>
</template>

<script setup lang="ts">
import Tabs from "~/features/transactions/tabs.vue";
import { usePageTitle } from "~/composables/usePageTitle";

usePageTitle();

definePageMeta({
    permission: "Transactions.View",
});
</script>
