<template>
    <div class="card mb-5 mb-xl-8">
        <!--begin::Card header-->
        <div class="card-header border-0">
            <!--begin::Card title-->
            <div class="card-title">
                <h2>Recent Activities</h2>
            </div>
            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <button
                    type="button"
                    class="btn btn-sm btn-flex btn-light-primary"
                    @click="fetchListing"
                >
                    <KTIcon icon-name="abstract-37" icon-class="fs-3" />
                    Refresh
                </button>
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card title-->
        <!--begin::Card body-->
        <div class="card-body pt-15">
            <!--begin::Table wrapper-->
            <div class="table-responsive">
                <!--begin::Table-->
                <table
                    class="table align-middle table-row-dashed fw-semibold text-gray-600 fs-6 gy-5"
                    id="kt_table_customers_logs"
                >
                    <!--begin::Table body-->
                    <tbody>
                        <template v-for="(log, i) in activities" :key="i">
                            <tr>
                                <!--begin::Badge--->
                                <td class="min-w-70px">
                                    <div
                                        :class="`badge badge-light-${EventColor[log.event]}`"
                                    >
                                        {{
                                            log.event.replace(
                                                /(^\w|_\w)/g,
                                                (m) =>
                                                    m
                                                        .replace("_", "")
                                                        .toUpperCase()
                                            )
                                        }}
                                    </div>
                                </td>
                                <!--end::Badge--->

                                <!--begin::Status--->
                                <td>
                                    {{ log.url }}
                                </td>
                                <!--end::Status--->

                                <!--begin::Timestamp--->
                                <td class="pe-0 text-end min-w-200px">
                                    {{
                                        new Date(log.created_at).toLocaleString(
                                            "en-US",
                                            {
                                                month: "numeric",
                                                day: "numeric",
                                                year: "numeric",
                                                hour: "2-digit",
                                                minute: "2-digit",
                                                second: "2-digit",
                                                hour12: true,
                                            }
                                        )
                                    }}
                                </td>
                                <!--end::Timestamp--->
                            </tr>
                        </template>
                    </tbody>
                    <!--end::Table body-->
                </table>
                <!--end::Table-->
            </div>
            <!--end::Table wrapper-->
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
const props = defineProps({
    id: {
        type: String,
        default: "",
    },
});

const EventColor = {
    created: "primary",
    updated: "warning",
    deleted: "danger",
};
const activities = ref([]);
const fetchListing = async () => {
    activities.value = await useClient(
        `/api/security/audit-trails/${props.id}`,
        {
            method: "GET",
        }
    );
    return activities;
};

onMounted(() => {
    fetchListing();
});
</script>
