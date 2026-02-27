<template>
    <div class="card mb-5 mb-xl-8">
        <div class="card-header border-0 pt-5">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bolder fs-3 mb-1">
                    Reset Password
                </span>
            </h3>
        </div>
        <div class="card-body pt-15">
            <div class="row mb-6">
                <!--begin::Label-->
                <label
                    class="col-sm-3 col-form-label fw-semibold fs-6 text-right"
                >
                    Current Password
                </label>
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8">
                    <input
                        v-model="form.currentPassword"
                        type="password"
                        class="form-control form-control-lg form-control-solid"
                        placeholder="Enter Current Password"
                    />
                </div>
                <!--end::Col-->
            </div>

            <div class="row mb-6">
                <!--begin::Label-->
                <label
                    class="col-sm-3 col-form-label fw-semibold fs-6 text-right"
                >
                    New Password
                </label>
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8">
                    <input
                        v-model="form.password"
                        type="password"
                        class="form-control form-control-lg form-control-solid"
                        placeholder="Enter New Password"
                    />
                </div>
                <!--end::Col-->
            </div>

            <div class="row mb-6">
                <!--begin::Label-->
                <label
                    class="col-sm-3 col-form-label fw-semibold fs-6 text-right"
                >
                    Re-Enter New Password
                </label>
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8">
                    <input
                        v-model="form.confirmPassword"
                        type="password"
                        class="form-control form-control-lg form-control-solid"
                        placeholder="Re-Enter New Password"
                    />
                </div>
                <!--end::Col-->
            </div>

            <div class="flex justify-content-center">
                <button
                    type="button"
                    class="btn btn-sm btn-flex btn-danger"
                    @click="submit"
                >
                    Reset
                </button>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
const { yup, validate } = useYup();
const { t } = useI18n();
const { $swal } = useNuxtApp();
const { send } = usePageEvent();
const Yup = yup();

const form = ref({
    currentPassword: "",
    password: "",
    confirmPassword: "",
});

const isLoading = ref(false);

function validateForm() {
    const valForm = Yup.object().shape({
        currentPassword: Yup.string().required(),
        password: Yup.string().min(4).required(),
        confirmPassword: Yup.string().min(4).required(),
    });

    return validate(valForm, {
        data: form.value,
    });
}

async function submit() {
    const formData = new FormData();
    formData.append("currentPassword", form.value.currentPassword);
    formData.append("password_confirmation", form.value.confirmPassword);
    formData.append("password", form.value.password);
    isLoading.value = true;
    try {
        await validateForm();
        // Call your API here
        // await $fetch('/api/reset-password', { method: 'POST', body: form.value })
        await useClient("/api/security/users/change-password-profile", {
            method: "PATCH",
            body: formData,
        });

        await $swal("success", {
            title: "Password Changed!",
            text: "",
        });
    } catch (error: any) {
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
    } finally {
        isLoading.value = false;
    }
}
</script>
