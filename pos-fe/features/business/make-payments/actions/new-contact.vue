<template>
    <div
        @click="() => (drawerOpen = !drawerOpen)"
        class="flex items-center justify-between text-start bg-gray-100 px-3 py-2 cursor-pointer"
    >
        <span>Create a new contact</span>
        <KTIcon icon-name="plus-square" icon-class="fs-2x" />
    </div>
    <Teleport to="body">
        <Drawer
            id="add-new-customer"
            title="New Contact"
            description="Simplest and easy way to add a new contact"
            :processing="processing"
            width="70%"
            width-lg="40%"
            @submit="submit"
            @cancel="cancel"
        >
            <template #footer>
                <Button
                    variant="light"
                    class="btn btn-light w-25 ms-auto fw-semibold"
                    icon="black-left"
                    @click="cancel()"
                >
                    <span>Cancel</span>
                </Button>
                <Button
                    variant="primary"
                    class="btn btn-primary w-25 fw-semibold"
                    icon="add-folder"
                    @click="submit"
                >
                    <span>Submit</span>
                </Button>
            </template>
            <Form :errors="errors" :form="syncForm" :schema="schemaSync" />
        </Drawer>
    </Teleport>
</template>

<script lang="ts" setup>
import { ref } from "vue";
import type { AnyObject } from "yup";
import type { Throwable, Method } from "~/types/common";

import Form from "~/features/contacts/customers/form.vue";
const { send } = usePageEvent();

const form = ref<unknown>();
const processing = ref(false);
const drawer = ref();
const errors = ref([]);
const schema = ref<AnyObject>();
const drawerOpen = ref(false);

const { t } = useI18n();
const { validate } = useYup();
const { $message, $drawer } = useNuxtApp();

const validateForm = () => {
    return validate(schema.value, { data: form.value });
};

const execute = () =>
    useClient("api/contacts/vendors", {
        method: "POST",
        body: form.value,
    });

const syncForm = (value: Ref) => {
    form.value = value;
};

const schemaSync = (value: AnyObject) => {
    schema.value = value;
};

const cancel = () => {
    drawer.value.hide();
    drawerOpen.value = false;
};

const submit = async () => {
    try {
        if (processing.value) return;

        processing.value = true;

        await validateForm();

        await execute();

        form.value = clearKeyValue(form.value);
        errors.value = [];

        processing.value = false;

        $message("success", t("action.created"));

        cancel();

        send(`refresh`);
    } catch (error: Throwable) {
        errors.value = error.errors;
        processing.value = false;
        $message("error", error.message ?? t("error.failed_request"));
    }
};

watch(drawerOpen, (value) => {
    if (value) {
        drawer.value.show();
    } else {
        drawer.value.hide();
    }
});

onMounted(() => {
    drawer.value = $drawer(`add-new-customer`);
});
</script>
