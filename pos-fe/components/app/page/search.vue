<template>
    <Input group placeholder="Search">
        <template #default>
            <InputNative
                v-model="model[query]"
                :placeholder="placeholder"
                title="Type and enter key to search"
                @keyup.enter="click"
            />
        </template>
        <template #append>
            <KTIcon
                title="Click me to search"
                icon-name="click"
                icon-class="fs-2 cursor-pointer"
                @click="click"
            />
        </template>
    </Input>
    <Filter v-if="!noOptions">
        <template #options>
            <slot name="options" />
        </template>
    </Filter>
</template>

<script lang="ts" setup>
import Filter from "./filter.vue";

const { send } = usePageEvent();

interface Props {
    query: string;
    noOptions?: boolean | null;
    prefix?: string;
    placeholder?: string;
}

const props = defineProps<Props>();

const model = ref<any>({
    [props.query]: "",
});

const key = id(useRoute().fullPath);

const click = () => {
    send(`${props.prefix ?? ""}search`, model.value);
};
</script>
