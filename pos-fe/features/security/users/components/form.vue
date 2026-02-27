<template>
    <form class="space-y-4 py-1 px-1">
        <div class="flex flex-col">
            <span class="text-sm font-semibold mb-2 text-left">Name</span>
            <Input
                v-model="form.name"
                placeholder="Enter Name"
                :is-valid="isValid('name')"
            />
        </div>

        <div class="flex flex-col">
            <span class="text-sm font-semibold mb-2 text-left"
                >Email Address</span
            >
            <Input
                v-model="form.email"
                placeholder="Enter Email Address"
                :is-valid="isValid('email')"
            />
        </div>

        <div v-if="!hidden?.password" class="flex flex-col">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-semibold text-left">Password</span>
                <Checkbox
                    v-if="!hidden?.default_password"
                    v-model="form.default_password"
                    label="Default Password"
                    :is-valid="isValid('default_password')"
                    size="sm"
                />
            </div>
            <Input
                v-model="form.password"
                type="password"
                placeholder="Enter Password"
                size="md"
                :is-valid="isValid('password')"
                :disabled="form?.default_password || false"
            />
        </div>

        <div class="flex flex-col">
            <span class="text-sm font-semibold mb-2 text-left">Role</span>
            <AppRoles v-model="form.roles" :is-valid="isValid('roles')" />
        </div>

        <div class="flex flex-col mt-2">
            <Checkbox
                v-if="!hidden?.send_email_account"
                v-model="form.send_email_account"
                label="Send account to email."
                :is-valid="isValid('send_email_account')"
                size="sm"
            />
        </div>
    </form>
</template>

<script lang="ts" setup>
import { watch } from "vue";
import type { User } from "~/types/user";

interface Props {
    hidden?: Partial<User>;
}

const props = defineProps<Props>();
const errors = defineModel("errors");
const form = defineModel<Partial<User>>();

const isValid = (key: string) =>
    errors.value
        ? Object.keys(errors.value).includes(key)
            ? errors.value[key]?.length <= 0
            : null
        : null;

watch(
    form,
    (value) => {
        if (value?.default_password) {
            form.value.password = "";

            delete errors.value.password;
        }
    },
    {
        deep: true,
    }
);
</script>
