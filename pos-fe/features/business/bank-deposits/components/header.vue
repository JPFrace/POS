<template>
    <div class="flex flex-col gap-y-8">
        <div class="flex justify-between items-center">
            <Reference v-model="data" :errors="errors" />
        </div>
        <div class="flex justify-between items-start gap-x-8 w-full">
            <div class="w-full">
                <ul class="mb-5 nav nav-tabs nav-line-tabs fs-6">
                    <li class="nav-item">
                        <a
                            class="uppercase nav-link active"
                            data-bs-toggle="tab"
                            href="#kt_tab_general"
                            >General</a
                        >
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div
                        class="tab-pane active show"
                        id="kt_tab_general"
                        role="tabpanel"
                    >
                        <div class="flex justify-start items-start gap-x-8">
                            <General
                                v-model="data"
                                v-model:cash_in_banks="cash_in_banks"
                                :errors="errors"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import Reference from "./reference.vue";
import General from "./general.vue";
import type { Payment } from "~/types/payment";
import type { ChartAccount } from "~/types/chart-account";

const data = defineModel<Partial<Payment>>();
const cash_in_banks = defineModel<ChartAccount[]>("cash_in_banks");

interface Props {
    errors: any;
}

const props = defineProps<Props>();
</script>
