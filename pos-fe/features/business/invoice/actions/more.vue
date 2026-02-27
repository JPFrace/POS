<template>
    <el-dropdown>
        <Button
            light
            icon="dots-square-vertical"
            :icon-color="{
                color: 'primary',
                active: 'secondary',
            }"
            class="!uppercase dropdown-toggle btn-sm"
            data-bs-toggle="dropdown"
            aria-expanded="false"
        />
        <template #dropdown>
            <el-dropdown-menu>
                <el-dropdown-item :disabled="isPosted || isPaid" @click="handleSave">
                    <KTIcon
                        icon-name="save-2"
                        icon-class="fs-2 cursor-pointer hover:dark:text-white hover:text-slate-700"
                    />
                    Save </el-dropdown-item
                >
                <el-dropdown-item :disabled="isPosted || isPaid" @click="handleSaveNew">
                    <KTIcon
                        icon-name="plus-circle"
                        icon-class="fs-2 cursor-pointer hover:dark:text-white hover:text-slate-700"
                    />
                    Save & New</el-dropdown-item
                >
            </el-dropdown-menu>
        </template>
    </el-dropdown>
</template>

<script lang="ts" setup>
import type { Invoice } from "~/types/invoice";

const props = defineProps<{
    data?: Partial<Invoice>;
}>();

const isPosted = computed(() => {
    return props.data?.status?.name?.toLowerCase() === "posted";
});

const isPaid = computed(() => {
    return props.data?.status?.name?.toLowerCase() === "paid";
});


const emit = defineEmits(["save", "save-new"]);

function handleSave() {
    if (!isPosted.value || !isPaid.value) {
        emit("save");
    }
}

function handleSaveNew() {
    if (!isPosted.value || !isPaid.value) {
        emit("save-new");
    }
}
</script>
