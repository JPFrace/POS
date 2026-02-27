<template>
    <div>
        <NuxtLayout>
            <template #toolbarLeft>
                <AppPageSearch query="name" no-options />
            </template>
            <template #toolbarRight>
                <div class="space-x-standard">
                    <AppPageAdd
                        endpoint="/api/setup/reports"
                        width="40%"
                        width-lg="40%"
                        @before-show="send('clear')"
                    >
                        <template #drawerFooter="{ submit, cancel }">
                            <Button
                                variant="light"
                                class="ms-auto w-25 btn btn-light fw-semibold"
                                icon="black-left"
                                @click="cancel(() => send('clear'))"
                            >
                                <span>Cancel</span>
                            </Button>
                            <Button
                                variant="primary"
                                class="w-25 btn btn-primary fw-semibold"
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
                    <AppPageDeleteSel endpoint="/api/setup/reports/selected" />
                </div>
            </template>
            <div>
                <TableReports />
            </div>
        </NuxtLayout>
    </div>
</template>

<script lang="ts" setup>
import Form from "~/features/setup/reports/form.vue";
import TableReports from "~/features/setup/reports/table.vue";
import { usePageTitle } from "~/composables/usePageTitle";

usePageTitle();
definePageMeta({
    permission: "Setup.Setup Reports.View",
});

const { send } = usePageEvent();
</script>
