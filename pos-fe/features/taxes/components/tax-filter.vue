<template>
    <div class="flex flex-col pb-10 px-2">
        <slot name="options" />
    </div>
    <!-- <div class="flex items-center justify-end gap-x-4 mt-8">
        <Button variant="light" @click="cancel">Cancel / Clear</Button>
        <Button @click="search">Search</Button>
    </div> -->
</template>
<script lang="ts" setup>
const route = useRoute();

interface Props {
    transform?: Function;
}

const props = defineProps<Props>();

const { $bus } = useNuxtApp();

const id = route.fullPath;
const visible = ref(false);
const form = defineModel();

const cancel = () => {
    form.value = clearKeyValue(form.value);
    $bus.emit(`${id}:search`, form.value);
    visible.value = false;
};

const search = () => {
    let transform = form.value;
    if (props.transform) {
        transform = props.transform(form);
    }
    $bus.emit(`${id}:search`, transform);
    visible.value = false;
};
</script>
