<script setup lang="ts">
import AdminDashboard from "@/features/dashboard/components/admin-dashboard.vue";
import AccountantDashboard from "@/features/dashboard/components/accountant-dashboard.vue";

definePageMeta({
    showToolbar: false,
    isDashboard: true,
});
const userRole = "admin"; // later from store

const dashboardMap = {
    admin: AdminDashboard,
    accountant: AccountantDashboard,
};

const CurrentDashboard = dashboardMap[userRole];
</script>

<template>
    <NuxtLayout>
        <component :is="CurrentDashboard" />
    </NuxtLayout>
</template>
