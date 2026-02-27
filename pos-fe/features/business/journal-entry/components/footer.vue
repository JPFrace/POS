<template>
    <div class="flex flex-col gap-y-4 items-start justify-between">
        <div class="w-1/2 flex flex-col gap-y-4">
            <div>
                <Textarea
                    block
                    label="Memo"
                    placeholder="..."
                    v-model="form.memo"
                    :is-valid="isValid('memo')"
                ></Textarea>
            </div>
            <div>
                <InputFile
                    block
                    label="Attachments"
                    accept=".jpg,.png,.jpeg,.pdf"
                    v-model="form.attachment"
                    :is-valid="isValid('attachment')"
                    ref="fileRef"
                />
            </div>
        </div>
        <div>&nbsp;</div>
    </div>
</template>

<script setup lang="ts">
import type { JournalEntry } from "~/types/journal-entry";
const form = defineModel<Partial<JournalEntry>>();
const { receive } = usePageEvent();

interface Props {
    errors: any;
}

const props = defineProps<Props>();
const fileRef = ref();

const isValid = (key: string) =>
    props.errors
        ? Object.keys(props.errors).includes(key)
            ? (props.errors as any)[key]?.length <= 0
            : null
        : null;

onMounted(() => {
    receive("on:create-new", () => {
        fileRef.value.clear();
    });
});
</script>
