<template>
    <td
        class="text-end w-[15%] pr-2"
        :class="{
            '!border-b border-solid': last && !final,
            '!border-b-4 !border-double': final,
            'font-bold uppercase': highlightName,
        }"
    >
        {{ money(data?.budget, 2) }}
    </td>
    <td
        class="w-[30%]"
        :class="{
            '!border-b border-solid': last && !final,
            'font-bold uppercase': highlightName,
        }"
    >
        {{ data?.name }}
    </td>
    <td
        class="w-[18.33%] text-end"
        :class="{
            '!border-b border-solid': last && !final,
            '!border-b-4 border-double': final,
        }"
    >
        {{ money(data?.current_month, 2) }}
    </td>
    <td
        class="w-[18.33%] text-end"
        :class="{
            '!border-b border-solid': last && !final,
            '!border-b-4 border-double': final,
        }"
    >
        {{ money(data?.year_to_date, 2) }}
    </td>
    <td
        class="w-[18.33%] text-end"
        :class="{
            '!border-b border-solid': last && !final,
            '!border-b-4 border-double': final,
        }"
    >
        {{ money(data?.budget_to_date, 2) }}
    </td>
</template>

<script lang="ts" setup>
import type { StatementIncomeExpenseRow } from "~/types/reports";

interface Props {
    data: Partial<StatementIncomeExpenseRow>;
    first: boolean;
    last: boolean;
    final: boolean;
    highlightName: boolean;
}

defineProps<Partial<Props>>();
</script>
