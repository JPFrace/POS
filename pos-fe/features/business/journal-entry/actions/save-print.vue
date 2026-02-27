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
            @print-journal-voucher="printJournalVoucher"
        />
    </div>
</template>

<script lang="ts" setup>
import More from "./more.vue";
import moment from "moment";
import type { JournalEntry, JournalItem } from "~/types/journal-entry";
import { isEmpty } from "lodash";

const route = useRoute();

const { t } = useI18n();
const { validate } = useYup();
const { $message, $swal } = useNuxtApp();
const { yup } = useYup();
const { send } = usePageEvent();

const Yup = yup();
const data = defineModel<Partial<JournalEntry>>();

const isPosted = computed(() => {
    return data.value?.status?.name?.toLowerCase() === "posted";
});

const total = (key: string, items?: Partial<JournalItem>[]): number =>
    (items ?? []).reduce((sum: any, item: any) => {
        if (item[key]) {
            return (
                parseFloat(sum.toString()) +
                parseFloat(item[key].toString().replace(/[^0-9.]/g, ""))
            );
        }

        return sum;
    }, 0);

const debits = computed(() => total("debit", data.value?.items));
const credits = computed(() => total("credit", data.value?.items));

const validateForm = () => {
    const form = Yup.object().shape({
        date: Yup.string().required(),
        je_no_auto: Yup.boolean(),
        je_no: Yup.string()
            .ensure()
            .when(["je_no_auto"], ([je_no_auto], schema) =>
                je_no_auto == true ? schema.notRequired() : schema.required()
            ),
        ref_no_auto: Yup.boolean(),
        ref_no: Yup.string()
            .ensure()
            .when(["ref_no_auto"], ([ref_no_auto], schema) =>
                ref_no_auto == true ? schema.notRequired() : schema.required()
            ),
        memo: Yup.string().notRequired(),
        description: Yup.string().notRequired(),
        debits: Yup.number()
            .transform((current: any, _original: any) => {
                return parseFloat(current.toString().replace(/[^0-9.]/g, ""));
            })
            .required(),
        credits: Yup.number()
            .transform((current: any, _original: any) => {
                return parseFloat(current.toString().replace(/[^0-9.]/g, ""));
            })
            .required(),
        totals: Yup.number().test({
            name: "total-valid",
            test(value, ctx) {
                if (ctx.parent.debits !== ctx.parent.credits) {
                    return ctx.createError({
                        message: t("journal.validation.totals_invalid"),
                    });
                }

                return true;
            },
        }),
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
                    debit: Yup.number()
                        .transform((current: any, original: any) => {
                            return original ? numberOnly(original) : 0;
                        })
                        .notRequired(),
                    credit: Yup.number()
                        .transform((current: any, original: any) => {
                            return original ? numberOnly(original) : 0;
                        })
                        .notRequired(),
                    debit_value: Yup.number().when(
                        ["credit", "debit", "active"],
                        ([credit, debit, active], schema) =>
                            active && !credit && !debit
                                ? schema.min(1).required()
                                : schema.notRequired()
                    ),
                    credit_value: Yup.number().when(
                        ["debit", "credit", "active"],
                        ([debit, credit, active], schema) =>
                            active && !debit && !credit
                                ? schema.min(1).required()
                                : schema.notRequired()
                    ),
                })
            ),
    });

    return validate(form, {
        data: {
            ...data.value,
            debits: debits.value,
            credits: credits.value,
        },
    });
};

const submit = async (action?: string) => {
    if (isPosted.value) {
        $message("warning", t("journal.validation.cannot_edit"));
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
        } else if (key === "items") {
            formData.append(
                key,
                JSON.stringify(value, (jsonKey, jsonValue) =>
                    jsonValue === null ? "" : jsonValue
                )
            );
        } else if (key === "date") {
            formData.append(
                key,
                moment(value, "MM/DD/YYYY").format("YYYY-MM-DD")
            );
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
            ? `/api/business/journal-entries/${route.params.uuid}`
            : "/api/business/journal-entries";
        const method = isUpdate ? "PUT" : "POST";
        const messageKey = isUpdate ? "action.updated" : "action.created";

        const result = await useClient(url, {
            method,
            body: formData,
        });

        const journalEntriesUUID = isUpdate
            ? (route.params.uuid as string)
            : result.uuid;

        await $swal("success", {
            title: "Entry",
            text: t(messageKey),
        });
        if (action === "save") {
            return navigateTo(`/business/journal-entry/${journalEntriesUUID}`);
        }
        if (action === "save-new") {
            send("on:create-new");
            return navigateTo("/business/journal-entry");
        }
        printJournalVoucher(journalEntriesUUID);
        setTimeout(() => {
            send("on:create-new");
        }, 300);
        navigateTo("/business/journal-entry");
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

const printJournalVoucher = async (id?: string) => {
    const checkUUID = id ?? uuid.value;
    if (!checkUUID) {
        $message("info", t("journal.info.no_journal_entry_data"));
        return;
    }

    const url = `/reports/journal-voucher/${checkUUID}`;
    window.open(url, "_blank");
};
</script>
