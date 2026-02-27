<template>
    <div class="transaction-card">
        <div class="card-header">
            <p class="label">TRANSACTION HISTORY</p>
        </div>

        <div class="empty-state">No transactions yet</div>
    </div>
</template>
<script lang="ts" setup></script>
<style scoped lang="scss">
.transaction-card {
    background: #f5f7fa;
    padding: 24px;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    min-height: 280px;
}

.empty-state {
    height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #9ca3af;
    font-size: 14px;
}
</style>
