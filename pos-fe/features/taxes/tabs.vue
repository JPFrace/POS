<template>
    <div>
        <div class="flex items-center justify-between -mt-12">
            <el-menu
                v-model:default-active="activeTab"
                mode="horizontal"
                class="flex-1 menu-no-background"
                @select="handleTabChange"
            >
                <el-menu-item index="returns">Returns</el-menu-item>
                <el-menu-item index="payments">Payments</el-menu-item>
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

        <div class="tab-content"></div>
    </div>
</template>

<script setup lang="ts">
const activeTab = ref("returns");

const shortcuts = [
    {
        text: "This Day",
        value: () => {
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const endOfDay = new Date(today);
            endOfDay.setHours(23, 59, 59, 999);
            return [today, endOfDay] as [Date, Date];
        },
    },
    {
        text: "This Month",
        value: () => {
            const now = new Date();
            const start = new Date(now.getFullYear(), now.getMonth(), 1);
            const end = new Date(now.getFullYear(), now.getMonth() + 1, 0);
            end.setHours(23, 59, 59, 999);
            return [start, end] as [Date, Date];
        },
    },
    {
        text: "Last Week",
        value: () => {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
            return [start, end] as [Date, Date];
        },
    },
    {
        text: "Last Month",
        value: () => {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
            return [start, end] as [Date, Date];
        },
    },
    {
        text: "Last 3 Months",
        value: () => {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
            return [start, end] as [Date, Date];
        },
    },
    {
        text: "Year to Date",
        value: () => {
            const now = new Date();
            const start = new Date(now.getFullYear(), 0, 1);
            const end = new Date();
            end.setHours(23, 59, 59, 999);
            return [start, end] as [Date, Date];
        },
    },
];

const selectedPreset = ref<string | null>(
    localStorage.getItem(`datePreset_${activeTab.value}`) || "This Month",
);

const getSavedDates = (tab: string): [Date, Date] => {
    const savedPreset =
        localStorage.getItem(`datePreset_${tab}`) || "This Month";
    const preset = shortcuts.find((s) => s.text === savedPreset);
    return preset ? preset.value() : shortcuts[1].value();
};

const dates = ref<[Date, Date]>(getSavedDates(activeTab.value));

watch(dates, (newDates) => {
    if (newDates) {
        const matchingPreset = shortcuts.find((shortcut) => {
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

    if (savedPreset) {
        const preset = shortcuts.find((s) => s.text === savedPreset);
        dates.value = preset ? preset.value() : shortcuts[1].value();
    } else {
        const saved = localStorage.getItem(`customDateRange_${index}`);
        if (saved) {
            const [start, end] = JSON.parse(saved);
            dates.value = [new Date(start), new Date(end)];
        } else {
            dates.value = shortcuts[1].value();
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
