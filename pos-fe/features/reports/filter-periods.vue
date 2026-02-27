<template>
    <div class="flex flex-col">
        <label class="form-label">Comparison period(s)</label>
        <Select v-model="model" v-model:data="data" />
    </div>
</template>

<script setup lang="ts">
const model = defineModel();

const data = ref([
    {
        uuid: "none",
        value: "none",
        label: "None",
    },
]);

onMounted(() => {
    model.value = data.value[0];
});
</script>
