<template>
    <Form @submit.prevent="emits('submit')" ref="form" class="space-y-st-md">
        <Row v-for="row in entry.rows" :row="row" />

        <slot name="additional" />
        <slot name="submit" />
    </Form>
</template>

<script lang="ts" setup>
import Row from "./row.vue";
import Agreement from "./agreement.vue";
import type { Entry } from "~/types/builder";

const emits = defineEmits(["submit"]);

const entry = defineModel<Entry>("entry", {
    default: { rows: [] },
});

const agree = defineModel("agree");

const form = ref();

defineExpose({
    submit: () => form.value.submit(),
});
</script>
