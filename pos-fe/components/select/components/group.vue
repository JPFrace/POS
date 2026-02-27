<template>
    <el-option-group
        v-for="option in data"
        :key="option.id"
        :label="option.label"
    >
        <el-option
            v-for="item in option.children"
            :key="item.id"
            :label="item.label"
            :value="item"
        />
    </el-option-group>
</template>

<script lang="ts" setup>
import type { Option } from "~/types/form";

interface Props {
    data?: Option[] | null;
}

withDefaults(defineProps<Props>(), {
    data: null,
});
</script>
