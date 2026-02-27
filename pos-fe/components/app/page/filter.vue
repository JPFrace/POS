<template>
    <el-popover
        v-model:visible="visible"
        trigger="click"
        :width="300"
        popper-style="box-shadow: rgb(14 18 22 / 35%) 0px 10px 38px -10px, rgb(14 18 22 / 20%) 0px 10px 20px -15px; padding: 20px;"
    >
        <template #reference>
            <KTIcon
                title="More filter options"
                icon-name="filter-square"
                icon-class="fs-4x cursor-pointer hover:dark:text-white hover:text-slate-900"
            />
        </template>

        <h3>Filter options</h3>
        <div class="flex flex-col gap-y-4">
            <slot name="options" />
        </div>
        <div class="flex items-center justify-end gap-x-4 mt-8">
            <Button variant="light" @click="cancel">Cancel / Clear</Button>
            <Button @click="search">Search</Button>
        </div>
    </el-popover>
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
