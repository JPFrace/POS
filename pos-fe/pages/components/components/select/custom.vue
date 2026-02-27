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
            <el-option
                v-for="item in data"
                :key="item.id"
                :label="item.label"
                :value="item"
                class="flex items-center justify-start gap-x-2"
            >
                <div class="form-check">
                    <input
                        type="checkbox"
                        :checked="checked(item)"
                        :label="item.label"
                        class="form-check-input"
                    >
                </div>
                <label>{{ item.label }}</label>
            </el-option>
        </template>
    </Select>
    <span class="mt-2">Result: {{ selected }}</span>
</template>
<script lang="ts" setup>
import { ref } from "vue";
import type { Option } from "~/types/form";

const data = ref<Option[]>([]);
const selected = ref<Option[]>([]);

const checked = (item: Option) =>
    selected.value.filter((d) => d.id == item.id).length > 0;
</script>
