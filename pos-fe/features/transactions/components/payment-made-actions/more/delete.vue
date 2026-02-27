<template>
    <AppPageMoreDelete
        :id="tab"
        endpoint="/api/business/payments"
        :uuid="row.uuid"
        :title="'Ref No: ' + row.ref_no"
        :disabled="isPosted"
        :warning-message="isPosted ? t('make_payment.validation.cannot_delete') : ''"
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
    props.row.status.name.toLowerCase() === "paid"
);
</script>