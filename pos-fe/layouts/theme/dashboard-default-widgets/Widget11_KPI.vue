<template>
    <div
        class="bg-[#F7F7F9] rounded-[16px] shadow-md w-full h-[147px] p-4 flex items-center gap-4"
    >
        <!-- Icon -->
        <div
            class="w-10 h-10 rounded-xl bg-[#0F1E46] flex items-center justify-center text-white"
        >
            <component :is="iconComponent" />
        </div>

        <!-- Content -->
        <div>
            <p class="text-[11px] font-semibold text-[#8A94A6] uppercase">
                {{ label }}
            </p>

            <h3 class="text-[18px] font-bold text-[#0F172A]">
                ₱ {{ formattedAmount }}
            </h3>

            <p
                class="text-[12px]"
                :class="percentage >= 0 ? 'text-green-600' : 'text-red-600'"
            >
                {{ percentage >= 0 ? "▲" : "▼" }}
                {{ Math.abs(percentage) }}% vs last month
            </p>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from "vue";

const props = defineProps<{
    label: string;
    amount: number;
    percentage: number;
    iconComponent?: any;
}>();

const formattedAmount = computed(() => props.amount.toLocaleString());
</script>
