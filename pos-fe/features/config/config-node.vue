<template>
    <div class="mb-4">
        <!-- Node label -->
        <div class="flex items-center" :class="{ 'mb-6': hasChildren, 'mb-1': !hasChildren }">
            <label
            class="cursor-default select-none"
            :class="{
                'font-semibold': hasChildren,
                uppercase: hasChildren,
            }"
            >
            <div class="flex items-center gap-2">
                <KTIcon
                v-if="hasChildren"
                icon-name="setting-2"
                icon-class="fs-2"
                />
                <span>{{ hasChildren ? node.name + " CONFIGURATIONS" : node.name + ":" }}</span>
            </div>

            </label>
        </div>

        <!-- Leaf node input -->
        <div
            v-if="node.type !== 'none' && !hasChildren"
            class="flex items-center gap-2 mt-1"
        >
            <!-- Prefix --> 
            <div v-if="node.use_prefix" class="flex items-center gap-1">
                <input
                    v-model="node.prefix"
                    type="text"
                    class="p-2 border rounded w-30"
                    placeholder="Prefix"
                />
                <span>-</span>
            </div>

            <!-- Main control based on type -->
            <div class="flex-1 min-w-[120px]">
                <template v-if="node.type === 'string'">
                    <input
                        v-model="node.value"
                        type="text"
                        class="w-full p-2 border rounded"
                    />
                </template>

                <template
                    v-else-if="
                        node.type === 'integer' || node.type === 'decimal'
                    "
                >
                    <input
                        v-model.number="node.value"
                        type="number"
                        class="w-full p-2 border rounded"
                    />
                </template>

                <template v-else-if="node.type === 'boolean'">
                    <el-switch
                        v-model="booleanValue"
                        inline-prompt
                        active-text="Yes"
                        inactive-text="No"
                    />
                </template>

                <template v-else-if="node.type === 'json:single'">
                    <el-select
                        v-model="node.value"
                        placeholder="Select"
                        class="w-full"
                    >
                        <el-option
                            v-for="item in node.options"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value"
                        />
                    </el-select>
                </template>

                <template v-else-if="node.type === 'json:multi'">
                    <el-select
                        v-model="node.value"
                        multiple
                        collapse-tags
                        placeholder="Select"
                        class="w-full"
                    >
                        <el-option
                            v-for="item in node.options"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value"
                        />
                    </el-select>
                </template>
            </div>

            <!-- Suffix -->
            <div v-if="node.use_suffix" class="flex items-center gap-1">
                <span>-</span>
                <input
                    v-model="node.suffix"
                    type="text"
                    class="w-30 p-2 border rounded"
                    placeholder="Suffix"
                />
            </div>
        </div>

        <!-- Recursive children -->
        <div v-if="hasChildren" class="mt-2 space-y-2">
            <ConfigNode
                v-for="child in node.children"
                :key="child.uuid"
                :node="child"
                :level="level + 1"
            />
        </div>
    </div>
</template>

<script lang="ts" setup>
import { trimEnd } from "lodash";
import { computed } from "vue";

interface Config {
    uuid: string;
    name: string;
    slug: string;
    type: string;
    options?: any[];
    value: any;
    use_prefix?: number;
    prefix?: string | null;
    use_suffix?: number;
    suffix?: string | null;
    children?: Config[];
}

const props = defineProps<{
    node: Config;
    level?: number;
}>();

const level = computed(() => props.level ?? 0);
const hasChildren = computed(() => !!props.node.children?.length);

const booleanValue = computed({
    get: () => props.node.value === "1" || props.node.value === true,
    set: (val: boolean) => {
        props.node.value = val ? "1" : "0";
    },
});
</script>
