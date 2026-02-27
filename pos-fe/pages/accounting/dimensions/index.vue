<template>
    <div>
        <NuxtLayout>
            <template #toolbarLeft>
                <AppPageSearch query="name_code"> </AppPageSearch>
            </template>
            <template #toolbarRight>
                <div class="space-x-standard">
                    <AppPageAdd
                        endpoint="/api/accounting/dimensions"
                        width="60%"
                        width-lg="30%"
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
                        endpoint="/api/accounting/dimensions/selected"
                    />
                </div>
            </template>
            <div>
                <Dimensions />
            </div>
        </NuxtLayout>
    </div>
</template>
<script lang="ts" setup>
import Form from "~/features/accounting/dimensions/form.vue";
import Dimensions from "~/features/accounting/dimensions/table.vue";
import { usePageTitle } from "~/composables/usePageTitle";

usePageTitle();
const { send } = usePageEvent();

definePageMeta({
    permission: "Accounting.Dimensions.View",
});
</script>
