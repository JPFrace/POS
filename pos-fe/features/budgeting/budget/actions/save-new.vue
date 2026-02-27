<template>
    <div class="flex gap-x-2">
        <NuxtLink to="/budgeting/annual-budgets">
            <Button type="button" variant="secondary" icon="black-left">Back</Button>
        </NuxtLink>
        <Button variant="primary" label="Save & New" class="!uppercase" @click="submit()" :disabled="data?.isPosted" />
        <More @save="submit(true)" @save-as="saveAsNew(data?.uuid)" :is-posted="data?.isPosted" />
    </div>
</template>

<script lang="ts" setup>
import More from "./more.vue";
import type { Budget, BudgetItem } from "~/types/budget";
import { isEmpty } from "lodash";

const route = useRoute();

const { t } = useI18n();
const { validate } = useYup();
const { $message, $swal } = useNuxtApp();
const { yup } = useYup();
const { send } = usePageEvent();

const Yup = yup();

const data = defineModel<Partial<Budget>>();
const validateForm = () => {
    const form = Yup.object().shape({
        department: Yup.object().notRequired(),
        calendar: Yup.object().required(),
        type: Yup.object().notRequired(),
        name: Yup.string().required(),
        description: Yup.string().notRequired(),
        items: Yup.array()
            .compact((value: any) => value.account == null)
            .min(1)
            .of(
                Yup.object().shape({
                    active: Yup.boolean().notRequired(),
                    account: Yup.object().notRequired(),
                    account_value: Yup.object().when(
                        ["active", "account"],
                        ([active, account], schema) =>
                            active == true && isEmpty(account)
                                ? schema.required()
                                : schema.notRequired()
                    ),
                    amount: Yup.number()
                        .transform((current: any, original: any) => {
                            return original ? numberOnly(original) : 0;
                        })
                        .min(0)
                        .notRequired(),
                    description: Yup.string().notRequired(),
                })
            ),
    });

    return validate(form, {
        data: data.value ?? {},
    });
};

const submit = async (saveOnly: boolean = false) => {
    const formData = new FormData();
    const formValue = (data.value ?? {}) as Record<string, any>;
    const keys = Object.keys(formValue);

    for (const key of keys) {
        const value = formValue[key];
        if (
            key === "department" || key === "calendar" || key === "type"
        ) {
            formData.append(
                key,
                JSON.stringify(value, (jsonKey, jsonValue) =>
                    jsonValue === null ? "" : jsonValue
                )
            );
        }
        else if (key === "items") {
            const normalizedItems = Array.isArray(value)
                ? value.map(item => {
                    const amount = parseFloat(
                        String(item.amount ?? "0")
                            .replace(/,/g, "")
                    );

                    console.log('Normalized Item:', {
                        account: item.account.id,
                        amount: item.amount,
                        description: item.description
                    });
                    return {
                        account: item.account ? {
                            id: item.account.id,
                            value: item.account.id,
                            label: item.account.name
                        } : null,
                        amount: Number.isFinite(amount)
                            ? Number(amount.toFixed(2))
                            : 0.00,
                        description: item.description || ""
                    };
                }).filter(item => item.amount !== 0)
                : [];

            formData.append(key, JSON.stringify(normalizedItems));
        }
        else {
            formData.append(key, value === null ? "" : value);
        }
    }

    try {
        await validateForm();

        const isUpdate = Boolean(route.params.uuid);
        const url = isUpdate
            ? `/api/budgeting/budget/${route.params.uuid}`
            : "/api/budgeting/budget";
        const method = isUpdate ? "PUT" : "POST";
        const messageKey = isUpdate ? "action.updated" : "action.created";

        const result = await useClient(url, {
            method,
            body: formData,
        });

        const budgetUUID = isUpdate
            ? (route.params.uuid as string)
            : result.uuid;

        await $swal("success", {
            title: t(messageKey),
            html:
                `<b>${formValue.name}</b><br/>${formValue.description || ''}`
        });

        if (saveOnly && budgetUUID) {
            navigateTo(`/budgeting/budget/${budgetUUID}`);
            return;
        }
        // Default = Save & New
        setTimeout(() => {
            send("on:create-new");
        }, 300);

        navigateTo("/budgeting/budget/new-budget");

    } catch (error: any) {
        const errors = error?.errors ?? [];

        send("on:error", errors);

        const messages = [];

        for (const e of Object.values(errors)) {
            messages.push((e as string[])[0]);
        }

        let html = "<ol>";
        for (const m of messages) {
            html += `<li>${m}</li>`;
        }

        html += "</ol>";

        $swal("error", {
            title: error.message ?? t("error.failed_request"),
            html,
        });

        throw error;
    }
};

const saveAsNew = async (uuid: string) => {
    try {
        const result = await useClient(`/api/budgeting/budget/save-as-new/${uuid}`, {
            method: "POST",
        });

        await $swal("success", {
            title: t("action.created"),
            html:
                `<b>${result.name}</b><br/>${result.description || ''}`
        });

        navigateTo(`/budgeting/budget/${result.uuid}`);

    } catch (error: any) {
        await $swal("error", {
            title: error.message ?? t("error.failed_request"),
        });
    }
};
</script>
