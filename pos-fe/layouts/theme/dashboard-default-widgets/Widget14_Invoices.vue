<template>
    <div class="invoices-card">
        <!-- Header -->
        <div class="invoices-header">
            <h3>INVOICES</h3>

            <div class="header-actions">
                <div class="search-box">
                    <input type="text" placeholder="Search invoices" />
                </div>

                <button class="create-btn">Create Invoice</button>
                <button class="filter-btn">Filters</button>
            </div>
        </div>

        <!-- Table -->
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Orders/Type</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="invoice in invoices" :key="invoice.id">
                    <td class="title-cell">
                        <img :src="invoice.logo" />
                        {{ invoice.title }}
                    </td>
                    <td>{{ invoice.date }}</td>
                    <td>{{ invoice.orders }}</td>
                    <td>₱ {{ invoice.amount }}</td>
                    <td>
                        <span :class="['status', invoice.status.toLowerCase()]">
                            {{ invoice.status }}
                        </span>
                    </td>
                    <td class="action">•••</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script lang="ts" setup>
const invoices = [
    {
        id: 1,
        title: "Figma",
        date: "14 Apr 2022",
        orders: 20,
        amount: 285,
        status: "Pending",
        logo: "/figma.svg",
    },
    {
        id: 2,
        title: "Figma",
        date: "14 Apr 2022",
        orders: 20,
        amount: 285,
        status: "Paid",
        logo: "/figma.svg",
    },
];
</script>
<style scoped lang="scss">
.invoices-card {
    background: #f5f7fa;
    padding: 24px;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.invoices-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.invoices-header h3 {
    font-size: 14px;
    font-weight: 600;
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

.search-box input {
    padding: 8px 12px;
    border-radius: 10px;
    border: 1px solid #e5e7eb;
    background: white;
}

.create-btn {
    background: #00d1ff;
    color: white;
    border: none;
    padding: 8px 14px;
    border-radius: 10px;
    font-weight: 500;
}

.filter-btn {
    background: white;
    border: 1px solid #e5e7eb;
    padding: 8px 14px;
    border-radius: 10px;
}

.invoice-table {
    width: 100%;
    border-collapse: collapse;
}

.invoice-table th {
    text-align: left;
    font-size: 12px;
    color: #6b7280;
    padding-bottom: 12px;
}

.invoice-table td {
    padding: 14px 0;
    font-size: 14px;
}

.title-cell {
    display: flex;
    align-items: center;
    gap: 10px;
}

.title-cell img {
    width: 20px;
    height: 20px;
}

.status {
    padding: 6px 10px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 500;
}

.status.pending {
    background: #fff4e5;
    color: #f59e0b;
}

.status.paid {
    background: #e6f9f0;
    color: #10b981;
}

.status.unpaid {
    background: #fdecec;
    color: #ef4444;
}

.action {
    cursor: pointer;
    letter-spacing: 2px;
}
</style>
