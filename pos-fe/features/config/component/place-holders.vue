<template>
    <div>
        <!-- Info icon that opens the popup -->
        <button @click="open = true" class="flex items-center gap-2 text-blue-600 hover:text-blue-800">
            <i class="fas fa-info-circle"></i>
            <span>Placeholders</span>
        </button>

        <!-- Popup modal -->
        <div v-if="open" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
            <div class="bg-white w-96 shadow-lg p-5 animate-fadeIn">
                <h3 class="font-semibold mb-3 text-center">Available Placeholders</h3>

                <ul class="space-y-2 h-96 overflow-y-auto w-full p-0">
                    <li v-for="(value, key) in placeholders" :key="key"
                        class="flex justify-between items-center border rounded-md p-2">
                        <span class="font-medium">{{ key }}</span>
                        <code class="bg-gray-100 px-2 py-1 rounded border">{{ value }}</code>
                    </li>
                </ul>

                <div class="flex justify-end mt-4">
                    <button @click="open = false"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Close</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'

const placeholders = {
    "00000": "Auto Incrementing Number",
    "{MMM}": new Date().toLocaleString('default', { month: 'long' }).toUpperCase(),
    "{MM}": new Date().toLocaleString('default', { month: 'short' }).toUpperCase(),
    "{M}": String(new Date().getMonth() + 1),
    "{YYYY}": new Date().getFullYear(),
    "{YY}": String(new Date().getFullYear()).slice(-2),
    "{DD}": String(new Date().getDate()).padStart(2, '0'),
    "{D}": String(new Date().getDate()),
    "{MMYY}": String(new Date().getMonth() + 1).padStart(2, '0') + String(new Date().getFullYear()).slice(-2),
    "{MMYYYY}": String(new Date().getMonth() + 1).padStart(2, '0') + new Date().getFullYear(),
    "{YYMM}": String(new Date().getFullYear()).slice(-2) + String(new Date().getMonth() + 1).padStart(2, '0'),
    "{YYYYMM}": new Date().getFullYear() + String(new Date().getMonth() + 1).padStart(2, '0'),
    "{DDMMYY}": String(new Date().getDate()).padStart(2, '0') + String(new Date().getMonth() + 1).padStart(2, '0') + String(new Date().getFullYear()).slice(-2),
    "{BANK}": "BPI/MBTC/RCBC",
    "{PMETHOD}": "CHK/CSH/CC",
}

const open = ref(false)
</script>

<style scoped>
.animate-fadeIn {
    animation: fadeIn .2s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(.95);
    }

    to {
        opacity: 1;
        transform: scale(1);
    }
}
</style>
