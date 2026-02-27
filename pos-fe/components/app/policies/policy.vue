<template>
    <div>
        <div
            v-for="policy in policies"
            :key="policy.uuid"
            class="flex items-center justify-start gap-x-st"
        >
            <div class="flex flex-col gap-y-st w-full">
                <div
                    class="w-full rounded-md p-st"
                    :class="{
                        'bg-slate-100': parent ? false : true,
                    }"
                >
                    {{ policy.name }}
                </div>
                <AppPoliciesPolicy
                    v-if="policy?.children?.length"
                    :policies="policy?.children ?? []"
                    :parent="policy"
                    :roles="roles"
                    :permissions="permissions"
                    class="flex flex-col gap-y-st w-full"
                />
                <div
                    v-else
                    v-for="action in policy.actions"
                    class="flex items-center justify-start gap-x-st w-full pl-4"
                >
                    <div class="w-1/2 pl-4">{{ action.name }}</div>
                    <div
                        v-for="role in roles"
                        :key="role.uuid"
                        class="flex-1 flex items-center justify-center"
                    >
                        <Checkbox
                            @update:model-value="
                                (checked) =>
                                    set(role.uuid, action.uuid, checked)
                            "
                            :checked="can(role.uuid, action.uuid)"
                        />
                    </div>
                    <div class="flex-1 flex items-center justify-center">
                        <Checkbox />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script lang="ts" setup>
import type { Permission, Policy, Role } from "~/types/policy";
const props = defineProps<{
    parent?: Policy;
    policies: Policy[];
    roles: Role[];
    permissions: Role[];
}>();

const { $message } = useNuxtApp();
const { t } = useI18n();

const set = async (role: string, action: string, checked: Boolean) => {
    try {
        await useClient(`/api/security/policies/set/${role}/${action}`, {
            method: "POST",
            body: {
                checked,
            },
        });

        $message("success", t("action.saved"));
    } catch (error) {
        $message("error", t("action.failed_request"));
    }
};

const can = (role: string, action: string) => {
    const permissions =
        props.permissions.filter((row: Role) => row.uuid == role)[0] ?? null;

    if (permissions?.permissions) {
        return (
            permissions.permissions.filter(
                (row: Permission) => row.action.uuid == action
            ).length > 0
        );
    }

    return false;
};

/** Collect all action UUIDs under a policy (self and descendants). */
function getAllActionUuids(policy: Policy): string[] {
    if (policy.actions?.length) {
        return policy.actions.map((a) => a.uuid);
    }
    const uuids: string[] = [];
    (policy.children ?? []).forEach((child) => {
        uuids.push(...getAllActionUuids(child));
    });
    return uuids;
}

function isAllCheckedForRole(policy: Policy, roleUuid: string): boolean {
    const actionUuids = getAllActionUuids(policy);
    if (actionUuids.length === 0) return false;
    return actionUuids.every((actionUuid) => can(roleUuid, actionUuid));
}

function isIndeterminateForRole(policy: Policy, roleUuid: string): boolean {
    const actionUuids = getAllActionUuids(policy);
    if (actionUuids.length === 0) return false;
    const checkedCount = actionUuids.filter((actionUuid) =>
        can(roleUuid, actionUuid)
    ).length;
    return checkedCount > 0 && checkedCount < actionUuids.length;
}

async function setAllForRole(
    policy: Policy,
    roleUuid: string,
    checked: boolean
): Promise<void> {
    const actionUuids = getAllActionUuids(policy);
    for (const actionUuid of actionUuids) {
        await set(roleUuid, actionUuid, checked);
    }
}
</script>
