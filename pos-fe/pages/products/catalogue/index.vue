<template>
    <div>
        <NuxtLayout>
            <template #toolbarLeft>
                <AppPageSearch query="name" no-options />
            </template>
            <template #toolbarRight>
                <div class="space-x-standard">
                    <AppPageAdd
                        endpoint="/api/products/products"
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
                                @click="submit(() => send('all'))"
                            >
                                <span>Submit</span>
                            </Button>
                        </template>
                        <template #form="{ errors, form, syncForm, schema }">
                            <Tabs
                                :errors="errors"
                                :form="syncForm"
                                :schema="schema"
                            />
                        </template>
                    </AppPageAdd>
                </div>
            </template>
            <div>
                <TableProducts />
            </div>
        </NuxtLayout>
    </div>
</template>

<script lang="ts" setup>
import TableProducts from "~/features/products/catalogue/table.vue";
import Tabs from "~/features/products/catalogue/tabs.vue";

import { usePageTitle } from "~/composables/usePageTitle";

usePageTitle();
definePageMeta({
    permission: "Products & Services.Catalogue.View",
});

const { send } = usePageEvent();
</script>
