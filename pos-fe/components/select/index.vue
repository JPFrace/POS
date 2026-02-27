<template>
    <el-select
        v-model="selected"
        filterable
        reserve-keyword
        remote-show-suffix
        :remote="remote"
        :multiple="multiple"
        :remote-method="remoteHandler"
        :loading="loading"
        :change="change"
        :visible-change="visibleChange"
        :remove-tag="removeTag"
        :allow-create="allowCreate"
        :clear="clear"
        :blur="blur"
        :focus="focus"
        :loading-text="loadingText"
        :no-match-text="noMatchText"
        :no-data-text="noDataText"
        value-key="id"
        size="large"
        :class="classNames(invalidClass)"
        :style="classNames(style)"
        :disabled="props.disabled"
    >
        <slot name="default" />
        <slot name="customColumn" v-if="column && customColumn" :data="data" />
        <Column v-if="column && !customColumn" :data="data" />
        <Group v-if="group" :data="data" />

        <el-option
            v-for="item in data"
            v-if="!$slots.default && !group && !column"
            :key="item.id"
            :label="item.label"
            :value="item"
        />
        <template #loading>
            <i class="ki-duotone ki-loading animate-spin fs-2x">
                <span class="path1" />
                <span class="path2" />
            </i>
        </template>
    </el-select>
</template>

<script lang="ts" setup>
import { computed, ref } from "vue";
import { onRemote } from "./common";
import type { Methods, Option, Select } from "~/types/form";

import Column from "./components/column.vue";
import Group from "./components/group.vue";

const props = withDefaults(defineProps<Select>(), {
    method: "GET",
    isValid: null,
    disabled: undefined,
});

const data = defineModel<Option[]>("data");

const selected = defineModel<Option[] | Option>("selected");

const _loading = ref(false);

const loading = computed(() => {
    if (!props.url) {
        return props.loading;
    }

    return _loading.value;
});

const setLoading = (status: boolean) => {
    if (props.loading) {
        _loading.value = status;
    }
};

const remoteHandler = async (query: string) => {
    if (!props.url) {
        return props.remoteMethod(query, props.method);
    }

    setLoading(true);

    const _query = props.mapQuery ? props.mapQuery(query) : query;

    try {
        let result = await onRemote(props.url, props.method, _query);

        if (props.mapResult) {
            result = props.mapResult(result) as Option[];
        }

        setLoading(false);

        if (result) {
            data.value = result;
        }
    } catch (error) {
        setLoading(false);
        throw error;
    }
};

const style = computed(() => {
    if (props.isValid === false) {
        return "--el-border-color: var(--bs-form-invalid-border-color)";
    }
    return "";
});

const invalidClass = computed(() => {
    if (props.isValid === false) {
        return "is-invalid";
    }
    return "";
});

const onSearchSelect = (query: any, callback: Function) => {
    return callback(remoteHandler(query));
};

defineExpose({
    data,
    onSearchSelect,
});
</script>
