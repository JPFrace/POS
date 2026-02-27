<template>
    <TransitionGroup
        tag="div"
        role="Column"
        class="relative group/column w-full gap-st-md flex items-center justify-center"
        enter-active-class="transition-all transform ease-in-out delay-100 duration-300"
        leave-active-class="transition-all transform ease-in-out delay-200 duration-500"
        enter-from-class="-translate-y-[0.5rem] opacity-0"
        enter-to-class="translate-y-[0.5rem] opacity-100"
        leave-from-class="translate-y-[2rem] opacity-100"
        leave-to-class="-translate-y-[8rem] opacity-0"
    >
        <Component
            v-for="component in column.components"
            :component="component"
            :column="column"
            :key="component.id"
        />
    </TransitionGroup>
</template>
<script lang="ts" setup>
import Component from "./component.vue";
import { type Row, type Column } from "~/types/builder";

const props = defineProps<{ column: Column; row: Row }>();
</script>
