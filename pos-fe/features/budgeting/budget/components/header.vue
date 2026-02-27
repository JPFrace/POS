<template>
    <div class="flex items-end gap-4 -mt-6 w-full">
        <div class="flex flex-col w-80">
            <div class="flex flex-col flex-1">
                <span class="mb-2 font-semibold text-sm">Department</span>
                <Select url="/api/setup/departments" v-model:data="departments" v-model:selected="data!.department"
                    placeholder="All Departments" :mapResult="(result: any) =>
                        result.data.map((row: ResponsibilityCenter) => ({
                            id: row.uuid,
                            value: row.uuid,
                            label: `${row.code} - ${row.name}`,
                        }))
                        " :mapQuery="(search: any) => ({
                            query: {
                                name_code: search,
                            },
                        })
                            " :is-valid="isValid('department')" clearable remote loading>
                </Select>
            </div>
        </div>
        <div class="flex flex-col w-80">
            <div class="flex flex-col flex-1">
                <span class="mb-2 font-semibold text-sm">Fiscal Year</span>
                <Select url="/api/accounting/calendars" column custom-column v-model:data="calendars"
                    v-model:selected="data!.calendar" :mapResult="(result: any) =>
                        result.data.map((row: calendar) => ({
                            id: row.uuid,
                            value: row.uuid,
                            label: `${moment(row.start_date).format('MM/DD/YYYY')} - ${moment(row.end_date).format('MM/DD/YYYY')}`,
                            year: row.year,
                        }))
                        " :mapQuery="(search: any) => ({
                            query: {
                                name_code: search,
                            },
                        })
                            " :is-valid="isValid('calendar')" remote loading>
                    <template #customColumn="{ data }">
                        <CalendarColumn :data="data" />
                    </template>
                </Select>
            </div>
        </div>
        <div class="flex flex-col w-80">
            <div class="flex flex-col flex-1">
                <span class="mb-2 font-semibold text-sm">Type</span>
                <Select url="/api/budgeting/budget-type" v-model:data="types" v-model:selected="data!.type" placeholder="Select Budget Type" :mapResult="(result: any) =>
                    result.data.map((row: BudgetType) => ({
                        id: row.uuid,
                        value: row.uuid,
                        label: row.name,
                    }))
                    " :mapQuery="(search: any) => ({
                        query: {
                            name_code: search,
                        },
                    })
                        " :is-valid="isValid('type')" remote loading clearable>
                </Select>
            </div>
        </div>
        <!-- Save Button -->
        <div class="flex-shrink-0 ml-auto">
            <SaveNew v-model="data" />
        </div>
    </div>
    <div class="flex justify-between items-start gap-x-8 w-full">
        <div class="w-full">
            <ul class="mb-5 nav nav-tabs nav-line-tabs fs-6">
                <li class="nav-item">
                    <a class="uppercase nav-link active" data-bs-toggle="tab" href="#kt_tab_general">General</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane active show" id="kt_tab_general" role="tabpanel">
                    <div class="flex justify-start items-start gap-4">
                        <!-- Name -->
                        <div class="flex flex-col w-80">
                            <Input v-model="data!.name" label="Name" block placeholder="Name"
                                :is-valid="isValid('name')">
                            </Input>
                        </div>
                        <div class="flex flex-col w-165">
                            <Input block label="Description" placeholder="..." v-model="data!.description"
                                :is-valid="isValid('description')"></Input>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import type { Budget, BudgetType } from "~/types/budget";
import SaveNew from "../actions/save-new.vue";
import type { Option } from "~/types/form";
import type { ResponsibilityCenter } from "~/types/responsibility-center";
import type { calendar } from "~/types/calendar";
import CalendarColumn from "./calendar-column.vue";
import moment from "moment";

const departments = ref<Option[]>();
const calendars = ref<Option[]>();
const types = ref<Option[]>();
const data = defineModel<Budget>();
const categories = ref<Option[]>();

interface Props {
    errors: any;
}

const props = defineProps<Props>();

const isValid = (key: string) =>
    props.errors
        ? Object.keys(props.errors).includes(key)
            ? (props.errors as any)[key]?.length <= 0
            : null
        : null;
</script>