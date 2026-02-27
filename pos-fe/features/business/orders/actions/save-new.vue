<template>
    <div class="flex gap-x-2">
        <Button
            variant="primary"
            label="Save & New"
            class="!uppercase"
            @click="submit()"
        />
        <More @save="submit(true)" />
    </div>
</template>

<script lang="ts" setup>
import More from "./more.vue";
import moment from "moment";
import { isEmpty } from "lodash";
import type { Invoice } from "~/types/invoice";

const route = useRoute();

const { t } = useI18n();
const { validate } = useYup();
const { $message, $swal } = useNuxtApp();
const { yup } = useYup();
const { send } = usePageEvent();

const Yup = yup();

const data = defineModel<Partial<Invoice>>();

const validateForm = () => {
    const form = Yup.object().shape({
        date: Yup.string().required(),
        order_no_auto: Yup.boolean(),
        order_no: Yup.string()
            .ensure()
            .when(["order_no_auto"], ([order_no_auto], schema) =>
                order_no_auto == true ? schema.notRequired() : schema.required()
            ),
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

const submit = async (saveOnly: boolean = false) => {
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
                moment(value, "MM/DD/YYYY").format("YYYY-MM-DD")
            );
        } else if (key === "items" || key === "vendor") {
            formData.append(
                key,
                JSON.stringify(value, (jsonKey, jsonValue) =>
                    jsonValue === null ? "" : jsonValue
                )
            );
        } else {
            formData.append(key, value === null ? "" : value);
        }
    }

    try {
        await validateForm();

        const isUpdate = Boolean(route.params.uuid);
        const url = isUpdate
            ? `/api/business/orders/${route.params.uuid}`
            : "/api/business/orders";
        const method = isUpdate ? "PUT" : "POST";
        const messageKey = isUpdate ? "action.updated" : "action.created";

        const result = await useClient(url, {
            method,
            body: formData,
        });

        const purchaseOrdersUUID = isUpdate
            ? (route.params.uuid as string)
            : result.uuid;

        await $swal("success", {
            title: "Entry",
            text: t(messageKey),
        });

        if (saveOnly) {
            navigateTo(`/business/purchase-orders/${purchaseOrdersUUID}`);
            return;
        }

        // Default = Save & New
        setTimeout(() => {
            send("on:create-new");
        }, 300);
        navigateTo("/business/purchase-orders");
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
