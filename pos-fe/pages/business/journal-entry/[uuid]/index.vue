<template>
    <div>
        <NuxtLayout>
            <div class="flex flex-col gap-y-8">
                <Header v-model="form" :errors="errors" />
                <Body v-model="form" :errors="errors" />
                <Footer v-model="form" :errors="errors" />
            </div>
        </NuxtLayout>
    </div>
</template>

<script lang="ts" setup>
import Header from "~/features/business/journal-entry/components/header.vue";
import Body from "~/features/business/journal-entry/components/body.vue";
import Footer from "~/features/business/journal-entry/components/footer.vue";
import type { JournalEntry, JournalItem } from "~/types/journal-entry";
import moment from "moment";
import { usePageTitle } from "~/composables/usePageTitle";

usePageTitle();
definePageMeta({
    permission: "Business.Journal Entry.Edit",
});

const { send, receive, dismiss } = usePageEvent();

const tableRows = 4;
const errors = ref();

const items: Partial<JournalItem>[] = [];

const item = ref<Partial<JournalItem>>({
    account: null,
    debit: 0,
    credit: 0,
    description: "",
    contact: null,
    active: false,
});

for (let i = 0; i < tableRows; i++) {
    items.push({ ...item.value });
}

const form = ref<Partial<JournalEntry>>({
    date: moment().format("MM/DD/YYYY"), // Set default date
    je_no: "",
    ref_no: "",
    memo: "",
    attachment: null,
    je_no_auto: true,
    ref_no_auto: true,
    items,
});

const fill = () => {
    const items = [];
    for (let i = 0; i < tableRows; i++) {
        items.push({ ...item.value });
    }

    form.value.items = items;
};

const refill = () => {
    for (let i = (form.value?.items ?? []).length - 1; i < 3; i++) {
        form.value!.items = (form.value!.items ?? []).concat([
            {
                account: null,
                debit: 0,
                credit: 0,
                description: "",
                contact: null,
                active: false,
            },
        ]);
    }
};

const client = useSanctumClient();
const route = useRoute();
const { data: journal_entries, refresh } = useAsyncData<JournalEntry[]>(
    `${id(route.fullPath)}.journal-entry`,
    () =>
        client("/api/business/journal-entries", {
            method: "GET",
            params: {
                query: {
                    uuid: route.params.uuid,
                    account: true,
                    details: true,
                    file: true,
                    status: true,
                },
            },
        }),
    {
        server: false,
        lazy: true,
        immediate: true,
    },
);

const fillItem = () => {
    if (!journal_entries.value?.data?.[0]) {
        return;
    }
    const jeData = journal_entries.value.data[0] ?? [];
    const { details, ...filtered } = jeData;
    form.value = {
        ...filtered,
        attachment: jeData.file,
        ref_no_auto: true,
        je_no_auto: true,
    };

    const items: Partial<JournalItem>[] = [];
    (jeData?.details ?? []).forEach((row: any) => {
        items.push({
            account: {
                ...row.chart_account,
                id: row.chart_account.uuid,
                value: row.chart_account.uuid,
                label: row.chart_account.name,
                type: row.chart_account.type.name,
                code: row.chart_account.code,
                department: row.chart_account.department,
                description: row.chart_account.description,
            },
            debit: row.debit,
            credit: row.credit,
            description: row.description,
            active: true,
            contact: row.contact
                ? {
                      id: row.contact.uuid,
                      value: row.contact.uuid,
                      label: row.contact.name,
                      type: row.contact.type_label,
                      id_no: row.contact.id_no,
                      email: row.contact.email,
                  }
                : null,
        });
    });
    form.value.items = items;
    refill();
};

onBeforeUnmount(() => {
    dismiss("on:create-new");
    dismiss("on:error");
    dismiss("on:new-line");
    dismiss("on:clear-lines");
});

watch(journal_entries, () => {
    fillItem();
});

onMounted(() => {
    receive("on:create-new", (_value: any) => {
        const currentDate = form.value.date; // Preserve the date
        clearKeyValue(form.value);
        form.value.date = currentDate; // Restore the date after submit
        errors.value = [];
        fill();
        form.value.je_no_auto = true;
        form.value.ref_no_auto = true;
    });

    receive("on:error", (value: any) => {
        errors.value = value;
    });

    receive("on:new-line", (_value: any) => {
        form.value.items = (form.value.items ?? []).concat({ ...item.value });
    });

    receive("on:clear-lines", (_value: any) => {
        fill();
    });
});
</script>
