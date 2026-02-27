<template>
    <div class="flex flex-col gap-y-st">
        <div
            class="flex items-center justify-start gap-x-st uppercase sticky top-24 p-st bg-primary text-white"
        >
            <div class="w-1/2">ACTIONS</div>
            <div
            v-for="role in roles"
            class="flex-1 flex items-center justify-center"
            >
            {{ role.display_name }}
            </div>
            <div class="flex-1 flex items-center justify-center gap-x-1">
                user 
                <!-- <Badge variant="success" label="custom" /> -->
            </div>
        </div>
        <AppPoliciesPolicy
            :policies="policies"
            :roles="roles"
            :permissions="permissions"
            class="flex flex-col gap-y-st w-full"
        />
    </div>
</template>
<script lang="ts" setup>
import type { Policy, Role } from "~/types/policy";

const route = useRoute();
const { data: policiesData } = useAsyncData(
    `${route.path}_policies`,
    () =>
        useClient("/api/security/policies/list", {
            method: "POST",
            body: {
                query: { actions: true, "children.actions": true, root: true },
                page: 1,
                size: 20,
            },
        }),
    {
        server: false,
        lazy: true,
    }
);

const { data: rolesData } = useAsyncData(
    `${route.path}_security_roles`,
    () =>
        useClient("/api/security/roles/list", {
            method: "POST",
        }),
    {
        server: false,
        lazy: true,
    }
);

const fetchPermissions = (role: string) =>
    useClient(`/api/security/roles/${role}/permissions`, {
        method: "POST",
        body: {
            query: { "permissions.action": true },
        },
    }).then((response) => {
        permissions.value = permissions.value.concat(response as Role);
    });

const roles = ref<Role[]>([]);
const policies = ref<Policy[]>([]);
const permissions = ref<Role[]>([]);

watch(rolesData, (value) => {
    roles.value = (value?.data ?? []) as Role[];
});

watch(policiesData, (value) => {
    policies.value = (value?.data ?? []) as Policy[];
});

watch(roles, (value) => {
    value.forEach((role: Role) => {
        fetchPermissions(role.uuid);
    });
});
</script>
