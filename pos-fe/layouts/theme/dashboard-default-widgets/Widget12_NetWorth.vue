<template>
    <div class="networth-card">
        <div class="networth-header">
            <div>
                <p class="label">NET WORTH</p>
                <h2 class="amount">₱ 1,345,622.85</h2>
            </div>

            <select class="period-select">
                <option>Monthly</option>
            </select>
        </div>

        <div class="chart-area">
            <!-- Your bar chart here -->
        </div>

        <div class="legend">
            <div class="legend-item"><span class="dot assets" />Assets</div>
            <div class="legend-item">
                <span class="dot liabilities" />Liabilities
            </div>
        </div>

        <button class="view-details">View Details</button>
    </div>
</template>
<script lang="ts" setup></script>
<style scoped lang="scss">
.networth-card {
    background: #f5f7fa;
    padding: 24px;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    height: 100%;
}

.networth-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.label {
    font-size: 12px;
    color: #6b7280;
    font-weight: 600;
}

.amount {
    font-size: 20px;
    font-weight: 700;
}

.period-select {
    background: white;
    border-radius: 8px;
    padding: 6px 12px;
    border: 1px solid #e5e7eb;
}

.chart-area {
    height: 180px;
    margin-bottom: 16px;
}

.legend {
    display: flex;
    gap: 20px;
    font-size: 12px;
    margin-bottom: 16px;
}

.dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
    margin-right: 6px;
}

.assets {
    background: #00d1ff;
}

.liabilities {
    background: #1e293b;
}

.view-details {
    width: 100%;
    padding: 10px;
    border-radius: 12px;
    background: #e3edf3;
    border: none;
    font-weight: 500;
}
</style>
