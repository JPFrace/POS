<template>
    <div
        class="group/component relative flex-1 flex flex-col items-center justify-center"
    >
        <Render
            class="transform transition-all ease-in-out delay-100 duration-500"
            :label="`${composable.component.label(component)}`"
            :placeholder="`${composable.component.placeholder(component)}`"
            :float="composable.component.float(component)"
            :id="composable.component.id(component)"
            :multiple="composable.component.multiple(component)"
            :is-valid="isValid"
            :error="error"
            v-model="model"
            v-model:data="options"
            v-model:selected="selected"
            parentClass="w-full"
        />
    </div>
</template>

<script lang="ts" setup>
import { debounce } from "lodash";
import { inject } from "vue";
import { type Component, type Column } from "~/types/builder";
import type { Option } from "~/types/form";

const { $bus } = useNuxtApp();

const props = defineProps<{ component: Component; column: Column }>();

const key = inject("key");

const selected = ref<Option | Option[]>();

const composable = useBuilder(key as string);

const formStore = useFormBuilderStore();

formStore.identifier(key as string);

const model = ref(composable.component.value(props.component));

const Render = builder.component(composable.component.type(props.component));

const options = computed(() => composable.component.options(props.component));

const isValid = ref<boolean | null>(null);
const error = ref<string | null>(null);

watch(props.component, (value) => {
    model.value = composable.component.value(value);
    isValid.value = composable.component.isValid(props.component);
    error.value = composable.component.error(props.component);
});

watch(
    model,
    debounce((value) => {
        composable.component.setValue(props.component, value);
        $bus.emit(`${key}-registration:change`, {
            component: props.component,
            value,
        });
    }, 300)
);
</script>
