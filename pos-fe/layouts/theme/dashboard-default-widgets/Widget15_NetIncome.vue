<template>
    <div class="netincome-card">
        <div class="card-header">
            <div>
                <p class="label">NET INCOME</p>
                <p class="sub-label">January - December 2023</p>
            </div>

            <select class="period-select">
                <option>Monthly</option>
            </select>
        </div>

        <div class="chart-area">
            <!-- Your chart library here -->
            <div class="fake-chart" />
        </div>

        <div class="legend">
            <div><span class="dot income" /> Net Income</div>
            <div><span class="dot gross" /> Gross Sales</div>
            <div><span class="dot interest" />Interest</div>
        </div>
    </div>
</template>
<script lang="ts" setup></script>
<style scoped lang="scss">
.netincome-card {
    background: #f5f7fa;
    padding: 24px;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
}

.label {
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
}

.sub-label {
    font-size: 11px;
    color: #9ca3af;
}

.period-select {
    border-radius: 8px;
    padding: 6px 12px;
    border: 1px solid #e5e7eb;
    background: white;
}

.chart-area {
    height: 200px;
    margin-bottom: 16px;
}

/* Temporary chart placeholder */
.fake-chart {
    width: 100%;
    height: 100%;
    background: linear-gradient(to top, #00d1ff33, transparent);
    border-radius: 12px;
}

.legend {
    display: flex;
    gap: 20px;
    font-size: 12px;
}

.dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
    margin-right: 6px;
}

.income {
    background: #1e293b;
}
.gross {
    background: #00d1ff;
}
.interest {
    background: #60a5fa;
}
</style>
