<template>
    <div class="flex gap-x-2">
        <Button
            variant="primary"
            label="Save & Print"
            class="!uppercase"
            :disabled="isPosted"
            @click="submit()"
        />
        <More
            :data="data"
            @save="submit('save')"
            @save-new="submit('save-new')"
        />
    </div>
</template>

<script lang="ts" setup>
import More from "./more.vue";

import { isEmpty } from "lodash";
import moment from "moment";
import type { Bill } from "~/types/bill";

const route = useRoute();

const { t } = useI18n();
const { validate } = useYup();
const { $message, $swal } = useNuxtApp();
const { yup } = useYup();
const { send } = usePageEvent();

const Yup = yup();

const isPosted = computed(() => {
return data.value?.status?.name?.toLowerCase() === "posted";
});

const data = defineModel<Partial<Bill>>();
const validateForm = () => {
    const form = Yup.object().shape({
        date: Yup.string().required(),
        due_date: Yup.string().nullable(),
        bill_no_auto: Yup.boolean(),
        bill_no: Yup.string()
            .trim()
            .ensure()
            .when(["bill_no_auto"], ([bill_no_auto], schema) =>
                bill_no_auto == true ? schema.notRequired() : schema.required()
            ),
        term: Yup.object().nullable(),
        vendor: Yup.object()
            .shape({
                value: Yup.string().required(),
            })
            .required(),
        vendor_email: Yup.string().notRequired(),
        billing_address: Yup.string().notRequired(),
        remarks: Yup.string().notRequired(),
        items: Yup.array()
            .compact((value: any) => value.product == null)
            .min(1)
            .of(
                Yup.object().shape({
                    active: Yup.boolean().notRequired(),
                    product: Yup.object()
                        .shape({
                            value: Yup.string().required(),
                            name: Yup.string().required(),
                        })
                        .required(),
                    product_value: Yup.object().when(
                        ["active", "product"],
                        ([active, product], schema) =>
                            active == true && isEmpty(product)
                                ? schema.required()
                                : schema.notRequired()
                    ),
                    product_name: Yup.string().ensure().notRequired(),
                    product_description: Yup.string().ensure().notRequired(),
                    rate: Yup.number()
                        .transform((current: any, original: any) => {
                            return original ? numberOnly(original) : 0;
                        })
                        .min(1)
                        .required(),
                    quantity: Yup.number()
                        .transform((current: any, original: any) => {
                            return original ? numberOnly(original) : 0;
                        })
                        .min(1)
                        .required(),
                })
            ),
    });

    return validate(form, {
        data: data.value ?? {},
    });
};

const submit = async (action?: string) => {
    if (isPosted.value) {
        $message("warning", t("bill.validation.cannot_edit"));
        return;
    }

    const formData = new FormData();
    const formValue = (data.value ?? {}) as Record<string, any>;
    const keys = Object.keys(formValue);

    for (const key of keys) {
        const value = formValue[key];
        if (key === "attachment") {
            if (value?.length) {
                formData.append(key, value[0]?.file);
            }
        } else if (key === "items" || key === "vendor" || key === "term") {
            formData.append(
                key,
                JSON.stringify(value, (jsonKey, jsonValue) =>
                    jsonValue === null ? "" : jsonValue
                )
            );
        } else if (key === "date" || key === "due_date") {
            formData.append(
                key,
                value ? moment(value, "MM/DD/YYYY").format("YYYY-MM-DD") : ""
            );
        } else {
            formData.append(key, value === null ? "" : value);
        }
    }

    try {
        await validateForm();

        const isUpdate = Boolean(route.params.uuid);
        const url = isUpdate
            ? `/api/business/bills/${route.params.uuid}`
            : "/api/business/bills/";
        const method = isUpdate ? "PUT" : "POST";
        const messageKey = isUpdate ? "action.saved" : "action.created";

        const result = await useClient(url, {
            method,
            body: formData,
        });

        const billsUUID = isUpdate
            ? (route.params.uuid as string)
            : result.uuid;

        await $swal("success", {
            title: "Entry",
            text: t(messageKey),
        });
        if (action === "save") {
            return navigateTo(`/business/bills/${billsUUID}`);
        }
        if (action === "save-new") {
            send("on:create-new");
            return navigateTo("/business/bills");
        }
        setTimeout(() => {
            send("on:create-new");
        }, 300);
        navigateTo("/business/bills");
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
