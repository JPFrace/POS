<template>
    <TransitionGroup
        tag="div"
        role="Row"
        class="group/row w-full gap-x-st-md flex items-center justify-start border-0 border-slate-500 dark:border-slate-200"
        enter-active-class="transition-all transform ease-in-out delay-100 duration-300"
        leave-active-class="transition-all transform ease-in-out delay-200 duration-500"
        enter-from-class="-translate-y-[0.5rem] opacity-0"
        enter-to-class="translate-y-[0.5rem] opacity-100"
        leave-from-class="translate-y-[2rem] opacity-100"
        leave-to-class="-translate-y-[8rem] opacity-0"
    >
        <Column
            v-for="column in row.columns"
            :column="column"
            :row="row"
            :key="column.id"
        />
    </TransitionGroup>
</template>

<script lang="ts" setup>
import Column from "./column.vue";
import { type Row } from "~/types/builder";

const props = defineProps<{ row: Row }>();
</script>
