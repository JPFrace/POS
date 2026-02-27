<template>
    <div>
        <NuxtLayout name="auth">
            <!--begin::Wrapper-->
            <div class="auth-form-wrapper w-full max-w-[380px] mx-auto py-12">
                <!--begin::Form-->
                <VForm
                    id="kt_login_signin_form"
                    class="form w-100"
                    :validation-schema="login"
                    :initial-values="{
                        email: '',
                        password: '',
                    }"
                    @submit="onSubmitLogin"
                >
                    <!--begin::Heading-->
                    <div class="text-center mb-10">
                        <!--begin::Title-->
                        <p
                            class="text-[#080809] font-urbanist fw-bold fs-4 fs-sm-3 fs-md-2 fs-lg-1 mb-6"
                        >
                            Sign In
                        </p>
                        <!--end::Title-->
                    </div>
                    <!--begin:Policy-->
                    <p
                        class="text-[#A1A1A1] font-urbanist fw-medium fs-6 mb-6 text-center leading-relaxed"
                    >
                        By tapping Sign In, you agree to our Terms. Learn how we
                        process your data in our
                        <a
                            href="#"
                            class="!text-[#3E7DDC] fw-semibold hover:underline"
                        >
                            Privacy Policy
                        </a>
                        and
                        <a
                            href="#"
                            class="!text-[#3E7DDC] fw-semibold hover:underline"
                        >
                            Cookies Policy </a
                        >.
                    </p>
                    <!--end:Policy-->
                    <!--begin::Input group-->
                    <div class="fv-row mb-6">
                        <!--begin::Label-->
                        <label
                            class="form-label fs-5 text-gray-900 font-urbanist font-bold"
                            >Email</label
                        >
                        <!--end::Label-->

                        <!--begin::Input-->
                        <Field
                            tabindex="1"
                            name="email"
                            type="text"
                            autocomplete="off"
                            placeholder="Enter your User name"
                            class="auth-input"
                        />
                        <!--end::Input-->
                        <div class="fv-help-block min-h-[20px] mt-1">
                            <ErrorMessage name="email" />
                        </div>
                    </div>
                    <!--end::Input group-->

                    <!--begin:: Password Input group-->
                    <div class="fv-row mb-6">
                        <!--begin::Wrapper-->
                        <div class="d-flex flex-stack mb-2">
                            <!--begin::Label-->
                            <label class="form-label fs-5 fw-bold text-gray-900"
                                >Password</label
                            >
                            <!--end::Label-->
                        </div>
                        <!-- end::Wrapper -->

                        <!--begin::Password Field-->
                        <div class="password-wrapper">
                            <Field
                                :type="showPassword ? 'text' : 'password'"
                                name="password"
                                placeholder="Enter your Password"
                                class="auth-input pr-12"
                            />

                            <span
                                class="password-toggle"
                                @click="showPassword = !showPassword"
                            >
                                <svg
                                    v-if="!showPassword"
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5
         c4.477 0 8.268 2.943 9.542 7
         -1.274 4.057-5.065 7-9.542 7
         -4.477 0-8.268-2.943-9.542-7z"
                                    />
                                </svg>

                                <svg
                                    v-else
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19
         c-4.477 0-8.268-2.943-9.542-7
         a9.956 9.956 0 012.042-3.362M6.7 6.7l10.6 10.6"
                                    />
                                </svg>
                            </span>
                        </div>
                        <!--start::Password Error Message-->
                        <div class="fv-help-block min-h-[20px] mt-1">
                            <ErrorMessage name="password" />
                        </div>
                        <!--end::Password Error Message-->
                        <!-- Remember & Forgot -->
                        <div
                            class="flex items-center justify-between mt-6 mb-8"
                        >
                            <label
                                class="flex items-center gap-3 cursor-pointer select-none"
                            >
                                <input
                                    type="checkbox"
                                    v-model="remember"
                                    class="remember-checkbox"
                                />
                                <span class="remember-text"> Remember me </span>
                            </label>

                            <NuxtLink
                                to="/password-reset"
                                class="text-sm font-semibold text-[#3E7DDC] hover:underline"
                            >
                                Forgot Password?
                            </NuxtLink>
                        </div>
                        <!--end::Input-->
                    </div>
                    <!--end:: Password Input group-->
                    <!--end::Input group-->

                    <!--begin::Actions-->
                    <div class="text-center">
                        <!--begin::Submit button-->
                        <button
                            id="kt_sign_in_submit"
                            ref="submitButton"
                            tabindex="3"
                            type="submit"
                            class="w-full py-3 rounded-full bg-[#0C1E3C] text-white font-semibold text-base transition-all duration-200 hover:opacity-90 mb-6"
                        >
                            <span class="indicator-label">Sign In </span>

                            <span class="indicator-progress">
                                Please wait...
                                <span
                                    class="spinner-border spinner-border-sm align-middle ms-2"
                                />
                            </span>
                        </button>
                        <!--end::Submit button-->

                        <!--begin::Google link-->
                        <a
                            :href="`${googleRef}/auth/google/redirect`"
                            class="w-full py-3 rounded-full border border-[#D1D5DB] bg-white text-[#111827] font-medium text-base flex items-center justify-center gap-3 transition-all duration-200 hover:bg-gray-50"
                        >
                            <img
                                alt="Logo"
                                src="/media/svg/brand-logos/google-icon.svg"
                                class="w-5 h-5"
                            />
                            Continue with your google email.
                        </a>
                        <!--end::Google link-->
                    </div>
                    <!--end::Actions-->
                </VForm>
                <!--end::Form-->
            </div>
            <!--end::Wrapper-->
        </NuxtLayout>
    </div>
</template>
<script lang="ts" setup>
import { ref } from "vue";
import { ErrorMessage, Field, Form as VForm } from "vee-validate";
import { type User } from "@/stores/auth";
import Swal from "sweetalert2/dist/sweetalert2.js";
import * as Yup from "yup";

const remember = ref(false);
const showPassword = ref(false);

const { login: loginAs } = useSanctumAuth();
const checkout = useCheckoutStore();
const submitButton = ref<HTMLButtonElement | null>(null);

const googleRef = ref(import.meta.env.VITE_APP_PROXY_BASE_URI);

//Create form validation object
const login = Yup.object().shape({
    email: Yup.string().email().required().label("Email"),
    password: Yup.string().min(4).required().label("Password"),
});

//Form submit function
const onSubmitLogin = async (values: any) => {
    values = values as User;

    if (submitButton.value) {
        submitButton.value!.disabled = true;
        // Activate indicator
        submitButton.value.setAttribute("data-kt-indicator", "on");
    }

    try {
        await loginAs(values);
        Swal.fire({
            text: "You have successfully logged in!",
            icon: "success",
            buttonsStyling: false,
            confirmButtonText: "Ok, proceed!",
            heightAuto: false,
            customClass: {
                confirmButton: "btn fw-semibold btn-light-primary",
            },
        }).then(() => {
            checkout.$reset();
            // Go to page after successfully login
            navigateTo("/dashboard");
        });
    } catch (errors: any) {
        Swal.fire({
            text: errors.data?.message ?? "Invalid Credentials",
            icon: "error",
            buttonsStyling: false,
            confirmButtonText: "Try again!",
            heightAuto: false,
            customClass: {
                confirmButton: "btn fw-semibold btn-light-danger",
            },
        });
    }

    //Deactivate indicator
    submitButton.value?.removeAttribute("data-kt-indicator");

    submitButton.value!.disabled = false;
};
</script>
<style scoped lang="scss">
.auth-form-wrapper .auth-input {
    width: 100%;
    padding: 1rem 1.5rem;
    font-size: 1rem;
    border-radius: 9999px;
    border: 1px solid #2f3a44;
    background: transparent;
    transition: all 0.2s ease;
}

.auth-form-wrapper .auth-input:focus {
    outline: none;
    border-color: #3e7ddc;
    box-shadow: 0 0 0 3px rgba(62, 125, 220, 0.15);
}
.auth-form-wrapper button[type="submit"] {
    border-radius: 9999px;
}
//For Error Messages
.fv-help-block {
    font-size: 0.85rem;
    color: #ef4444;
    margin-top: 4px;
}
.password-wrapper {
    position: relative;
}

.password-toggle {
    position: absolute;
    top: 50%;
    right: 1rem;
    transform: translateY(-50%);
    cursor: pointer;
    color: #6b7280;
    transition: 0.2s ease;
}

.password-toggle:hover {
    color: #3e7ddc;
}
.remember-checkbox {
    width: 18px;
    height: 18px;
    border-radius: 0;
    border: 1px solid #2f3a44;
    appearance: none;
    cursor: pointer;
    position: relative;
    transform: translateY(4px);
}

.remember-checkbox:checked {
    background-color: #3e7ddc;
    border-color: #3e7ddc;
}

.remember-checkbox:checked::after {
    content: "";
    position: absolute;
    left: 5px;
    top: 2px;
    width: 5px;
    height: 10px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}
.remember-text {
    font-size: 0.875rem; /* same as text-sm */
    line-height: 1;
    color: #374151;
    font-weight: 500;
}

.remember-checkbox {
    width: 18px;
    height: 18px;
    border-radius: 0;
    border: 1px solid #2f3a44;
    appearance: none;
    cursor: pointer;
    position: relative;
    transition: all 0.2s ease;
    margin: 0; /* important */
    display: inline-block;
}
</style>
