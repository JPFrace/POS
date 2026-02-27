<template>
    <div class="card mb-5 mb-xl-8">
        <!--begin::Card header-->
        <div class="card-header border-0">
            <!--begin::Card title-->
            <div class="card-title">
                <h2>User Profile</h2>
            </div>
            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Filter-->
                <button
                    type="button"
                    class="btn btn-sm btn-flex btn-light-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#kt_modal_add_payment"
                    @click="submit"
                >
                    <KTIcon icon-name="user-tick" icon-class="fs-3" />
                    Update Profile
                </button>
                <!--end::Filter-->
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card title-->
        <div class="card-body pt-15">
            <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label fw-semibold fs-6"
                    >Avatar</label
                >
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8">
                    <!--begin::Image input-->
                    <InputImage v-model="form.photo" />
                    <!--end::Image input-->

                    <!--begin::Hint-->
                    <div class="form-text">
                        Allowed file types: png, jpg, jpeg.
                    </div>
                    <!--end::Hint-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->

            <!--begin::Row-->
            <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label fw-semibold fs-6"
                    >Name</label
                >
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8">
                    <Input
                        v-model="form.name"
                        placeholder="Enter name here..."
                        :is-valid="isValid('name')"
                    />
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->

            <!--begin::Row-->
            <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label fw-semibold fs-6"
                    >Address</label
                >
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8">
                    <Input
                        v-model="form.address"
                        placeholder="Enter address here..."
                        :is-valid="isValid('address')"
                    />
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
            <!--begin::Row-->
            <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label fw-semibold fs-6"
                    >Contacts</label
                >
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8">
                    <Input
                        v-model="form.contacts"
                        placeholder="Enter Your Contact here..."
                        :is-valid="isValid('contacts')"
                    />
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->

            <hr class="flex-grow-1 mb-6" />

            <!--begin::Row-->
            <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label fw-semibold fs-6"
                    >Email</label
                >
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8 flex items-center">
                    <span class="text-m font-bold mb-2 text-left">
                        {{ details?.email }}
                    </span>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->

            <!--begin::Row-->
            <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label fw-semibold fs-6"
                    >Department</label
                >
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8 flex items-center">
                    <span class="text-m font-bold mb-2 text-left">
                        {{ details?.department?.name ?? "NONE" }}
                    </span>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
            <!--begin::Row-->
            <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label fw-semibold fs-6"
                    >Position</label
                >
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8 flex items-center">
                    <span class="text-m font-bold mb-2 text-left">
                        {{ details?.position?.title ?? "NONE" }}
                    </span>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
        </div>
    </div>
</template>

<script lang="ts" setup>
import type { userProfile } from "~/types/user-profile";
import type { ResponsibilityCenter } from "~/types/responsibility-center";
import { ref } from "vue";
import type { Throwable } from "~/types/common";
import type { userPosition } from "~/types/user-position";

const { t } = useI18n();
const { $swal } = useNuxtApp();
const { send } = usePageEvent();

const form = defineModel<Partial<userProfile>>("data", { default: {} });
const details = defineModel("details", {
    default: {
        email: "",
        position: {} as userPosition,
        department: {} as ResponsibilityCenter,
    },
});
const processing = ref(false);
const errors = ref([]);
const imageUrl = ref<string | null>(null);

const isValid = (key: string) =>
    errors.value
        ? Object.keys(errors.value).includes(key)
            ? errors.value[key]?.length <= 0
            : null
        : null;

const props = defineProps({
    id: {
        type: String,
        default: "",
    },
});

const previewPhoto = ref("/media/avatars/blank.png");

const { yup, validate } = useYup();
const Yup = yup();

const validateForm = () => {
    const val = Yup.object().shape({
        name: Yup.string().required(),
        address: Yup.string().notRequired(),
        contacts: Yup.string().notRequired(),
    });

    return validate(val, {
        data: form.value ?? {},
    });
};

const submit = async () => {
    const formData = new FormData();
    const formValue = (form.value ?? {}) as Record<string, any>;
    const keys = Object.keys(formValue);

    for (const key of keys) {
        formData.append(key, formValue[key] ?? "");
    }

    try {
        await validateForm();

        await useClient(
            `/api/security/users/update-profile/${form.value.uuid}`,
            {
                method: "PUT",
                body: formData,
            }
        );

        await $swal("success", {
            title: "Profile Updated",
            text: "",
        });

        send("user:update");
    } catch (error: Throwable) {
        const errors = error?.errors ?? [];

        send("on:error", errors);

        var messages = [];

        for (var e of Object.values(errors)) {
            messages.push((e as string[])[0]);
        }

        var html = "<ol>";
        for (var m of messages) {
            html += `<li>${m}</li>`;
        }

        html += "</ol>";

        $swal("error", {
            title: error.message ?? t("error.failed_request"),
            html,
        });
    }
};
</script>
