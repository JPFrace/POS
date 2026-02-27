<template>
    <Select
        v-model:data="data"
        v-model:selected="selected"
        url="/api/test"
        remote
        loading
        multiple
    >
        <template #default>
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
                >
                    <template #default>
                        <div class="flex items-center justify-start gap-x-2">
                            <i class="ki-duotone ki-ts fs-2x">
                                <span class="path1"/>
                                <span class="path2"/>
                                <span class="path3"/>
                            </i>
                            <span>{{ item.label }}</span>
                        </div>
                    </template>
                </el-option>
            </el-option-group>
        </template>
    </Select>
    <span class="mt-2">Result: {{ selected }}</span>
</template>
<script lang="ts" setup>
import { ref } from "vue";
import type { Option } from "~/types/form";

const data = ref<Option[]>([]);
const selected = ref<Option[]>();
</script>
