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
                            <Vendor v-model="data" :errors="errors" />
                            <Payment v-model="data" :errors="errors" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import Reference from "./reference.vue";
import Vendor from "./vendor.vue";
import Payment from "./payment.vue";

import type { Bill } from "~/types/bill";

const data = defineModel<Partial<Bill>>();

interface Props {
    errors: any;
}

const props = defineProps<Props>();
</script>
