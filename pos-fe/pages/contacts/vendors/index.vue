<template>
    <div>
        <NuxtLayout>
            <template #toolbarLeft>
                <AppPageSearch
                    query="name_or_id"
                    no-options
                    placeholder="Search by Name & ID No."
                />
            </template>
            <template #toolbarRight>
                <div class="space-x-standard">
                    <AppPageAdd
                        endpoint="/api/contacts/vendors"
                        width="70%"
                        width-lg="40%"
                        @before-show="() => send('clear')"
                        @success="send('clear')"
                    >
                        <template #drawerFooter="{ submit, cancel }">
                            <Button
                                variant="light"
                                class="ms-auto btn btn-light fw-semibold"
                                icon="black-left"
                                @click="
                                    () => {
                                        send('clear');
                                        cancel();
                                    }
                                "
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
                        endpoint="/api/contacts/vendors/selected"
                    />
                </div>
            </template>
            <div>
                <TableAccess />
            </div>
        </NuxtLayout>
    </div>
</template>

<script lang="ts" setup>
import Form from "~/features/contacts/vendors/form.vue";
import TableAccess from "~/features/contacts/vendors/table.vue";
import { usePageTitle } from "~/composables/usePageTitle";

usePageTitle();
definePageMeta({
    permission: "Contacts.Vendors.View",
});

const { send } = usePageEvent();
</script>
