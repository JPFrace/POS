<template>
    <base-dashboard-layout>
        <!-- Greeting -->
        <div class="mb-6">
            <dashboard-greeting />
        </div>

        <!-- MAIN DASHBOARD GRID -->
        <div class="dashboard-grid">
            <!-- LEFT SIDE -->
            <div class="left-section">
                <WidgetNetIncome
                    :income="69700"
                    :expenses="45200"
                    :chart-size="250"
                />
                <WidgetNetWorthChart />
            </div>

            <!-- RIGHT SIDE -->
            <div class="right-section">
                <!-- Mini KPI Grid -->
                <div class="mini-grid">
                    <WidgetKPI
                        label="Cash in Bank"
                        :amount="2456890"
                        :percentage="2.5"
                    />

                    <WidgetKPI
                        label="Pending Invoices"
                        :amount="200890"
                        :percentage="1.8"
                    />

                    <WidgetKPI
                        label="Total Expenses"
                        :amount="2456890"
                        :percentage="-3.2"
                    />

                    <WidgetKPI
                        label="Total Sales"
                        :amount="2456890"
                        :percentage="4.5"
                    />
                </div>
                <WidgetUpcomingBills />
            </div>
        </div>
        <div class="mt-6">
            <WidgetInvoices />
        </div>
        <div class="last-row">
            <WidgetNetIncomeChart />
            <WidgetTransactionHistory />
        </div>
    </base-dashboard-layout>
</template>

<script lang="ts" setup>
import BaseDashboardLayout from "@/features/dashboard/components/base-dashboard-layout.vue";
//import { getAssetPath } from "~/core/helpers/assets";
//import Widget1 from "~/layouts/theme/dashboard-default-widgets/Widget1.vue";
//import Widget2 from "~/layouts/theme/dashboard-default-widgets/Widget2.vue";
import WidgetNetIncome from "~/layouts/theme/dashboard-default-widgets/Widget3-CircularProg.vue";
//import Widget4 from "~/layouts/theme/dashboard-default-widgets/Widget4.vue";
//import Widget5 from "~/layouts/theme/dashboard-default-widgets/Widget5.vue";
//import Widget6 from "~/layouts/theme/dashboard-default-widgets/Widget6.vue";
import WidgetKPI from "~/layouts/theme/dashboard-default-widgets/Widget11_KPI.vue";
import WidgetNetWorthChart from "~/layouts/theme/dashboard-default-widgets/Widget12_NetWorth.vue";
import WidgetUpcomingBills from "~/layouts/theme/dashboard-default-widgets/Widget13_UpcomingBills.vue";
import WidgetInvoices from "~/layouts/theme/dashboard-default-widgets/Widget14_Invoices.vue";
import WidgetNetIncomeChart from "~/layouts/theme/dashboard-default-widgets/Widget15_NetIncome.vue";
import WidgetTransactionHistory from "~/layouts/theme/dashboard-default-widgets/Widget16_TransactionHistory.vue";
//import Widget7 from "~/layouts/theme/dashboard-default-widgets/Widget7.vue";
//import Widget8 from "~/layouts/theme/dashboard-default-widgets/Widget8.vue";
//import Widget9 from "~/layouts/theme/dashboard-default-widgets/Widget9.vue";
//import Widget10 from "~/layouts/theme/dashboard-default-widgets/Widget10.vue";
// import MixedWidget5 from "~/layouts/theme/widgets/mixed/Widget5.vue";
//import BookmarkList from "~/features/reports/bookmark/list.vue";
import { usePageTitle } from "~/composables/usePageTitle";

usePageTitle();

definePageMeta({
    showToolbar: false,
}); //added to hide toolbar on this page

// a test
</script>
<style scoped lang="scss">
.dashboard-grid {
    display: grid;
    grid-template-columns: 472px minmax(400px, 1fr);
    column-gap: 12px; /* reduce from 24px */
    row-gap: 24px;
    align-items: stretch;
}

.left-section {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.right-section {
    display: flex;
    flex-direction: column;
    gap: 24px;
    width: 100%;
}
.mini-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 24px;
}

/* .mini-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    column-gap: 16px; 
    row-gap: 32px; 
} */
.last-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    margin-top: 24px;
}
</style>
