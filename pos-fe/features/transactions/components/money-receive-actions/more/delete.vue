<template>
    <AppPageMoreDelete
        :id="tab"
        endpoint="/api/business/official-receipts"
        :uuid="row.uuid"
        :title="'OR: ' + row.or_no"
        :disabled="isPosted"
        :warning-message="isPosted ? t('money-receive.validation.cannot_delete') : ''"
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