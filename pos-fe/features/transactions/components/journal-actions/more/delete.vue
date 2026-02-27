<template>
    <AppPageMoreDelete
        :id="tab"
        endpoint="/api/business/journal-entries"
        :uuid="row.uuid"
        :title="`JE: ${row.je_no}`"
        :disabled="isPosted"
        :warning-message="isPosted ? t('journal.validation.cannot_delete') : ''"
    />
</template>

<script setup lang="ts">
const { t } = useI18n();

interface Props {
    row: any;
    tab: string;
}

const props = defineProps<Props>();

const isPosted = computed(() => 
    props.row.status.name.toLowerCase() === "posted"
);
</script>