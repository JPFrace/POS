<template>
    <Drawer
        :id="id"
        title="Change Password"
        description="Update record"
        :processing="processing"
        @submit="submit"
        @cancel="props.drawer.hide()"
    >
        <div>
            <div class="flex flex-col mb-6">
                <span class="text-sm font-semibold mb-2 text-left">Email:</span>
                <label class="text-left">{{ props.data.email }}</label>
            </div>

            <div class="flex flex-col mb-6">
                <span class="text-sm font-semibold mb-2 text-left"
                    >New Password:</span
                >
                <Input
                    type="password"
                    v-model="form.newPassword"
                    placeholder="New Password..."
                    :is-valid="isValid('newPassword')"
                />
            </div>

            <div class="flex flex-col mb-6">
                <span class="text-sm font-semibold mb-2 text-left"
                    >Confirm Password:</span
                >
                <Input
                    type="password"
                    v-model="form.confirmPassword"
                    placeholder="Confirm Password..."
                    :is-valid="isValid('confirmPassword')"
                />
            </div>
        </div>
    </Drawer>
</template>

<script lang="ts" setup>
import type { User } from "~/types/user";
import Form from "../components/form.vue";
import type { Option } from "~/types/form";
import type { Throwable } from "~/types/common";
import { method } from "lodash";

interface Props {
    data: User;
    drawer: any;
}

const props = defineProps<Props>();
const id = computed(() => `change_pass_${props.data.uuid}`);

const form = ref({
    newPassword: "",
    confirmPassword: "",
});

const { t } = useI18n();
const { $message, $bus, $drawer } = useNuxtApp();
const { validate, yup } = useYup();

const processing = ref(false);
const errors = ref([]);

const isValid = (key: string) =>
    errors.value
        ? Object.keys(errors.value).includes(key)
            ? errors.value[key]?.length <= 0
            : null
        : null;

//Create form validation object
const Yup = yup();
const schema = Yup.object().shape({
    newPassword: Yup.string()
        .min(8, "Minimum length is 8 characters")
        .required("New password is required"),
    confirmPassword: Yup.string()
        .min(8, "Minimum length is 8 characters")
        .required("Confirm password is required")
        .oneOf([Yup.ref("newPassword")], "Password mismatch"),
});

const submit = async () => {
    try {
        processing.value = true;

        await validate(schema, { data: form.value });

        await useClient(
            `/api/security/users/change-password-user/${props.data.uuid}`,
            {
                method: "PATCH",
                body: {
                    password: form.value.newPassword,
                    password_confirmation: form.value.confirmPassword,
                },
            }
        );

        processing.value = false;

        errors.value = [];

        $message("success", t("action.saved"));

        props.drawer.hide();
    } catch (error: Throwable) {
        errors.value = error.errors;
        processing.value = false;
        $message("error", error.message ?? t("error.failed_request"));
    }
};
</script>
