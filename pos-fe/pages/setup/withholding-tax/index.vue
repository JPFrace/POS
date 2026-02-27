<template>
    <div>
        <NuxtLayout>
            <template #toolbarLeft>
                <AppPageSearch query="codeDesc" no-options />
            </template>
            <template #toolbarRight>
                <div class="space-x-standard">
                    <AppPageAdd
                        endpoint="/api/setup/withholding-tax"
                        width="40%"
                        width-lg="40%"
                        @before-show="send('clear')"
                    >
                        <template #drawerFooter="{ submit, cancel }">
                            <Button
                                variant="light"
                                class="ms-auto btn btn-light fw-semibold"
                                icon="black-left"
                                @click="cancel(() => send('clear'))"
                            >
                                <span>Cancel</span>
                            </Button>
                            <Button
                                variant="primary"
                                class="btn btn-primary fw-semibold"
                                icon="add-folder"
                                @click="submit"
                            >
                                <span>Submit</span>
                            </Button>
                        </template>
                        <template #form="{ errors, form, schema }">
                            <Form
                                :errors="errors"
                                :form="form"
                                :schema="schema"
                            />
                        </template>
                    </AppPageAdd>
                    <AppPageDeleteSel
                        endpoint="/api/setup/withholding-tax/selected"
                    />
                </div>
            </template>
            <div>
                <TableTaxes />
            </div>
        </NuxtLayout>
    </div>
</template>

<script lang="ts" setup>
import Form from "~/features/setup/withholding-taxes/form.vue";
import TableTaxes from "~/features/setup/withholding-taxes/table.vue";
import { usePageTitle } from "~/composables/usePageTitle";

usePageTitle();
definePageMeta({
    permission: "Setup.Withholding Taxes.View",
});

const { send } = usePageEvent();
</script>
