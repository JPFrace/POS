<template>
    <div>
        <NuxtLayout>
            <template #toolbarLeft>
                <AppPageSearch query="name" no-options />
            </template>
            <template #toolbarRight>
                <div class="space-x-standard">
                    <AppPageAdd
                        endpoint="/api/setup/payment-types"
                        width="30%"
                        width-lg="30%"
                        @before-show="send('clear')"
                    >
                        <template #drawerFooter="{ submit, cancel }">
                            <Button
                                variant="light"
                                class="ms-auto btn btn-light fw-semibold"
                                icon="black-left"
                                @click="
                                    () => {
                                        cancel(() => send('clear'));
                                        resetKey++;
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
                                :reset-key="resetKey"
                            />
                        </template>
                    </AppPageAdd>
                    <AppPageDeleteSel
                        endpoint="/api/setup/payment-types/selected"
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
import { ref } from "vue";
import Form from "~/features/setup/payment-types/form.vue";
import TableAccess from "~/features/setup/payment-types/table.vue";
import { usePageTitle } from "~/composables/usePageTitle";

usePageTitle();
definePageMeta({
    permission: "Setup.Payment Types.View",
});
const { send } = usePageEvent();

const resetKey = ref(0);
</script>
