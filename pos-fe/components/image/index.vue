<template>
    <div class="space-y-2">
        <label v-if="label" class="block text-sm font-medium text-gray-700">
            {{ label }}
        </label>
        <el-upload
            v-model:file-list="fileList"
            class="image-uploader"
            :auto-upload="false"
            :limit="1"
            accept="image/*"
            drag
            :on-change="handleFileChange"
            :on-remove="handleRemove"
            :on-exceed="handleExceed"
            :show-file-list="false"
        >
            <template v-if="previewUrl">
                <div class="preview-wrapper">
                    <img :src="previewUrl" class="preview-image" />
                    <div class="preview-overlay" @click.stop="handleRemove">
                        <el-icon size="20" color="#D14249"><Delete /></el-icon>
                        <span class="text-white text-xs mt-1">Remove</span>
                    </div>
                </div>
            </template>
            <template v-else>
                <el-icon class="el-icon--upload"><UploadFilled /></el-icon>
                <div class="el-upload__text">
                    Drop file here or <em>click to upload</em>
                </div>
                <div class="text-xs text-gray-500 mt-1">
                    PNG, JPG, GIF up to {{ maxSizeMB }}MB
                </div>
            </template>
        </el-upload>
    </div>
</template>

<script lang="ts" setup>
import { UploadFilled, Delete } from "@element-plus/icons-vue";
import { ElMessage } from "element-plus";
import type { UploadFile, UploadRawFile } from "element-plus";

interface Props {
    label?: string;
    maxSizeMB?: number;
}

const props = withDefaults(defineProps<Props>(), {
    label: "",
    maxSizeMB: 10,
});

const emit = defineEmits<{
    (e: "file-change", file: File | null): void;
}>();

const fileList = ref<UploadFile[]>([]);
const previewUrl = ref<string | null>(null);

const validateFile = (raw: UploadRawFile): boolean => {
    if (!raw.type.startsWith("image/")) {
        ElMessage.error("Please select an image file");
        return false;
    }
    if (raw.size > props.maxSizeMB * 1024 * 1024) {
        ElMessage.error(`File size must be less than ${props.maxSizeMB}MB`);
        return false;
    }
    return true;
};

const setPreview = (raw: UploadRawFile) => {
    if (previewUrl.value?.startsWith("blob:"))
        URL.revokeObjectURL(previewUrl.value);
    previewUrl.value = URL.createObjectURL(raw);
};

const handleFileChange = (uploadFile: UploadFile) => {
    const raw = uploadFile.raw as UploadRawFile;
    if (!raw || !validateFile(raw)) return;
    setPreview(raw);
    emit("file-change", raw);
};

const handleRemove = () => {
    if (previewUrl.value?.startsWith("blob:"))
        URL.revokeObjectURL(previewUrl.value);
    previewUrl.value = null;
    fileList.value = [];
    emit("file-change", null);
};

const handleExceed = (files: File[]) => {
    const file = files[0] as UploadRawFile;
    file.uid = Date.now() as number;
    fileList.value = [
        { name: file.name, raw: file, uid: file.uid, status: "ready" },
    ];
    if (validateFile(file)) {
        setPreview(file);
        emit("file-change", file);
    }
};

const setInitialFile = (url: string, name: string = "image") => {
    previewUrl.value = url;
    fileList.value = [
        { name, url, uid: Date.now() as number, status: "success" },
    ];
};

defineExpose({ setInitialFile });
</script>

<style scoped>
:deep(.el-upload),
:deep(.el-upload-dragger) {
    width: 100%;
}

:deep(.el-upload-dragger) {
    padding: 0;
    overflow: hidden;
    min-height: 180px;
}

.preview-wrapper {
    position: relative;
    width: 100%;
    aspect-ratio: 16/9;
}

.preview-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.preview-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.45);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.2s;
    cursor: pointer;
}

.preview-wrapper:hover .preview-overlay {
    opacity: 1;
}
</style>
