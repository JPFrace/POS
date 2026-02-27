<template>
    <el-dropdown-item @click="handleExportExcel">
        <KTIcon
            icon-name="file-down"
            icon-class="fs-2 cursor-pointer hover:dark:text-white hover:text-slate-700"
        />
        Export to Excel
    </el-dropdown-item>
</template>

<script lang="ts" setup>
import { useExcelExport } from "~/composables/reports/useExcelExport";

const { exportToExcel } = useExcelExport();

const handleExportExcel = () => {
    exportToExcel();
};
</script>
