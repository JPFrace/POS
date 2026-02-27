<template>
    <el-dropdown-item @click="handlePrint">
        <KTIcon
            icon-name="printer"
            icon-class="fs-2 cursor-pointer hover:dark:text-white hover:text-slate-700"
        />
        Print
    </el-dropdown-item>
</template>

<script setup lang="ts">
const handlePrint = () => {
    setTimeout(() => {
        window.print();
    }, 300);
};
</script>
