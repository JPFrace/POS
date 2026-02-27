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
    permission: "Business.Journal Entry.View",
});

const { send, receive, dismiss } = usePageEvent();

const tableRows = 4;
const errors = ref();

const item = ref<Partial<JournalItem>>({
    account: null,
    debit: 0,
    credit: 0,
    description: "",
    contact: null,
    active: false,
});

const items: Partial<JournalItem>[] = [];

for (let i = 0; i < tableRows; i++) {
    items.push({ ...item.value });
}

const form = ref<Partial<JournalEntry>>({
    date: moment().format("MM/DD/YYYY"), // Set default date
    je_no: null,
    ref_no: null,
    memo: null,
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

onBeforeUnmount(() => {
    dismiss("on:create-new");
    dismiss("on:error");
    dismiss("on:new-line");
    dismiss("on:clear-lines");
});

onMounted(() => {
    receive("on:create-new", (_value: any) => {
        const currentDate = form.value.date; // Preserve the date
        clearKeyValue(form.value);
        form.value.date = currentDate; // Restore the date after submit
        errors.value = [];
        fill();
        form.value.je_no_auto = true;
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
