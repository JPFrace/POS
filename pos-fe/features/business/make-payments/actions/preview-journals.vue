<template>
    <div @click="handler">
        <KTIcon
            icon-name="questionnaire-tablet"
            icon-class="fs-2 cursor-pointer hover:dark:text-white hover:text-slate-700"
        />
        Preview Journal Entries
    </div>
    <ModalDialog
        title="Journal Entries"
        centered
        v-model:open="open"
        align-center
        width="40%"
    >
        <template #body>
            <table
                class="table table-bordered table-hover table-rounded border gy-4 gs-4 table-row-gray-300"
            >
                <thead>
                    <tr
                        class="fw-semibold fs-6 text-gray-800 border-bottom-2 border-gray-200"
                    >
                        <th width="70%" class="text-start">Account</th>
                        <th width="8=20%" class="text-end">Debit</th>
                        <th width="8=20%" class="text-end">Credit</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ item?.product.expense_account?.name }}</td>
                        <td>{{ item?.rate }}</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>{{ item?.product.expense_account?.name }}</td>
                        <td>-</td>
                        <td>{{ item?.rate }}</td>
                    </tr>
                </tbody>
            </table>
        </template>
    </ModalDialog>
</template>

<script lang="ts" setup>
import type { Payment, PaymentItem } from "~/types/payment";
import moment from "moment";
import { isEmpty } from "lodash";

interface Props {
    data: Partial<Payment>;
}

const { yup } = useYup();
const { $message, $swal } = useNuxtApp();
const { t } = useI18n();
const props = defineProps<Partial<Props>>();

const open = defineModel({
    default: false,
    type: Boolean,
});

const Yup = yup();
const { validate } = useYup();

const validateForm = () => {
    const form = Yup.object().shape({
        date: Yup.string().required().notRequired(),
        ref_no_auto: Yup.boolean().notRequired(),
        check_no_auto: Yup.boolean().notRequired(),
        ref_no: Yup.string()
            .ensure()
            .when(["ref_no_auto"], ([ref_no_auto], schema) =>
                ref_no_auto ? schema.notRequired() : schema.required(),
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
                },
            )
            .notRequired(),
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
                                : schema.notRequired(),
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
                }),
            ),
    });

    return validate(form, {
        data: props.data ?? {},
    });
};

const handler = async () => {
    const formData = new FormData();
    const formValue = (props.data ?? {}) as Record<string, any>;
    const keys = Object.keys(formValue);
    console.log(props.data);
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
        } else if (
            key === "items" ||
            key === "contact" ||
            key === "cash_in_bank" ||
            key === "dimension"
        ) {
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

        const url = "/api/business/payments/journals-preview";
        const method = "POST";

        const result = await useClient(url, {
            method,
            body: formData,
        });
    } catch (error: any) {
        const errors = error?.errors ?? [];

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
