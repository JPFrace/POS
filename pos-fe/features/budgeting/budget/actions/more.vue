<template>
    <el-dropdown>
        <Button light icon="dots-square-vertical" :icon-color="{
            color: 'primary',
            active: 'secondary',
        }" class="!uppercase dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-expanded="false" />
        <template #dropdown>
            <el-dropdown-menu>
                <el-dropdown-item v-if="!props.isPosted" @click="handleSave">
                    <KTIcon icon-name="save-2"
                        icon-class="fs-2 cursor-pointer hover:dark:text-white hover:text-slate-700" />
                    Save
                </el-dropdown-item>
                <el-dropdown-item v-if="!props.isPosted">
                    <KTIcon icon-name="printer"
                        icon-class="fs-2 cursor-pointer hover:dark:text-white hover:text-slate-700" />
                    Save & Print
                </el-dropdown-item>
                <el-dropdown-item-divider />
                <el-dropdown-item @click="handleSaveAs">
                    <KTIcon icon-name="some-files"
                        icon-class="fs-2 cursor-pointer hover:dark:text-white hover:text-slate-700" />
                    Save As
                </el-dropdown-item>
            </el-dropdown-menu>
        </template>
    </el-dropdown>
</template>

<script lang="ts" setup>

interface Props {
  isPosted: boolean;
}

const props = defineProps<Props>();

const emit = defineEmits(["save", "saveAs"]);

function handleSave() {
    emit("save");
}

function handleSaveAs() {
    emit("saveAs");
}
</script>
