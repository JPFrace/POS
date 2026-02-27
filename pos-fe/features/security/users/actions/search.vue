<template>
    <Input group placeholder="Search">
        <template #default>
            <InputNative
                v-model="model"
                placeholder="Search"
                title="Type and enter key to search"
                @keyup.enter="click"
            />
        </template>
        <template #append>
            <div>
                <KTIcon
                    title="Click me to search"
                    icon-name="click"
                    icon-class="fs-2 cursor-pointer"
                    @click="click"
                />
            </div>
        </template>
    </Input>
    <Filter />
</template>

<script lang="ts" setup>
import Filter from "./filter.vue";
const { $bus } = useNuxtApp();

const model = defineModel();

const click = () => {
    $bus.emit("users:search", { name: model.value });
};
</script>
