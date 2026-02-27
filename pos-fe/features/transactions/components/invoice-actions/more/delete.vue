<template>
    <AppPageMoreDelete
        :id="tab"
        endpoint="/api/business/invoices"
        :uuid="row.uuid"
        :title="'Invoice No: ' + row.invoice_no"
        :disabled="isPosted || isPaid"
        :warning-message="isPosted || isPaid ? t('invoice.validation.cannot_delete') : ''"
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

const isPaid = computed(() =>
    props.row.status.name.toLowerCase() === "paid")
</script>