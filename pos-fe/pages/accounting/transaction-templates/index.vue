<template>
    <div>
        <NuxtLayout>
            <template #toolbarLeft>
                <AppPageSearch
                    query="name"
                    no-options
                    placeholder="Search by Name"
                />
            </template>
            <template #toolbarRight>
                <div class="space-x-standard">
                    <AppPageAdd
                        endpoint="/api/accounting/transaction-templates"
                        width="80%"
                        width-lg="50%"
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
                        endpoint="/api/accounting/transaction-templates/selected"
                    />
                </div>
            </template>
            <div>
                <Table />
            </div>
        </NuxtLayout>
    </div>
</template>

<script lang="ts" setup>
import Form from "~/features/accounting/transaction-templates/form.vue";
import Table from "~/features/accounting/transaction-templates/table.vue";
import { usePageTitle } from "~/composables/usePageTitle";

usePageTitle();
const { send } = usePageEvent();

definePageMeta({
    permission: "Accounting.Transaction Templates.View",
});
</script>
