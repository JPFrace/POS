<template>
    <AppPageMoreDelete
        :id="tab"
        endpoint="/api/business/bills"
        :uuid="row.uuid"
        :title="'Bill No: ' + row.bill_no"
        :disabled="isPosted"
        :warning-message="isPosted ? t('bill.validation.cannot_delete') : ''"
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