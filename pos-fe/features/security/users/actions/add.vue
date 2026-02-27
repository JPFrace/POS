<template>
    <Button id="user_add_toggle" variant="primary" icon="plus-square"
        >Add New</Button
    >
    <Drawer
        id="user_add"
        title="Add New"
        description="Add new record"
        :processing="processing"
        @submit="submit"
        @cancel="drawer.hide()"
    >
        <Form v-model="form" v-model:errors="errors" />
    </Drawer>
</template>

<script lang="ts" setup>
import { ref } from "vue";
import type { User } from "~/types/user";
import Form from "../components/form.vue";
import type { Throwable } from "~/types/common";

const { t } = useI18n();
const { validate, yup } = useYup();
const { $message, $bus, $drawer } = useNuxtApp();

const form = ref<Partial<User>>({
    name: "",
    email: "",
    roles: [],
    default_password: false,
    send_email_account: true,
    password: null,
});

const id = computed(() => `user_add`);
const processing = ref(false);
const drawer = ref();
const errors = ref([]);

const execute = () =>
    useClient("/api/security/users", {
        method: "POST",
        body: form.value,
    });

//Create form validation object
const Yup = yup();
const schema = Yup.object().shape({
    email: Yup.string().email().required(),
    name: Yup.string().required(),
    default_password: Yup.boolean().notRequired(),
    password: Yup.string().when("default_password", {
        is: false,
        then: (schema) => schema.min(6).required(),
    }),
    roles: Yup.array(Yup.object().shape({ uuid: Yup.string() }))
        .ensure()
        .min(1)
        .required(),
});

const submit = async () => {
    try {
        processing.value = true;

        await validate(schema, { data: form.value });

        await execute();

        form.value = clearKeyValue(form.value);
        errors.value = [];

        processing.value = false;

        $message("success", t("action.saved"));

        drawer.value.hide();

        $bus.emit("users:refresh");
    } catch (error: Throwable) {
        errors.value = error.errors;
        processing.value = false;
        $message("error", error.message ?? t("error.failed_request"));
    }
};

onMounted(() => {
    drawer.value = $drawer(id.value);
});
</script>
