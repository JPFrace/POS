<template>
    <div class="bills-card">
        <div class="bills-header">
            <h3>UPCOMING BILLS</h3>
            <a href="#">View All</a>
        </div>

        <div class="bill-item" v-for="bill in bills" :key="bill.id">
            <div class="date-box">
                <span class="month">{{ bill.month }}</span>
                <span class="day">{{ bill.day }}</span>
            </div>

            <div class="bill-info">
                <p class="bill-name">{{ bill.name }}</p>
                <p class="bill-type">{{ bill.type }}</p>
            </div>

            <div class="bill-amount">₱ {{ bill.amount }}</div>
        </div>
    </div>
</template>
<script lang="ts" setup></script>
<style scoped lang="scss">
.bills-card {
    background: #0f1b3d;
    color: white;
    padding: 24px;
    border-radius: 16px;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.bills-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 24px;
}

.bills-header h3 {
    font-size: 14px;
    font-weight: 600;
}

.bills-header a {
    font-size: 12px;
    color: #9ca3af;
}

.bill-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 18px;
}

.date-box {
    background: #1f2a4d;
    border-radius: 10px;
    padding: 8px;
    text-align: center;
    width: 50px;
}

.month {
    font-size: 10px;
    display: block;
}

.day {
    font-size: 16px;
    font-weight: bold;
}

.bill-info {
    flex: 1;
    margin-left: 12px;
}

.bill-name {
    font-weight: 600;
}

.bill-type {
    font-size: 12px;
    color: #9ca3af;
}

.bill-amount {
    background: white;
    color: #111827;
    padding: 6px 12px;
    border-radius: 10px;
    font-weight: 600;
}
</style>
