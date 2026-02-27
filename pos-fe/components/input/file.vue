<template>
    <div>
        <label v-if="block" class="form-label"
            >{{ label }}
            <span v-if="topDesc" class="text-xs ml-2"
                >({{ topDesc }})</span
            ></label
        >
        <input
            type="file"
            ref="fileRef"
            class="form-control"
            :multiple="multiple"
            @change="handleFileUpload"
            :accept="accepted.join(',')"
            v-bind="$attrs"
        />
    </div>
</template>

<script lang="ts" setup>
import type { File as FormFile, Option } from "~/types/form";

const props = defineProps<{
    multiple?: boolean;
    block?: boolean;
    topDesc?: string;
    label?: string;
    acceptedFileTypes?: Option[];
}>();

interface DataProps {
    file: File;
    url: string;
    filename: string;
    original_filename: string;
}

const data = defineModel<Partial<DataProps[]>>();
const fileRef = ref<HTMLInputElement | null>(null);

const accepted = computed(() =>
    ((props.acceptedFileTypes ?? []) as any).map((row: any) => row.value)
);

const handleFileUpload = (event: Event) => {
    const target = event.target as HTMLInputElement;

    if (target.files) {
        const files = Array.from(target.files, (file: File) => file) as File[];

        data.value = files.map(
            (file: File) =>
                ({
                    file,
                    url: URL.createObjectURL(file),
                    filename: file.name,
                    original_filename: file.name,
                    is_image: file.type.includes("image"),
                }) as Partial<FormFile>
        ) as DataProps[];
    }
};

const remove = (index: number) => {
    const files = data.value as Partial<FormFile[]>;

    delete files[index];

    data.value = files.filter(Boolean);
};

const clear = () => {
    data.value = [];

    fileRef.value!.value = "";
};

defineExpose({
    click: () => fileRef.value?.click(),
    dblclick: () => fileRef.value?.dblclick(),
    clear,
});
</script>
