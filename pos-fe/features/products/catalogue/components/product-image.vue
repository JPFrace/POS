<template>
    <Image ref="uploaderRef" @file-change="emit('file-change', $event)" />
</template>

<script lang="ts" setup>
const uploaderRef = ref();

const emit = defineEmits<{
    (e: "file-change", file: File | null): void;
}>();

defineExpose({
    setInitialFile: (url: string, name?: string) =>
        uploaderRef.value?.setInitialFile(url, name),
});
</script>
