<template>
    <div class="flex gap-x-2 mt-8">
        <Button
            variant="primary"
            label="Save"
            class="!uppercase"
            @click="submit()"
        />
    </div>
</template>

<script lang="ts" setup>
import moment from "moment";
import { isEmpty } from "lodash";
import type { OfficialReceipt } from "~/types/official-receipts";

const route = useRoute();
const uuid = computed(() => route.params.uuid as string | null);
const { t } = useI18n();
const { $message, $swal } = useNuxtApp();
const { validate } = useYup();
const { yup } = useYup();
const { send } = usePageEvent();

const Yup = yup();
const data = defineModel<Partial<OfficialReceipt>>();

const validateForm = () => {
    const form = Yup.object().shape({
        date: Yup.string().required(),
        ref_no_auto: Yup.boolean(),
        ref_no: Yup.string()
            .ensure()
            .when(["ref_no_auto"], ([ref_no_auto], schema) =>
                ref_no_auto == true ? schema.notRequired() : schema.required(),
            ),
        cash_in_bank: Yup.object().shape({
            value: Yup.string().required(),
        }),
        remarks: Yup.string().required(),
        items: Yup.array()
            .min(1)
            .of(
                Yup.object().shape({
                    rate: Yup.number()
                        .transform((current: any, original: any) => {
                            return original ? numberOnly(original) : 0;
                        })
                        .test((value) => value !== 0)
                        .required(),
                }),
            ),
    });

    return validate(form, {
        data: data.value ?? {},
    });
};

const submit = async (action?: string) => {
    const formData = new FormData();
    const formValue = (data.value ?? {}) as Record<string, any>;
    const keys = Object.keys(formValue);

    for (const key of keys) {
        const value = formValue[key];
        if (key === "attachment") {
            if (value?.length) {
                formData.append(key, value[0]?.file);
            }
        } else if (key === "date") {
            formData.append(
                key,
                moment(value, "MM/DD/YYYY").format("YYYY-MM-DD"),
            );
        } else if (key === "items" || key == "cash_in_bank") {
            formData.append(
                key,
                JSON.stringify(value, (jsonKey, jsonValue) =>
                    jsonValue === null ? "" : jsonValue,
                ),
            );
        } else if (key === "payment_method") {
            formData.append(key, value?.uuid ?? "");
        } else {
            formData.append(key, value === null ? "" : value);
        }
    }

    try {
        await validateForm();

        $swal("loading", {
            title: "Processing...",
            text: t("action.loading"),
        });

        const isUpdate = Boolean(route.params.uuid);
        const url = isUpdate
            ? `/api/business/deposits/${route.params.uuid}`
            : "/api/business/deposits";
        const method = isUpdate ? "PUT" : "POST";
        const messageKey = isUpdate ? "action.updated" : "action.created";

        const result = await useClient(url, {
            method,
            body: formData,
        });

        await $swal("success", {
            title: t(messageKey),
            html: `Reference No.<br/>[ <b>${result.ref_no}</b> ]Trans. Date <br/>[ <b>${moment(result.date).format("MM/DD/YYYY")}</b> ]`,
        });

        setTimeout(() => {
            send("on:create-new");
        }, 300);
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
</script>
