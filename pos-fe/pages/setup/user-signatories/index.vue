<template>
    <div>
        <NuxtLayout>
            <template #toolbarLeft>
                <AppPageSearch query="name" no-options />
            </template>
            <template #toolbarRight>
                <div class="space-x-standard">
                    <AppPageAdd
                        endpoint="/api/setup/signatories/"
                        width="60%"
                        width-lg="60%"
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
                    <AppPageDeleteSel endpoint="/api/setup/signatories/" />
                </div>
            </template>
            <div>
                <UserSignatories />
            </div>
        </NuxtLayout>
    </div>
</template>

<script lang="ts" setup>
import Form from "~/features/setup/user-signatories/form.vue";
import UserSignatories from "~/features/setup/user-signatories/table.vue";
import { usePageTitle } from "~/composables/usePageTitle";

usePageTitle();
definePageMeta({
    permission: "Setup.Signatories.View",
});

const { send } = usePageEvent();
</script>
