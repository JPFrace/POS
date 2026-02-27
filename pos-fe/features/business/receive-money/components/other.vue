<template>
  <div class="flex flex-col gap-y-4 items-start justify-between">
    <div class="w-1/2 flex flex-col gap-y-4">
      <div>
        <Textarea
          block
          label="Remarks"
          placeholder="..."
          v-model="form!.remarks"
          :is-valid="isValid('remarks')"
        ></Textarea>
      </div>
      <div>
        <InputFile
          block
          label="Attachments"
          accept=".jpg,.png,.jpeg,.pdf"
          v-model="form!.attachment"
          :is-valid="isValid('attachment')"
          ref="fileRef"
        />
        <span v-if="form?.file" class="float-end text-blue-500 mt-2">
          <a :href="form.file.url" target="_blank">Download here</a>
        </span>
      </div>
    </div>
    <div>&nbsp;</div>
  </div>
</template>

<script setup lang="ts">
import type { OfficialReceipt } from "~/types/official-receipts";

const form = defineModel<Partial<OfficialReceipt>>();
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
