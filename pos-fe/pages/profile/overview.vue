<template>
    <div>
        <NuxtLayout>
            <template #default="scope">
                <!--begin::Layout-->
                <div class="d-flex flex-column flex-xl-row">
                    <!--begin::Sidebar-->
                    <div
                        class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10"
                    >
                        <!--begin::Card-->
                        <div class="card mb-5 mb-xl-8">
                            <!--begin::Card body-->
                            <div class="card-body pt-15">
                                <!--begin::Summary-->
                                <div
                                    class="d-flex flex-center flex-column mb-5"
                                >
                                    <div
                                        class="symbol symbol-100px symbol-circle mb-7"
                                    >
                                        <img
                                            :src="imageURL ?? previewPhoto"
                                            alt="User Photo"
                                            id="photo"
                                            class="image-input-wrapper w-125px h-125px"
                                        />
                                    </div>
                                    <a
                                        href="#"
                                        class="fs-3 text-gray-800 text-hover-primary fw-bold mb-1"
                                    >
                                        {{ currentUser.name ?? "N/A" }}
                                    </a>
                                    <div
                                        class="fs-5 fw-semibold text-muted mb-6"
                                    >
                                        {{
                                            currentUser.department?.name ??
                                            "N/A"
                                        }}
                                    </div>
                                </div>
                                <div class="d-flex align-items-center my-4">
                                    <hr class="flex-grow-1" />
                                    <span class="mx-3 text-muted"
                                        ><span class="fw-bold"
                                            >Details</span
                                        ></span
                                    >
                                    <hr class="flex-grow-1" />
                                </div>
                                <!--begin::Details content-->
                                <div>
                                    <div class="py-5 fs-6">
                                        <!--begin::Badge-->
                                        <div
                                            class="badge badge-light-info d-inline"
                                        >
                                            {{ currentUser.role ?? "N/A" }}
                                        </div>
                                        <!--begin::Badge-->
                                        <!--begin::Details item-->
                                        <div class="fw-bold mt-5">Email</div>
                                        <div class="text-gray-600">
                                            <a
                                                href="#"
                                                class="text-gray-600 text-hover-primary"
                                                >{{
                                                    currentUser.email ?? "N/A"
                                                }}</a
                                            >
                                        </div>
                                        <div class="fw-bold mt-5">Address</div>
                                        <div class="text-gray-600">
                                            {{ currentUser.address ?? "N/A" }}
                                        </div>
                                        <div class="fw-bold mt-5">Contacts</div>
                                        <div class="text-gray-600">
                                            {{ currentUser.contacts ?? "N/A" }}
                                        </div>
                                        <div class="fw-bold mt-5">
                                            Department
                                        </div>
                                        <div class="text-gray-600">
                                            {{
                                                currentUser.department?.name ??
                                                "N/A"
                                            }}
                                        </div>
                                        <div class="fw-bold mt-5">Position</div>
                                        <div class="text-gray-600">
                                            {{
                                                currentUser.position?.title ??
                                                "N/A"
                                            }}
                                        </div>
                                        <!--begin::Details item-->
                                    </div>
                                </div>
                                <!--end::Details content-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Sidebar-->

                    <!--begin::Content-->
                    <div class="flex-lg-row-fluid ms-lg-15">
                        <!--begin:::Tabs-->
                        <ul
                            class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8"
                        >
                            <!--begin:::Tab item-->
                            <li class="nav-item">
                                <a
                                    class="nav-link text-active-primary pb-4 active"
                                    data-bs-toggle="tab"
                                    href="#kt_customer_view_overview_tab"
                                    >Personal Info</a
                                >
                            </li>
                            <!--end:::Tab item-->

                            <!--begin:::Tab item-->
                            <li class="nav-item">
                                <a
                                    class="nav-link text-active-primary pb-4"
                                    data-bs-toggle="tab"
                                    href="#kt_customer_view_overview_events_and_logs_tab"
                                    >Activities</a
                                >
                            </li>
                            <!--end:::Tab item-->

                            <!--begin:::Tab item-->
                            <li class="nav-item">
                                <a
                                    class="nav-link text-active-primary pb-4"
                                    data-bs-toggle="tab"
                                    href="#kt_customer_view_overview_tab_reset_password"
                                    >Reset Password</a
                                >
                            </li>
                            <!--end:::Tab item-->
                        </ul>
                        <!--end:::Tabs-->

                        <!--begin:::Tab content-->
                        <div class="tab-content" id="myTabContent">
                            <!--begin:::Tab pane-->
                            <div
                                class="tab-pane fade show active"
                                id="kt_customer_view_overview_tab"
                                role="tabpanel"
                            >
                                <Form
                                    v-model:data="form"
                                    v-model:details="details"
                                />
                            </div>
                            <!--end:::Tab pane-->

                            <!--begin:::Tab pane-->
                            <div
                                class="tab-pane fade"
                                id="kt_customer_view_overview_events_and_logs_tab"
                                role="tabpanel"
                            >
                                <Activity :id="currentUser.uuid" />
                            </div>
                            <!--end:::Tab pane-->

                            <!--begin:::Tab pane-->
                            <div
                                class="tab-pane fade"
                                id="kt_customer_view_overview_tab_reset_password"
                                role="tabpanel"
                            >
                                <ResetPassword />
                            </div>
                            <!--end:::Tab pane-->
                        </div>
                        <!--end:::Tab content-->
                    </div>
                    <!--end::Content-->
                </div>
                <!--end::Layout-->
            </template>
        </NuxtLayout>
    </div>
</template>

<script lang="ts" setup>
import type { userProfile } from "~/types/user-profile";
import type { userPosition } from "~/types/user-position";
import Activity from "./activity.vue";
import Form from "./form.vue";
import { ref, onMounted, watch } from "vue";
import ResetPassword from "./reset-password.vue";
import type { ResponsibilityCenter } from "~/types/responsibility-center";
import { method } from "lodash";

var sanctumAuth = useSanctumAuth();
var currentUser = sanctumAuth.user.value as userProfile;
const previewPhoto = ref("\\media\\avatars\\blank.png");
const imageURL = ref(null);
const { receive } = usePageEvent();

const details = ref({
    email: "",
    department: {} as ResponsibilityCenter,
    position: {} as userPosition,
});
const form = ref({
    uuid: "",
    photo: null,
    name: "",
    address: "",
    contacts: "",
});

const setForm = (value: any) => {
    form.value = {
        uuid: value.uuid || "",
        photo: value.photo,
        name: value.name || "",
        address: value.address || "",
        contacts: value.contacts || "",
    };
    details.value = {
        email: value.email || "",
        department: value.department?.name || "",
        position: value.position || ({} as userPosition),
    };
    imageURL.value = value.photo;
};

onMounted(() => {
    setForm(currentUser);

    receive("user:update", async () => {
        currentUser = (await useClient("/api/user", {
            method: "GET",
        })) as userProfile;
        setForm(currentUser);
    });
});
</script>
