<template>
    <div
        class="net-income-card bg-[#F7F7F9] shadow-md rounded-[16px] p-5"
        :class="className"
    >
        <!-- Header -->
        <div class="mb-4">
            <span class="text-gray-500 text-sm font-semibold">
                Total Net Income
            </span>
        </div>

        <!-- Body -->
        <div class="flex flex-col items-center">
            <!-- Chart -->
            <div
                ref="chartRef"
                class="relative flex justify-center items-center"
                :style="{ width: chartSize + 'px', height: chartSize + 'px' }"
            >
                <!-- Center KPI -->
                <div class="absolute text-center z-10">
                    <div class="text-xs text-gray-400 mb-1">Total Revenue</div>

                    <div class="text-3xl font-bold text-gray-900">
                        ₱{{ formatCurrency(net) }}
                    </div>

                    <div class="text-xs text-gray-400 mt-1">This Month</div>
                </div>
            </div>

            <!--Start:Labels -->
            <div class="w-full flex justify-center mt-2">
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-1">
                        <div class="w-1.5 h-1.5 rounded-full bg-[#00D1FF]" />
                        <span class="text-gray-500 text-xs">Income</span>
                    </div>

                    <div class="flex items-center gap-1">
                        <div class="w-1.5 h-1.5 rounded-full bg-[#E1E2E7]" />
                        <span class="text-gray-500 text-xs">Expenses</span>
                    </div>
                </div>
            </div>
            <!-- End:Labels -->
        </div>
    </div>
</template>
<!--Start: Script-->
<script lang="ts">
import { defineComponent, onMounted, ref, computed } from "vue";

export default defineComponent({
    props: {
        className: { type: String, default: "" },
        chartSize: { type: Number, default: 250 },
        income: { type: Number, default: 0 },
        expenses: { type: Number, default: 0 },
    },

    setup(props) {
        const chartRef = ref<HTMLElement | null>(null);

        const net = computed(() => props.income - props.expenses);

        const profitPercent = computed(() => {
            if (props.income === 0) return 0;
            return (net.value / props.income) * 100;
        });

        const formatCurrency = (value?: number) =>
            (value ?? 0).toLocaleString("en-PH");

        const initChart = () => {
            const el = chartRef.value;
            if (!el) return;

            const existingCanvas = el.querySelector("canvas");
            if (existingCanvas) {
                existingCanvas.remove();
            }

            const size = props.chartSize;
            const lineWidth = 14;
            const rotate = 145;

            const canvas = document.createElement("canvas");
            const ctx = canvas.getContext("2d") as CanvasRenderingContext2D;

            canvas.width = canvas.height = size;
            canvas.style.position = "absolute";
            canvas.style.top = "0";
            canvas.style.left = "0";
            canvas.style.zIndex = "0";

            el.appendChild(canvas);

            ctx.translate(size / 2, size / 2);
            ctx.rotate((-1 / 2 + rotate / 180) * Math.PI);

            const radius = (size - lineWidth) / 2;

            const drawCircle = (color: string, percent: number) => {
                ctx.beginPath();
                ctx.arc(0, 0, radius, 0, Math.PI * 2 * percent);
                ctx.strokeStyle = color;
                ctx.lineCap = "round";
                ctx.lineWidth = lineWidth;
                ctx.stroke();
            };

            const expensePercent =
                props.income > 0 ? props.expenses / props.income : 0;

            const netPercent =
                props.income > 0
                    ? (props.income - props.expenses) / props.income
                    : 0;

            drawCircle("#E5E7EB", 1);
            drawCircle("#D1D5DB", expensePercent);
            drawCircle("#38BDF8", netPercent);
        };

        onMounted(() => {
            initChart();
        });

        return {
            chartRef,
            net,
            profitPercent,
            formatCurrency,
        };
    },
});
</script>
<style scoped lang="scss">
.net-income-card {
    width: 472px;
    height: 326px;
    background: #f7f7f9;
    border-radius: 20px;
    box-shadow: 0 10px 24px rgba(0, 0, 0, 0.06);
    padding: 24px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

/* Header */
.card-title {
    font-size: 18px;
    font-weight: 700;
    color: #2f3b4c;
    letter-spacing: 0.5px;
}

/* Chart Area */
.chart-wrapper {
    position: relative;
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Center Text */
.center-content {
    position: absolute;
    text-align: center;
    z-index: 2;
}

.label {
    font-size: 14px;
    color: #7a7a7a;
    margin-bottom: 6px;
}

.amount {
    font-size: 32px;
    font-weight: 700;
    color: #2f3b4c;
}

.month {
    font-size: 14px;
    color: #7a7a7a;
    margin-top: 6px;
}

/* Legend */
.legend {
    display: flex;
    justify-content: center;
    gap: 32px;
    font-size: 14px;
    color: #5f6b7a;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.gray {
    background: #d1d5db;
}

.blue {
    background: #38bdf8;
}
</style>
