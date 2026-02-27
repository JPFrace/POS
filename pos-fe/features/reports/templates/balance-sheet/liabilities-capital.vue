<template>
    <tr>
        <th class="text-center" colspan="2">LIABILITIES AND CAPITAL</th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
        <td class="text-start" colspan="3">Current Liabilities</td>
    </tr>
    <tr v-for="(row, index) in data?.current_liabilities ?? []">
        <td class="w-[30%]">{{ row.name }}</td>
        <td
            class="w-[10%]"
            :class="{
                '!border-b-2':
                    (data?.current_liabilities ?? []).length - 1 == index,
            }"
        >
            {{ index == 0 ? "PHP" : "" }}
        </td>
        <td
            class="w-[15%] text-end"
            :class="{
                '!border-b-2':
                    (data?.current_liabilities ?? []).length - 1 == index,
            }"
        >
            {{ money(row.debits, 2) }}
        </td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
        <td class="text-start">Total Current Liabilities</td>
        <td></td>
        <td></td>
        <td class="text-end w-[15%]">
            {{ money(data?.total_current_liabilities?.debits ?? 0, 2) }}
        </td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
        <td class="text-start" colspan="3">Long-Term Liabilities</td>
    </tr>
    <tr v-for="(row, index) in data?.longterm_liabilities ?? []">
        <td class="w-[30%]">{{ row.name }}</td>
        <td
            class="w-[10%]"
            :class="{
                '!border-b-2':
                    (data?.longterm_liabilities ?? []).length - 1 == index,
            }"
        ></td>
        <td
            class="w-[15%] text-end"
            :class="{
                '!border-b-2':
                    (data?.longterm_liabilities ?? []).length - 1 == index,
            }"
        >
            {{ money(row.debits, 2) }}
        </td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
        <td class="text-start">Total Long-Term Liabilities</td>
        <td></td>
        <td></td>
        <td class="text-end w-[15%] !border-b-2">
            {{ money(data?.total_longterm_liabilities?.debits ?? 0, 2) }}
        </td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
        <td class="text-start">Total Liabilities</td>
        <td></td>
        <td></td>
        <td class="text-end w-[15%]">
            {{ money(data?.total_liabilities ?? 0, 2) }}
        </td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
        <td class="text-start" colspan="3">Capital</td>
    </tr>
    <tr v-for="(row, index) in data?.capital ?? []">
        <td class="w-[30%]">{{ row.name }}</td>
        <td
            class="w-[10%]"
            :class="{
                '!border-b-2': (data?.capital ?? []).length - 1 == index,
            }"
        ></td>
        <td
            class="w-[15%] text-end"
            :class="{
                '!border-b-2': (data?.capital ?? []).length - 1 == index,
            }"
        >
            {{ money(row.credits, 2) }}
        </td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
        <td class="text-start">Total Capital</td>
        <td></td>
        <td></td>
        <td class="text-end w-[15%] !border-b-2">
            {{ money(data?.total_capital?.credits ?? 0, 2) }}
        </td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
        <td class="text-start">Total Liabilities & Capital</td>
        <td></td>
        <td></td>
        <td class="text-end w-[15%] !border-b-4">
            {{ money(data?.total_liabilities_capital ?? 0, 2) }}
        </td>
    </tr>
</template>

<script setup lang="ts">
const data = defineModel();
</script>
