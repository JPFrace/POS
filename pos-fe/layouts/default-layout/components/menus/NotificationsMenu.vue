<template>
    <!--begin::Menu-->
    <div
        class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-375px app-header-dropdown"
        data-kt-menu="true"
    >
        <!--begin::Heading-->
        <div
            class="d-flex flex-column bgi-no-repeat rounded-top"
            :style="`background-image: url('~/assets/media/misc/menu-header-bg.jpg')`"
        >
            <!--begin::Title-->
            <h3 class="text-white fw-semibold px-9 mt-10 mb-6">
                Notifications
                <span class="fs-8 opacity-75 ps-3">{{ total }}</span>
            </h3>
            <!--end::Title-->

            <!--begin::Tabs-->
            <ul
                class="nav nav-line-tabs nav-line-tabs-2x nav-stretch fw-semibold px-9"
            >
                <li class="nav-item">
                    <a
                        class="nav-link text-white opacity-75 opacity-state-100 pb-4 active"
                        data-bs-toggle="tab"
                        href="#kt_topbar_notifications_1"
                        >Orders</a
                    >
                </li>
            </ul>
            <!--end::Tabs-->
        </div>
        <!--end::Heading-->

        <!--begin::Tab content-->
        <div class="tab-content">
            <!--begin::Tab panel-->
            <div
                id="kt_topbar_notifications_1"
                class="tab-pane fade active show"
                role="tabpanel"
            >
                <!--begin::Items-->
                <div class="scroll-y mh-325px my-5 px-8">
                    <template
                        v-for="notification in notifications"
                        :key="notification.id"
                    >
                        <!--begin::Item-->
                        <div
                            class="flex flex-col items-start justify-start py-4"
                        >
                            <!--begin::Section-->
                            <div class="d-flex align-items-center">
                                <!--begin::Symbol-->
                                <div class="symbol symbol-35px me-4">
                                    <span
                                        :class="`bg-light-primary`"
                                        class="symbol-label"
                                    >
                                        <KTIcon
                                            :icon-name="`technology-2`"
                                            :icon-class="`text-primary`"
                                        />
                                    </span>
                                </div>
                                <!--end::Symbol-->

                                <!--begin::Title-->
                                <div class="mb-0 me-2">
                                    <a
                                        href="#"
                                        class="fs-6 text-gray-800 text-hover-primary fw-bold"
                                        v-html="notification.data.message"
                                    ></a>
                                    <!--begin::Label-->
                                    <span class="badge badge-light fs-8">{{
                                        notification.created_at
                                    }}</span>
                                    <!--end::Label-->
                                </div>
                                <!--end::Title-->
                            </div>
                            <!--end::Section-->
                        </div>
                        <!--end::Item-->
                    </template>
                </div>
                <!--end::Items-->

                <!--begin::View more-->
                <div class="py-3 text-center border-top">
                    <a
                        href="#"
                        class="btn btn-color-gray-600 btn-active-color-primary"
                    >
                        View All
                        <KTIcon icon-name="arrow-right" icon-class="fs-5" />
                    </a>
                </div>
                <!--end::View more-->
            </div>
            <!--end::Tab panel-->
        </div>
        <!--end::Tab content-->
    </div>
    <!--end::Menu-->
</template>

<script lang="ts" setup>
import type { User } from "~/types/user";

const user = useSanctumUser<User>();

const { data, refresh } = useAsyncData(
    "notifications",
    () =>
        useClient(`/api/user/${user.value!.uuid}/notifications`, {
            method: "GET",
        }),
    {
        server: false,
        immediate: false,
    }
);

const notifications = computed(() => data.value?.data ?? []);

const total = computed(() => data.value?.meta?.total ?? 0);

onMounted(() => {
    refresh();
});
</script>
