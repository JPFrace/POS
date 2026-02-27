<template>
    <div>
        <NuxtLayout>
            <template #toolbarLeft>
                <div class="flex items-end gap-4 ml-8 mt-4">
                    <FilterDaterange v-model="dates" />
                    <FilterReports v-model="report" />
                    <FilterPeriods />
                    <Bookmark :report="report as any" :dates="dates" />
                </div>
            </template>

            <template #toolbarRight>
                <div class="flex gap-x-2 mt-12 mr-6">
                    <Button
                        variant="primary"
                        label="Run"
                        :loading="processing"
                        loading-text="Generating..."
                        @click="generateReport"
                    />
                    <More />
                </div>
            </template>
            <div>
                <ClientOnly>
                    <component :is="Template" v-model="dates" />
                </ClientOnly>
            </div>
        </NuxtLayout>
    </div>
</template>

<script lang="ts" setup>
import moment from "moment";
import Bookmark from "~/features/reports/bookmark/star-button.vue";
import FilterDaterange from "~/features/reports/filter-daterange.vue";
import FilterPeriods from "~/features/reports/filter-periods.vue";
import FilterReports from "~/features/reports/filter-reports.vue";
import More from "~/features/reports/actions/more.vue";
import type { Reports } from "~/types/reports";

usePageTitle();
definePageMeta({
  permission: "Reports.View", 
});

const { send, receive, dismiss } = usePageEvent();
const report = ref<Reports | null>(null);
const processing = ref(false);
const end = moment().format("YYYY-MM-DD");
const start = moment().format("YYYY-MM-DD");
const dates = ref<[string, string]>([start, end]);

const modules = import.meta.glob("~/features/reports/templates/*.vue");

const templates: Record<string, string> = {
    Default: "/features/reports/templates/template.vue",
};

const generateReport = () => {
    if (report.value?.id) {
        processing.value = true;
        send("report:show", report.value.id);
    }
};

const Template = shallowRef<any>(null);

watch(report as any, (value: Reports) => {
    if (!value) return;
    if (modules[value?.template]) {
        Template.value = defineAsyncComponent(() =>
            modules[value.template]().then((m: any) => m.default)
        );
    } else {
        Template.value = defineAsyncComponent(() =>
            modules[templates.Default]().then((m: any) => m.default)
        );
        console.error(`${value?.template} is not defined report template.`);
    }
    triggerRef(Template);
});

onBeforeUnmount(() => {
    dismiss("report:done");
});

const reportData = useState("bookmarkData");

onMounted(async () => {
    receive("report:done", () => {
        processing.value = false;
    });

    if (reportData.value) {
        const data = reportData.value as any;

        report.value = {
            id: data.report_id,
            uuid: data.uuid,
            name: data.name,
            description: "",
            template: data.template || templates.Default,
        };

        dates.value = [
            moment(data.date_from).format("YYYY-MM-DD"),
            moment(data.date_to).format("YYYY-MM-DD"),
        ];
    } else {
        // Still trigger Template for safety
        triggerRef(Template);
    }
});

watch(
    [report, dates],
    ([currentReport]) => {
        if (!currentReport) return;

        const { label, name } = currentReport as any;

        reportData.value = {
            ...currentReport,
            name: label ?? name,
        };
    },
    { immediate: true }
);
</script>

<style>
@media print {
    .toolbar-left,
    .toolbar-right,
    #kt_app_header,
    .engage-toolbar,
    #kt_app_sidebar,
    #kt_app_footer,
    #kt_app_toolbar {
        display: none !important;
        padding: 0 !important;
    }
}
</style>
