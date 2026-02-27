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
                <el-dropdown-item :disabled="isPosted" @click="handleSave">
                    <KTIcon
                        icon-name="save-2"
                        icon-class="fs-2 cursor-pointer hover:dark:text-white hover:text-slate-700"
                    />
                    Save
                </el-dropdown-item>
                <el-dropdown-item :disabled="isPosted" @click="handleSaveNew">
                    <KTIcon
                        icon-name="plus-circle"
                        icon-class="fs-2 cursor-pointer hover:dark:text-white hover:text-slate-700"
                    />
                    Save & New</el-dropdown-item
                >
                <el-dropdown-item
                    v-if="hasUuid"
                    @click="handlePrintJournalVoucher"
                >
                    <KTIcon
                        icon-name="printer"
                        icon-type="solid"
                        icon-class="fs-2 cursor-pointer hover:dark:text-white hover:text-slate-700"
                    />
                    Print Journal Voucher
                </el-dropdown-item>
            </el-dropdown-menu>
        </template>
    </el-dropdown>
</template>

<script lang="ts" setup>
import type { JournalEntry } from "~/types/journal-entry";

const props = defineProps<{
    data?: Partial<JournalEntry>;
}>();

const emit = defineEmits([
    "save",
    "save-new",
    "print-journal-voucher",
]);

const isPosted = computed(() => {
    return props.data?.status?.name?.toLowerCase() === "posted";
});

const hasUuid = computed(() => {
    return !!props.data?.uuid;
});

function handleSave() {
    if (!isPosted.value) {
        emit("save");
    }
}

function handleSaveNew() {
    if (!isPosted.value) {
        emit("save-new");
    }
}

function handlePrintJournalVoucher() {
    emit("print-journal-voucher", props.data?.uuid);
}
</script>
