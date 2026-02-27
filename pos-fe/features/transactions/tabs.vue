<template>
    <div>
        <div class="flex items-center justify-between -mt-12">
            <el-menu
                v-model:default-active="activeTab"
                mode="horizontal"
                class="flex-1 menu-no-background"
                @select="handleTabChange"
            >
                <el-menu-item index="journals">Journal Entries</el-menu-item>
                <el-menu-item index="money_receives"
                    >Money Receives</el-menu-item
                >
                <el-menu-item index="payments_made">Payments Made</el-menu-item>
                <el-menu-item index="invoices">Invoices</el-menu-item>
                <el-menu-item index="purchase_orders"
                    >Purchase Orders</el-menu-item
                >
                <el-menu-item index="bills">Bills</el-menu-item>
                <el-menu-item index="reconciliation"
                    >Reconciliation</el-menu-item
                >
            </el-menu>
            <div
                class="flex-shrink-0 flex flex-col pb-6 mr-8"
                style="width: 300px"
            >
                <label class="font-medium mb-1 text-left text-sm"
                    >Date Range</label
                >
                <el-date-picker
                    v-model="dates"
                    type="daterange"
                    unlink-panels
                    range-separator="To"
                    start-placeholder="Start date"
                    end-placeholder="End date"
                    :shortcuts="shortcuts"
                    size="default"
                    style="width: 100%"
                />
            </div>
        </div>

        <div class="tab-content">
            <Journals v-if="activeTab === 'journals'" v-model:dates="dates" />
            <MoneyReceives
                v-if="activeTab === 'money_receives'"
                v-model:dates="dates"
            />
            <PaymentsMade
                v-if="activeTab === 'payments_made'"
                v-model:dates="dates"
            />
            <Invoices v-if="activeTab === 'invoices'" v-model:dates="dates" />
            <PurchaseOrders
                v-if="activeTab === 'purchase_orders'"
                v-model:dates="dates"
            />
            <Bills v-if="activeTab === 'bills'" v-model:dates="dates" />
            <Reconciliation v-if="activeTab === 'reconciliation'" />
        </div>
    </div>
</template>

<script setup lang="ts">
import type { calendar } from "~/types/calendar";
import MoneyReceives from "./money-receives.vue";
import PaymentsMade from "./payments-made.vue";
import Bills from "./bills.vue";
import Invoices from "./invoices.vue";
import PurchaseOrders from "./purchase-orders.vue";
import Journals from "./journals.vue";
import Reconciliation from "./reconciliation.vue";

type ShortcutItem = { text: string; value: () => [Date, Date] };

const activeTab = ref("journals");

const client = useSanctumClient();
const { data: calendars } = useAsyncData<any[]>(
    `transactions.tabs.calendars`,
    () =>
        client("/api/accounting/calendar-years", {
            method: "GET",
        }),
    {
        server: false,
        lazy: true,
        immediate: true,
    },
);

const staticShortcuts: ShortcutItem[] = [
    {
        text: "This Day",
        value: (): [Date, Date] => {
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const endOfDay = new Date(today);
            endOfDay.setHours(23, 59, 59, 999);
            return [today, endOfDay];
        },
    },
    {
        text: "This Month",
        value: (): [Date, Date] => {
            const now = new Date();
            const start = new Date(now.getFullYear(), now.getMonth(), 1);
            const end = new Date(now.getFullYear(), now.getMonth() + 1, 0);
            end.setHours(23, 59, 59, 999);
            return [start, end];
        },
    },
    {
        text: "Last Week",
        value: (): [Date, Date] => {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
            return [start, end];
        },
    },
    {
        text: "Last Month",
        value: (): [Date, Date] => {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
            return [start, end];
        },
    },
    {
        text: "Last 3 Months",
        value: (): [Date, Date] => {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
            return [start, end];
        },
    }
];

const shortcuts = computed<ShortcutItem[]>(() => {
    const list = [...staticShortcuts];
    const raw = calendars.value;
const calList = Array.isArray(raw) ? raw : (raw && typeof raw === 'object' && 'data' in raw ? (raw as { data: calendar[] }).data : []);
    for (const cal of calList) {
        if (cal.start_date != null && cal.end_date != null) {
            list.push({
                text: cal.year,
                value: (): [Date, Date] => [
                    new Date(cal.start_date!),
                    new Date(cal.end_date!),
                ],
            });
        }
    }
    return list;
});

const getSavedDates = (tab: string): [Date, Date] => {
    const savedPreset =
        localStorage.getItem(`datePreset_${tab}`) || "This Month";
    const list = shortcuts.value;
    const preset = list.find((s) => s.text === savedPreset);
    return preset ? preset.value() : list[1].value();
};

const dates = ref<[Date, Date]>(getSavedDates(activeTab.value));

const selectedPreset = ref<string | null>(
    localStorage.getItem(`datePreset_${activeTab.value}`) || "This Month",
);

watch(dates, (newDates) => {
    if (newDates) {
        const list = shortcuts.value;
        const matchingPreset = list.find((shortcut) => {
            const presetDates = shortcut.value();
            return (
                presetDates[0].getTime() === newDates[0].getTime() &&
                presetDates[1].getTime() === newDates[1].getTime()
            );
        });

        if (matchingPreset) {
            selectedPreset.value = matchingPreset.text;
            localStorage.setItem(
                `datePreset_${activeTab.value}`,
                matchingPreset.text,
            );
        } else {
            // Custom date range selected
            selectedPreset.value = null;
            localStorage.removeItem(`datePreset_${activeTab.value}`);
            // Store custom dates
            localStorage.setItem(
                `customDateRange_${activeTab.value}`,
                JSON.stringify(newDates),
            );
        }
    }
});

const handleTabChange = (index: string) => {
    activeTab.value = index;
    const savedPreset = localStorage.getItem(`datePreset_${index}`);
    const list = shortcuts.value;

    if (savedPreset) {
        const preset = list.find((s) => s.text === savedPreset);
        dates.value = preset ? preset.value() : list[1].value();
    } else {
        const saved = localStorage.getItem(`customDateRange_${index}`);
        if (saved) {
            const [start, end] = JSON.parse(saved);
            dates.value = [new Date(start), new Date(end)];
        } else {
            dates.value = list[1].value();
        }
    }
};
</script>

<style scoped>
.menu-no-background {
    background-color: transparent !important;
    border-bottom: none !important;
}
</style>
