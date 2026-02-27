<template>
    <div
        :id="id"
        class="bg-body"
        data-kt-drawer="true"
        :data-kt-drawer-name="id"
        data-kt-drawer-activate="true"
        data-kt-drawer-overlay="true"
        data-kt-drawer-direction="end"
        :data-kt-drawer-toggle="'#' + id + '_toggle'"
        :data-kt-drawer-close="'#' + id + '_close'"
        :data-kt-drawer-width="
            JSON.stringify({
                default: width,
                lg: widthLg,
            })
        "
    >
        <!--begin::Card-->
        <div class="card border-0 shadow-none rounded-0 w-100">
            <!--begin::Card header-->
            <div
                :id="id + '_header'"
                class="card-header bgi-position-y-bottom bgi-position-x-end bgi-size-cover bgi-no-repeat rounded-0 border-0 py-4"
                :style="{
                    backgroundImage: `url(
            '~/assets/media/misc/layout/customizer-header-bg.jpg')
          )`,
                }"
            >
                <!--begin::Card title-->
                <h3
                    v-if="!$slots.title"
                    class="card-title fs-3 fw-bold dark:text-white flex-column m-0"
                >
                    {{ title }}
                    <small
                        class="dark:text-white opacity-50 fs-7 fw-semibold pt-1"
                        >{{ description }}</small
                    >
                </h3>
                <slot name="title" />
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <button
                        :id="id + '_close'"
                        type="button"
                        class="btn btn-sm btn-icon dark:btn-color-white p-0 w-20px h-20px rounded-1"
                    >
                        <KTIcon icon-name="abstract-11" icon-class="fs-2" />
                    </button>
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div :id="id + '_body'" class="card-body position-relative">
                <!--begin::Content-->
                <div
                    :id="id + '_content'"
                    class="position-relative scroll-y me-n5 pe-5"
                    data-kt-scroll="true"
                    data-kt-scroll-height="auto"
                    :data-kt-scroll-wrappers="'#' + id + '_body'"
                    :data-kt-scroll-dependencies="
                        '#' + id + '_header,' + '#' + id + '_footer'
                    "
                    data-kt-scroll-offset="5px"
                >
                    <slot />
                </div>
                <!--end::Content-->
            </div>
            <!--end::Card body-->
            <!--begin::Card footer-->
            <div
                :id="id + '_footer'"
                class="card-footer border-0 d-flex gap-3 pb-9 pt-0"
            >
                <slot name="footer" />
                <Button
                    v-if="!$slots.footer && !noCancel"
                    variant="light"
                    class="ms-auto btn btn-light fw-semibold"
                    icon="black-left"
                    @click="emit('cancel')"
                >
                    <span>Cancel</span>
                </Button>
                <Button
                    v-if="!$slots.footer && !noSubmit"
                    variant="primary"
                    class="btn btn-primary fw-semibold"
                    icon="add-folder"
                    :loading="processing"
                    :disabled="processing"
                    @click="emit('submit')"
                >
                    <span>Submit</span>
                </Button>
            </div>
            <!--end::Card footer-->
        </div>
        <!--end::Card-->
    </div>
</template>

<script lang="ts" setup>
interface Props {
    title: string;
    description: string;
    id: string;
    processing: boolean;
    widthLg: string;
    width: string;
    noSubmit: boolean;
    noCancel: boolean;
}

const emit = defineEmits(["submit", "cancel"]);

withDefaults(defineProps<Partial<Props>>(), {
    width: "300px",
    widthLg: "380px",
});
</script>
