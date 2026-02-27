<template>
  <div>
    <div class="image-input image-input-outline" data-kt-image-input="true">
      <img
        :src="selectedFile || origPath"
        alt="Company Logo"
        class="image-input-wrapper w-125px h-125px"
      />
      <label
        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
        data-kt-image-input-action="change"
        @change="onLogoChange"
        title="Change logo"
      >
        <i class="bi bi-pencil-fill fs-7"></i>
        <input type="file" accept=".png, .jpg, .jpeg" />
        <input type="hidden" name="logo_remove" />
      </label>
      <span
        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
        data-kt-image-input-action="remove"
        @click="removeLogo"
        title="Remove logo"
      >
        <i class="bi bi-x fs-2"></i>
      </span>
    </div>
    <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
  </div>
</template>

<script lang="ts" setup>
import { ref } from "vue";

const props = defineProps<{
  path?: string ;
  origPath?: string;
}>();

const emits = defineEmits<{
  (e: "newImageSelected", payload: { file: File }): void;
  (e: "logoChanged", payload: string): void;
}>();

const selectedFile = ref<string>();

const onLogoChange = (event: Event) => {
  const file = (event.target as HTMLInputElement).files?.[0];
  if (file) {
    selectedFile.value = URL.createObjectURL(file);
    emits("newImageSelected", { file });
  }
};

const removeLogo = () => {
  selectedFile.value = undefined;
  emits("logoChanged", "");
};
</script>
