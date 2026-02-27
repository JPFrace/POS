<template>
    <label v-if="block" class="form-label"
        >{{ label }}
        <span v-if="topDesc" class="text-xs ml-2">({{ topDesc }})</span></label
    >
    <textarea
        :class="`form-control ${classNames(valid)}`"
        v-model="model"
        v-bind="{ ...$attrs }"
    ></textarea>
</template>

<script lang="ts" setup>
interface Props {
    id: string | null;
    value: string | null;
    isValid: boolean | null;
    label: string | null;
    block: boolean | null;
    topDesc: string | null;
}

const props = withDefaults(defineProps<Partial<Props>>(), {
    isValid: null,
});

const model = defineModel<string>();

const valid = computed(() =>
    props.isValid ? "is-valid" : props.isValid === false ? "is-invalid" : ""
);

onMounted(() => {
    console.log(props.isValid);
});
</script>
