<template>
    <el-select v-model="selected" placeholder="Select Group">
        <el-option
            v-for="(item, index) in groups"
            :key="index"
            :label="item == 'All' ? 'All' : item"
            :value="item == 'All' ? 'All' : item"
        />
    </el-select>
</template>
<script lang="ts" setup>
const selected = defineModel("selected");
const groups = defineModel("groups");
</script>
