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
                <el-dropdown-item @click="handleSave">
                    <KTIcon
                        icon-name="save-2"
                        icon-class="fs-2 cursor-pointer hover:dark:text-white hover:text-slate-700"
                    />
                    Save</el-dropdown-item
                >
                <el-dropdown-item>
                    <KTIcon
                        icon-name="printer"
                        icon-class="fs-2 cursor-pointer hover:dark:text-white hover:text-slate-700"
                    />
                    Save & Print</el-dropdown-item
                >
            </el-dropdown-menu>
        </template>
    </el-dropdown>
</template>

<script lang="ts" setup>
const emit = defineEmits(["save"]);

function handleSave() {
    emit("save");
}
</script>
