<template>
    <div class="flex justify-center items-center p-16 w-full report-container">
        <div
            class="flex flex-col justify-center items-center gap-y-8 bg-white p-4 w-[80%] preview-report"
        >
            <div class="flex flex-col justify-center items-center w-full">
                <h4>ST. SCHOLASTICA'S COLLEGE-WESTGROVE, INC.</h4>
                <p class="m-0 p-0">Ayala Westgrove Heights, Silang, Cavite</p>
                <p class="m-0 p-0">Balance Sheet</p>
                <p class="m-0 p-0 font-bold">
                    For the Period From {{ date1 }} to {{ date2 }}
                </p>
            </div>
            <div class="w-full">
                <table class="w-full">
                    <tbody>
                        <Asset v-model="data" />
                        <LiabilitiesCapital v-model="data" />
                    </tbody>
                </table>
                <div class="flex justify-start items-center gap-x-24 mt-16">
                    <div
                        v-for="s in sortedSignatories"
                        :key="s.uuid"
                        class="flex flex-col gap-y-8"
                    >
                        <span class="italic">{{ s.label }}</span>
                        <div class="flex flex-col gap-y-2">
                            <span class="font-bold uppercase">
                                {{ s.signatory?.name }}
                            </span>
                            <span>
                                {{
                                    s.signatory?.position?.title ??
                                    'Business Office Staff'
                                }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import Asset from './balance-sheet/asset.vue'
import LiabilitiesCapital from './balance-sheet/liabilities-capital.vue'
import moment from 'moment'

const company = ref(import.meta.env.VITE_APP_COMPANY_NAME)

const dates = defineModel()

const key = `${id(useRoute().fullPath)}.balance-sheet`
const client = useSanctumClient()
const { receive, send, dismiss } = usePageEvent()
const user = useSanctumUser()
const reportId = ref()

const date1 = computed(() => {
    const start = new Date()
    start.setTime(start.getTime() - 3600 * 1000 * 24 * 30)

    let date = moment(start)
    if (dates.value != null) {
        date = moment((dates.value as any)[0])
    }

    return date.format('MMMM DD, YYYY')
})

const sortedSignatories = computed(() => {
    const year =
        dates.value && (dates.value as any)[0]
            ? moment((dates.value as any)[0]).year()
            : moment().year()

    return (report.value?.report_signatories ?? [])
        .filter((s) => {
            return s.year_signatory == year
        })
        .slice()
        .sort((a, b) => (a.sort ?? 0) - (b.sort ?? 0))
})

const report = computed(() => data.value?.report ?? {})

const getSignatory = (label: string) => {
    return (
        report.value?.report_signatories?.find((r) => r.label === label)
            ?.signatory ?? {}
    )
}

const date2 = computed(() => {
    const end = new Date()

    let date = moment(end)
    if (dates.value != null) {
        date = moment((dates.value as any)[1])
    }

    return date.format('MMMM DD, YYYY')
})

const { data, refresh, status } = useAsyncData(
    key,
    () =>
        client('/api/reports/balance-sheet', {
            method: 'GET',
            params: {
                date_from: moment((dates.value as any)[0]).format('YYYY-MM-DD'),
                date_to: moment((dates.value as any)[1]).format('YYYY-MM-DD'),
                report_id: reportId.value,
            },
        }),
    {
        server: false,
        lazy: true,
        immediate: false,
    }
)

watch(status, (value) => {
    if (value == 'success') {
        send('report:done')
    }
})

onBeforeUnmount(() => {
    dismiss('report:show')
})

onMounted(() => {
    receive('report:show', (id: string) => {
        reportId.value = id
        refresh()
    })
})
</script>
<style scoped>
@media print {
    .preview-report {
        width: 100%;
    }
    .report-container,
    .preview-report {
        padding: 0 !important;
    }
    @page {
        size: portrait;
    }
}
</style>
