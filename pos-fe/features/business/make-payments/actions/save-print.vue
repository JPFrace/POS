<template>
    <div class="flex gap-x-2 mt-8">
        <Button
            variant="primary"
            label="Save & Print"
            class="!uppercase"
            :disabled="isPosted"
            @click="submit()"
        />
        <More
            :data="data"
            :validate-form="validateForm"
            :save-payment="savePayment"
            :uuid="uuid"
        />
    </div>
</template>

<script lang="ts" setup>
import More from "./more.vue";
import moment from "moment";
import type { Payment } from "~/types/payment";
import { isEmpty } from "lodash";
import { usePaymentMethod } from "~/composables/business/usePaymentMethod";

const route = useRoute();
const uuid = computed(() => route.params.uuid as string | undefined);
const { t } = useI18n();
const { $message, $swal } = useNuxtApp();
const { validate } = useYup();
const { yup } = useYup();
const { send } = usePageEvent();

const Yup = yup();
const data = defineModel<Partial<Payment>>();

const { getSavedPaymentMethod } = usePaymentMethod();

const isPosted = computed(() => {
    return data.value?.status?.name?.toLowerCase() === "paid";
});

const isCheckPayment = computed<boolean>(() => {
    return data.value?.payment_method?.name?.toLowerCase() === "check";
});

const validateForm = () => {
    const form = Yup.object().shape({
        date: Yup.string().required(),
        ref_no_auto: Yup.boolean(),
        check_no_auto: Yup.boolean(),
        ref_no: Yup.string()
            .ensure()
            .when(["ref_no_auto"], ([ref_no_auto], schema) =>
                ref_no_auto == true ? schema.notRequired() : schema.required()
            ),
        check_no: Yup.string()
            .ensure()
            .when(
                ["check_no_auto", "payment_method"],
                ([check_no_auto, payment_method], schema) => {
                    if (payment_method?.name?.toLowerCase() === "check") {
                        return check_no_auto
                            ? schema.notRequired()
                            : schema.required();
                    }
                    return schema.notRequired();
                }
            ),
        cash_in_bank: Yup.object().shape({
            value: Yup.string().required(),
        }),
        contact: Yup.object()
            .shape({
                value: Yup.string().required(),
            })
            .required(),
        contact_email: Yup.string().notRequired(),
        billing_address: Yup.string().notRequired(),
        dimension: Yup.array().notRequired(),
        payment_method: Yup.object()
            .shape({
                uuid: Yup.string().required(),
            })
            .required(),
        remarks: Yup.string().required(),
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
                        .test((value) => value !== 0)
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

const savePayment = async () => {
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
        } else if (
            key === "items" ||
            key === "contact" ||
            key === "cash_in_bank" ||
            key === "dimension"
        ) {
            formData.append(
                key,
                JSON.stringify(value, (jsonKey, jsonValue) =>
                    jsonValue === null ? "" : jsonValue
                )
            );
        } else if (key === "payment_method") {
            formData.append(key, value?.uuid ?? "");
        } else {
            formData.append(key, value === null ? "" : value);
        }
    }

    const isUpdate = Boolean(route.params.uuid);
    const url = isUpdate
        ? `/api/business/payments/${route.params.uuid}`
        : "/api/business/payments";
    const method = isUpdate ? "PUT" : "POST";

    return await useClient(url, {
        method,
        body: formData,
    });
};

const printCheckVoucher = async (id: string) => {
    const savedPaymentMethod = await getSavedPaymentMethod(id);

    if (savedPaymentMethod !== "check") {
        $swal("error", {
            title: t("Invalid Payment Method"),
            text: "Check Voucher can only be generated for check payments. Please save the payment method as 'Check' first.",
        });
        return;
    }

    const url = `/reports/check-voucher/${id}`;
    window.open(url, "_blank");
};

const submit = async () => {
    if (isPosted.value) {
        $message("warning", t("make_payment.validation.cannot_edit"));
        return;
    }

    try {
        await validateForm();

        $swal("loading", {
            title: "Processing...",
            text: t("action.loading"),
        });

        const isUpdate = Boolean(route.params.uuid);
        const messageKey = isUpdate ? "action.updated" : "action.created";

        const result = await savePayment();

        const makePaymentsUUID = isUpdate
            ? (route.params.uuid as string)
            : result.uuid;

        await $swal("success", {
            title: t(messageKey),
            html: `Reference No.<br/>[ <b>${result.ref_no}</b> ]<br/>DV/Check No.<br/>[ <b>${result.check_no}</b> ]<br/>Trans. Date <br/>[ <b>${moment(result.date).format("MM/DD/YYYY")}</b> ]`,
        });

        if (isCheckPayment.value) {
            await printCheckVoucher(makePaymentsUUID);
        } else {
            window.open("about:blank", "_blank");
        }

        setTimeout(() => {
            send("on:create-new");
        }, 300);
        navigateTo("/business/make-payments");
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
