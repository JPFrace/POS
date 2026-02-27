<template>
    <form class="space-y-4 py-1 px-1">
        <div class="grid grid-cols-2 gap-4">
            <div class="flex-1 flex flex-col">
                <span class="text-md font-semibold mb-2">Year</span>
                <Input v-model="form.year" placeholder="e.g. 2025-2026" :is-valid="isValid('year')" />
            </div>
            <div class="col-md-4" hidden>
                <label for="no_of_periods">No. Of Periods</label>
                <Input v-model="form.no_of_periods" label="No of Periods" placeholder="No of Periods"
                    :is-valid="isValid('no_of_periods')" readonly />
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="start_date" class="mb-2 block">Start Date</label>
                <el-date-picker v-model="form.start_date" type="date" placeholder="Pick start date"
                    :is-valid="isValid('start_date')" format="MMMM DD, YYYY" value-format="YYYY-MM-DD"
                    :clearable="false" @change="
                        generateDates(
                            form.start_date ? new Date(form.start_date) : null
                        )
                        " />
            </div>
            <div>
                <label for="end_date" class="mb-2 block">End Date</label>
                <el-date-picker v-model="form.end_date" type="date" placeholder="Pick end date"
                    :is-valid="isValid('end_date')" format="MMMM DD, YYYY" value-format="YYYY-MM-DD" readonly />
            </div>
        </div>
        <hr class="border-t border-dashed border-gray-400 my-4" />
        <div class="grid grid-cols-2 gap-4">
            <div v-for="i in 12" :key="i">
                <!-- Label + Lock -->
                <div class="d-flex justify-content-between align-items-center">
                    <label :for="`period_${i}`" class="form-label mb-0">
                        Period {{ i }}
                    </label>

                    <el-checkbox v-model="form[`period_${i}_closed`]" :is-valid="isValid(`period_${i}_closed`)">
                        Locked
                    </el-checkbox>
                </div>

                <!-- Date Picker -->
                <el-date-picker v-model="form[`period_${i}`]" type="date" placeholder="auto generated"
                    :is-valid="isValid(`period_${i}`)" format="MMMM DD, YYYY" value-format="YYYY-MM-DD" readonly
                    style="width: 100%" />
            </div>
        </div>
        <hr class="border-t border-dashed border-gray-400 my-4" />
        <Checkbox v-model="form.is_inactive" label="Inactive" :is-valid="isValid('is_inactive')" size="sm" />
    </form>
</template>

<script lang="ts" setup>
const { yup } = useYup();
import type { calendar } from "~/types/calendar";

function generateDates(startDate: Date) {

    if (!startDate) return;

    const baseDate = new Date(startDate);

    for (let i = 0; i < 12; i++) {
        let newDate = new Date(baseDate);
        newDate.setMonth(baseDate.getMonth() + i);
        newDate = getEndOfMonth(newDate);
        // Update the form with the new date
        (form as any).value[`period_${i + 1}`] = newDate
            .toISOString()
            .split("T")[0];
    }

    form.value.end_date = form.value[`period_12`];
}

function getEndOfMonth(date: Date | string): Date {
    const d = new Date(date);
    return new Date(d.getFullYear(), d.getMonth() + 1, 1);
}

interface Props {
    errors?: object;
    schema: Function;
    form: Function;
    data?: calendar;
}

const props = defineProps<Props>();

const form = ref<Partial<calendar>>({
    year: "",
    no_of_periods: 12,
    start_date: null,
    end_date: null,
    period_1: null,
    period_1_closed: false,
    period_2: null,
    period_2_closed: false,
    period_3: null,
    period_3_closed: false,
    period_4: null,
    period_4_closed: false,
    period_5: null,
    period_5_closed: false,
    period_6: null,
    period_6_closed: false,
    period_7: null,
    period_7_closed: false,
    period_8: null,
    period_8_closed: false,
    period_9: null,
    period_9_closed: false,
    period_10: null,
    period_10_closed: false,
    period_11: null,
    period_11_closed: false,
    period_12: null,
    period_12_closed: false,
    is_inactive: false,
    created_at: new Date().toISOString().split("T")[0],
});

const Yup = yup();

const isValid = (key: string) =>
    props.errors
        ? Object.keys(props.errors).includes(key)
            ? props?.errors[key]?.length <= 0
            : null
        : null;

const setForm = (value: any) => {
    form.value = {
        year: value.year ?? 2025,
        no_of_periods: 12,
        start_date: value.start_date ?? null,
        end_date: value.end_date ?? null,
        period_1: value.period_1 ?? null,
        period_1_closed: value.period_1_closed ?? false,
        period_2: value.period_2 ?? null,
        period_2_closed: value.period_2_closed ?? false,
        period_3: value.period_3 ?? null,
        period_3_closed: value.period_3_closed ?? false,
        period_4: value.period_4 ?? null,
        period_4_closed: value.period_4_closed ?? false,
        period_5: value.period_5 ?? null,
        period_5_closed: value.period_5_closed ?? false,
        period_6: value.period_6 ?? null,
        period_6_closed: value.period_6_closed ?? false,
        period_7: value.period_7 ?? null,
        period_7_closed: value.period_7_closed ?? false,
        period_8: value.period_8 ?? null,
        period_8_closed: value.period_8_closed ?? false,
        period_9: value.period_9 ?? null,
        period_9_closed: value.period_9_closed ?? false,
        period_10: value.period_10 ?? null,
        period_10_closed: value.period_10_closed ?? false,
        period_11: value.period_11 ?? null,
        period_11_closed: value.period_11_closed ?? false,
        period_12: value.period_12 ?? null,
        period_12_closed: value.period_12_closed ?? false,
        created_at: value.created_at ?? new Date().toISOString().split("T")[0],
        is_inactive: value.is_inactive ?? false,
    };
};

watch(
    form,
    (value) => {
        props.form(value);
    },
    {
        deep: true,
    }
);

onMounted(() => {
    if (props.data) {
        setForm(props.data);
    }

    props.schema(
        Yup.object().shape({
            year: Yup.string().required(),
            no_of_periods: Yup.number().required(),
            start_date: Yup.string().required(),
            end_date: Yup.string().required(),
            period_1: Yup.string().required(),
            period_1_closed: Yup.boolean().notRequired().nullable(),
            period_2: Yup.string().required(),
            period_2_closed: Yup.boolean().notRequired().nullable(),
            period_3: Yup.string().required(),
            period_3_closed: Yup.boolean().notRequired().nullable(),
            period_4: Yup.string().required(),
            period_4_closed: Yup.boolean().notRequired().nullable(),
            period_5: Yup.string().required().nullable(),
            period_5_closed: Yup.boolean().notRequired().nullable(),
            period_6: Yup.string().required().nullable(),
            period_6_closed: Yup.boolean().notRequired().nullable(),
            period_7: Yup.string().required().nullable(),
            period_7_closed: Yup.boolean().notRequired().nullable(),
            period_8: Yup.string().required().nullable(),
            period_8_closed: Yup.boolean().notRequired().nullable(),
            period_9: Yup.string().required().nullable(),
            period_9_closed: Yup.boolean().notRequired().nullable(),
            period_10: Yup.string().required().nullable(),
            period_10_closed: Yup.boolean().notRequired().nullable(),
            period_11: Yup.string().required().nullable(),
            period_11_closed: Yup.boolean().notRequired().nullable(),
            period_12: Yup.string().required().nullable(),
            period_12_closed: Yup.boolean().notRequired().nullable(),
            created_at: Yup.string().required(),
            is_inactive: Yup.boolean().notRequired().nullable(),
        })
    );
});
</script>
