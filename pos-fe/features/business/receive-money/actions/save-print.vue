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
            :validate-form="validateForm"
            :save-receipt="saveReceipt"
            :uuid="uuid"
        />
    </div>
</template>

<script lang="ts" setup>
import More from "./more.vue";
import moment from "moment";
import type { OfficialReceipt } from "~/types/official-receipts";
import { isEmpty } from "lodash";
import { numberOnly } from "~/utils/helper";

const route = useRoute();
const uuid = computed(() => route.params.uuid as string | undefined);
const { t } = useI18n();
const { validate } = useYup();
const { $message, $swal } = useNuxtApp();
const { yup } = useYup();
const { send } = usePageEvent();

const Yup = yup();
const data = defineModel<Partial<OfficialReceipt>>();

const isPosted = computed(() => {
    return data.value?.status?.name?.toLowerCase() === "posted";
});

const validateForm = () => {
    const form = Yup.object().shape({
        date: Yup.string().required(),
        ref_no_auto: Yup.boolean(),
        ref_no: Yup.string()
            .ensure()
            .when(["ref_no_auto"], ([ref_no_auto], schema) =>
                ref_no_auto ? schema.notRequired() : schema.required()
            ),
        or_no_auto: Yup.boolean(),
        or_no: Yup.string()
            .ensure()
            .when(["or_no_auto"], ([or_no_auto], schema) =>
                or_no_auto ? schema.notRequired() : schema.required()
            ),
        customer: Yup.object()
            .shape({
                value: Yup.string().required(),
            })
            .required(),
        customer_email: Yup.string().notRequired(),
        billing_address: Yup.string().notRequired(),
        dimension: Yup.array().notRequired(),        
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
        denominations: Yup.array()
            .of(
                Yup.object().shape({
                    depositAccount: Yup.object()
                        .shape({ value: Yup.string().required() })
                        .required("Deposit To is required"),
                    payment_method: Yup.mixed()
                        .required("Payment Method is required")
                        .test(
                            "has-value",
                            "Payment Method is required",
                            (v: any) =>
                                v != null &&
                                (typeof v !== "object"
                                    ? true
                                    : !!(v?.value ?? v?.uuid))
                        ),
                    amount: Yup.number()
                        .transform((_: any, orig: any) => numberOnly(orig ?? 0))
                        .min(0, "Amount must be 0 or more")
                        .required("Amount is required"),
                })
            )
            .test(
                "denominations-required",
                "Payment denominations are required",
                function (denominations: any) {
                    const items = (this.parent?.items ?? []) as any[];
                    const itemsTotal =
                        items.reduce(
                            (sum: number, item: any) =>
                                sum +
                                numberOnly(item?.quantity ?? 0) *
                                    numberOnly(item?.rate ?? 0) -
                                numberOnly(item?.tax_rate ?? 0),
                            0
                        ) || 0;
                    if (itemsTotal <= 0) return true;
                    const list = denominations ?? null;
                    return list != null && Array.isArray(list) && list.length > 0;
                }
            )
            .test(
                "denominations-total-gte-items",
                "Total payment amount must be equal to or more than the total amount of items",
                function (denominations: any) {
                    const list = denominations ?? [];
                    if (list.length === 0) return true;
                    const items = (this.parent?.items ?? []) as any[];
                    const itemsTotal =
                        items.reduce(
                            (sum: number, item: any) =>
                                sum +
                                numberOnly(item?.quantity ?? 0) *
                                    numberOnly(item?.rate ?? 0) -
                                numberOnly(item?.tax_rate ?? 0),
                            0
                        ) || 0;
                    const paymentTotal = list.reduce(
                        (sum: number, d: any) =>
                            sum + numberOnly(d?.amount ?? 0),
                        0
                    );
                    return paymentTotal >= itemsTotal;
                }
            ),
    });

    return validate(form, {
        data: data.value ?? {},
    });
};

const saveReceipt = async () => {
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
            key === "customer" ||
            key === "deposit" ||
            key === "dimension" ||
            key === "denominations"
        ) {
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

    const isUpdate = Boolean(route.params.uuid);
    const url = isUpdate
        ? `/api/business/official-receipts/${route.params.uuid}`
        : "/api/business/official-receipts";
    const method = isUpdate ? "PUT" : "POST";

    return await useClient(url, {
        method,
        body: formData,
    });
};

const printOfficialReceipt = async (id: string) => {
    const url = `/reports/official-receipt/${id}`;
    window.open(url, "_blank");
};

const submit = async () => {
    if (isPosted.value) {
        $message("warning", t("money_receive.validation.cannot_edit"));
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

        const result = await saveReceipt();

        const receiveMoneyUUID = isUpdate
            ? (route.params.uuid as string)
            : result.uuid;

        await $swal("success", {
            title: "Entry",
            text: t(messageKey),
        });

        await printOfficialReceipt(receiveMoneyUUID);

        setTimeout(() => {
            send("on:create-new");
        }, 300);
        navigateTo("/business/receive-money");
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
