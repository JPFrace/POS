<template>
    <div class="flex flex-wrap gap-y-2 gap-x-4">
        <Button type="submit" variant="success" @click="success"
            >Success</Button
        >
        <Button type="submit" variant="warning" @click="warning"
            >Warning</Button
        >
        <Button type="submit" variant="info" @click="info">Info</Button>
        <Button type="submit" variant="danger" @click="error">Error</Button>
    </div>
</template>
<script lang="ts" setup>
const { $success, $warning, $info, $error } = useNuxtApp();

const title = "Title";

const message = "Your message here.";

const success = () => $success(title, message);

const warning = () => $warning(title, message);

const info = () => $info(title, message);

const error = () => $error(title, message);
</script>
