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
const { $confirm, $message } = useNuxtApp();

const message = "Your message here.";

const success = () => $message("success", message);

const warning = () => $message("warning", message);

const info = () => $message("info", message);

const error = () => $message("error", message);
</script>
