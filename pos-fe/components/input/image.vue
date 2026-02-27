<template>
    <div class="image-input image-input-outline" data-kt-image-input="true">
        <!--begin::Preview existing avatar-->
        <img
            alt="User Photo"
            id="photo"
            class="image-input-wrapper w-125px h-125px"
            :src="imageUrl ?? defaultPhoto"
        />
        <!--end::Preview existing avatar-->

        <!--begin::Label-->
        <label
            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
            data-kt-image-input-action="change"
            data-bs-toggle="tooltip"
            title="Change avatar"
        >
            <i class="bi bi-pencil-fill fs-7"></i>

            <!--begin::Inputs-->
            <input
                type="file"
                name="avatar"
                accept=".png, .jpg, .jpeg"
                @change="onChange"
            />
            <input type="hidden" name="avatar_remove" />
            <!--end::Inputs-->
        </label>
        <!--end::Label-->

        <!--begin::Remove-->
        <span
            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
            data-kt-image-input-action="remove"
            data-bs-toggle="tooltip"
            @click="onRemove"
            title="Remove avatar"
        >
            <i class="bi bi-x fs-2"></i>
        </span>
        <!--end::Remove-->
    </div>
</template>

<script lang="ts" setup>
const props = defineProps<{
    type?: string;
}>();
const data = defineModel<File | string | null>();
const defaultPhoto = ref("/media/avatars/blank.png");
const fileError = ref("");
const imageUrl = ref<string | null>(null);

const onChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];
    if (file) {
        processFile(file);
    }
};

const onRemove = () => {
    imageUrl.value = null;
    data.value = null;
};

const processFile = async (file: File) => {
    // Validate file
    if (!validateFile(file)) return;

    imageUrl.value = URL.createObjectURL(file);
    data.value = file;
    fileError.value = "";

    const reader = new FileReader();
    reader.onload = (e) => {
        imageUrl.value = e.target?.result as string;
        // Add the file to form data
    };
    reader.readAsDataURL(file);
};

const validateFile = (file: File): boolean => {
    // Check if it's an image
    if (!file.type.startsWith("image/")) {
        fileError.value = "Please select an image file";
        return false;
    }

    // Check file size (10MB limit)
    if (file.size > 10 * 1024 * 1024) {
        fileError.value = "File size must be less than 10MB";
        return false;
    }

    return true;
};

onMounted(async () => {
    await nextTick();
    if (typeof data.value === "string") {
        imageUrl.value = data.value;
    }
});
</script>
