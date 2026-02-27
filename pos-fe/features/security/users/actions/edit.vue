<template>
    <KTIcon
        :id="`${id}_toggle`"
        title="Edit Record"
        icon-name="notepad-edit"
        icon-class="!text-3xl cursor-pointer !text-blue-500 hover:!text-blue-700 dark:hover:!text-blue-400"
        icon-type="outline"
    />

    <Drawer
        :id="id"
        title="Edit"
        description="Update record"
        :processing="processing"
        @submit="submit"
        @cancel="drawer.hide()"
    >
        <Form
            v-model="form"
            v-model:errors="errors"
            :hidden="{
                password: true,
                default_password: true,
                send_email_account: true,
            }"
        />
    </Drawer>
</template>

<script lang="ts" setup>
import type { User } from "~/types/user";
import Form from "../components/form.vue";
import type { Option } from "~/types/form";
import type { Throwable } from "~/types/common";

interface Props {
    data: User;
}

const props = defineProps<Props>();
const id = computed(() => `user_edit_${props.data.uuid}`);
const drawer = ref();

const { t } = useI18n();
const { $message, $bus, $drawer } = useNuxtApp();
const { validate, yup } = useYup();

const form = ref<User>({
    name: props.data.name,
    email: props.data.email,
    roles: (props.data.roles ?? []).map(
        (row: any) =>
            ({
                id: row.role.uuid,
                uuid: row.role.uuid,
                value: row.role.uuid,
                label: row.role.name,
            }) as Option
    ),
    default_password: props.data.default_password ?? false,
    send_email_account: props.data.send_email_account ?? false,
    password: props.data.password ?? null,
});

const execute = () =>
    useClient(`/api/security/users/${props.data.uuid}`, {
        method: "PATCH",
        body: form.value,
    });

const processing = ref(false);
const errors = ref([]);

//Create form validation object
const Yup = yup();
const schema = Yup.object().shape({
    email: Yup.string().email().required(),
    name: Yup.string().required(),
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

        processing.value = false;

        errors.value = [];

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
