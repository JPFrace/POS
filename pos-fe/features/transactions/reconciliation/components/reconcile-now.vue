<template>
    <el-button size="large" type="success" @click="submit">
        Reconcile Now</el-button
    >
</template>
<script lang="ts" setup>
import moment from "moment";
import type { Reconciliation } from "~/types/reconciliation";

interface Props {
    is_new: boolean;
}

const props = defineProps<Props>();
const { send } = usePageEvent();
const { $message, $swal } = useNuxtApp();
const { t } = useI18n();
const { validate, yup } = useYup();
const Yup = yup();

const data = defineModel<Reconciliation>();

const validateForm = () => {
    const form = Yup.object().shape({
        bank_account: Yup.object()
            .shape({
                value: Yup.string().required(),
            })
            .required(),
        bank_statement_ending_balance: Yup.number()
            .transform((current: any, original: any) => {
                return original ? numberOnly(original) : 0;
            })
            .min(1)
            .required(),
        start_at: Yup.string().required(),
        ending_balance: Yup.number()
            .transform((current: any, original: any) => {
                return original ? numberOnly(original) : 0;
            })
            .min(1)
            .required(),
        end_at: Yup.string().required(),
    });

    return validate(form, { data: data.value ?? {} });
};

const submit = async () => {
    console.log(data.value);

    const formData = new FormData();
    const formValue = (data.value ?? {}) as Record<string, any>;
    const keys = Object.keys(formValue);
    for (const key of keys) {
        const value = formValue[key];
        if (key === "end_at" || key === "start_at") {
            formData.append(
                key,
                value ? moment(value, "MM/DD/YYYY").format("YYYY-MM-DD") : "",
            );
        } else if (key === "bank_account") {
            formData.append(key, value.value);
        } else {
            formData.append(key, value === null ? "" : value);
        }
    }
    try {
        await validateForm();

        const url = props.is_new
            ? "/api/accounting/reconciliations/save-reconciliation"
            : "/api/accounting/reconciliations/update-reconciliation";
        const method = props.is_new ? "POST" : "PUT";

        const result = await useClient(url, {
            method: method,
            body: formData,
        });

        await $swal("success", {
            title: "Entry",
            text: t("action.created"),
        });

        setTimeout(() => {
            send("start:reconciliation", result);
        }, 300);
    } catch (error: any) {
        const errors = error?.errors ?? [];

        send("on:setup-error", errors);

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
</script>
