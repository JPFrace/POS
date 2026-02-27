<template>
    <div class="flex flex-col">
        <label class="form-label">Date range</label>
        <el-date-picker
            v-model="model"
            type="daterange"
            unlink-panels
            range-separator="To"
            start-placeholder="Start date"
            end-placeholder="End date"
            :shortcuts="shortcuts"
            :size="size"
            popper-class="filter-daterange-presets-wide"
        />
    </div>
</template>

<script setup lang="ts">
import type { calendar } from '~/types/calendar';

const model = defineModel<[Date, Date] | undefined>();

const size = ref<"default" | "large" | "small">("large");

type ShortcutItem = { text: string; value: () => [Date, Date] };

const route = useRoute();
const client = useSanctumClient();
const { data: calendars } = useAsyncData<any[]>(
    `${id(useRoute().fullPath)}.calendars`,
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
        text: "Last week",
        value: (): [Date, Date] => {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
            return [start, end];
        },
    },
    {
        text: "This month",
        value: (): [Date, Date] => {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 1);
            return [start, end];
        },
    },
    {
        text: "Last month",
        value: (): [Date, Date] => {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
            return [start, end];
        },
    },
    {
        text: "Last 3 months",
        value: (): [Date, Date] => {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
            return [start, end];
        },
    },
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

const dates = ref<Date[]>(staticShortcuts[0].value());
</script>