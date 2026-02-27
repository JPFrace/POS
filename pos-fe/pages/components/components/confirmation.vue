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

const title = "Title";

const message = "Your message here.";

const cancelled = "You cancelled it.";

const success = () =>
    $confirm("success", title, message)
        .then(() => $message("success", message))
        .catch(() => $message("error", cancelled));

const warning = () =>
    $confirm("warning", title, message)
        .then(() => $message("warning", message))
        .catch(() => $message("error", cancelled));

const info = () =>
    $confirm("info", title, message)
        .then(() => $message("info", message))
        .catch(() => $message("error", cancelled));

const error = () =>
    $confirm("error", title, message)
        .then(() => $message("error", message))
        .catch(() => $message("error", cancelled));
</script>
