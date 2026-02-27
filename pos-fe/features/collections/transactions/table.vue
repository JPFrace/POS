<template>
    <AppPageTable>
        <template #columns>
            <el-table-column type="selection" width="55" />
            <el-table-column
                prop="date_uploaded"
                label="Date Uploaded"
                width="100"
            />
            <el-table-column
                prop="trans_date"
                label="Transaction Date"
                width="110"
            />
            <el-table-column prop="customer" label="Customer" width="130" />
            <el-table-column
                prop="customer_id"
                label="Customer ID No."
                width="100"
            />
            <el-table-column
                prop="customer_name"
                label="Customer Name"
                width="100"
            />
            <el-table-column prop="or_no" label="OR No." />
            <el-table-column
                prop="particulars"
                label="Particulars"
                width="100"
            />
            <el-table-column
                prop="debit_account"
                label="Debit Account"
                width="100"
            />
            <el-table-column
                prop="debit_amount"
                label="Debit Amount"
                width="100"
            />
            <el-table-column
                prop="credit_account"
                label="Credit Account"
                width="100"
            />
            <el-table-column
                prop="credit_amount"
                label="Credit Amount"
                width="100"
            />
        </template>
    </AppPageTable>
</template>

<script setup lang="ts"></script>
