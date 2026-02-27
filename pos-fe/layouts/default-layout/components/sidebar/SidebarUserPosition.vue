<template>
    <div class="flex items-center justify-center gap-x-2 my-4 text-sm">
        <span
            v-for="position in positions"
            class="dark:text-white text-white border border-white px-4 py-1 rounded-full"
            >{{ position }}</span
        >
    </div>
</template>

<script lang="ts" setup>
const auth = useSanctumAuth();

const positions = computed(() =>
    auth.user.value.roles.map((row) => row.role.name.toUpperCase()),
);
</script>
