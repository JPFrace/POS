<template>
    <tr>
        <th class="text-center" colspan="2">ASSETS</th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
        <td class="text-start" colspan="3">Current Assets</td>
    </tr>
    <tr v-for="(row, index) in data?.current_assets ?? []">
        <td class="w-[30%]">{{ row.name }}</td>
        <td
            class="w-[10%]"
            :class="{
                '!border-b-2': (data?.current_assets ?? []).length - 1 == index,
            }"
        >
            {{ index == 0 ? "PHP" : "" }}
        </td>
        <td
            class="w-[15%] text-end"
            :class="{
                '!border-b-2': (data?.current_assets ?? []).length - 1 == index,
            }"
        >
            {{ money(row.debits, 2) }}
        </td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
        <td class="text-start">Total Current Assets</td>
        <td></td>
        <td></td>
        <td class="text-end w-[15%]">
            {{ money(data?.total_current_assets?.debits ?? 0, 2) }}
        </td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
        <td class="text-start" colspan="3">Property and Equipment</td>
    </tr>
    <tr v-for="(row, index) in data?.property_assets ?? []">
        <td class="w-[30%]">{{ row.name }}</td>
        <td
            class="w-[10%]"
            :class="{
                '!border-b-2':
                    (data?.property_assets ?? []).length - 1 == index,
            }"
        ></td>
        <td
            class="w-[15%] text-end"
            :class="{
                '!border-b-2':
                    (data?.property_assets ?? []).length - 1 == index,
            }"
        >
            {{ money(row.debits, 2) }}
        </td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
        <td class="text-start">Total Property and Equipment</td>
        <td></td>
        <td></td>
        <td class="text-end w-[15%]">
            {{ money(data?.total_property_assets?.debits ?? 0, 2) }}
        </td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
        <td class="text-start">Other Assets</td>
        <td class="!border-b-2"></td>
        <td class="!border-b-2"></td>
        <td class="text-end w-[15%]"></td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
        <td class="text-start">Total Other Assets</td>
        <td class=""></td>
        <td class=""></td>
        <td class="text-end w-[15%] !border-b-2">
            {{ money(0, 2) }}
        </td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
        <td class="text-start">Total Assets</td>
        <td class=""></td>
        <td class=""></td>
        <td class="text-end w-[15%] !border-b-2 font-bold">
            <div class="flex items-center justify-between">
                <span>PHP</span>
                <span>{{ money(data?.total_assets ?? 0, 2) }}</span>
            </div>
        </td>
    </tr>
</template>

<script setup lang="ts">
const data = defineModel();
</script>
