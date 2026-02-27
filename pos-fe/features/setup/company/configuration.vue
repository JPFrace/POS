<template>
    <div class="card mb-5 mb-xl-8">
        <div class="card-header border-0">
            <div class="card-toolbar d-flex justify-content-end w-100">
                <button type="button" class="btn btn-sm btn-light-primary" @click="submitForm">
                    <KTIcon icon-name="safe-home" icon-class="fs-3" />
                    Update
                </button>
            </div>
        </div>
        <div class="card-body pt-15">
            <div class="row mb-6">
                <label class="col-lg-4 fw-semibold fs-6">Logo</label>
                <div class="col-lg-8">
                    <Image :path="form?.file?.url" :orig-path="details?.file?.url"
                        @newImageSelected="form.file = $event.file" />
                </div>
            </div>

            <div class="row mb-6" v-for="field in ['name', 'tin_no', 'email', 'address', 'phone']" :key="field">
                <label class="col-lg-4 fw-semibold fs-6">{{ formatLabel(field) }}</label>
                <div class="col-lg-8">
                    <input v-model="form[field]" type="text" :class="[
                        'form-control form-control-lg form-control-solid',
                        touched[field] && errors[field] ? 'is-invalid' : touched[field] ? 'is-valid' : ''
                    ]" :placeholder="`Enter ${formatLabel(field)}`" @blur="validateField(field)" />
                    <div v-if="touched[field] && errors[field]" class="invalid-feedback">
                        {{ errors[field] }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { reactive, watch } from "vue";
import { cloneDeep } from "lodash";
import Image from "~/features/setup/company/image.vue";
import type { Company } from "~/types/company";

const emit = defineEmits<{
    (e: "updated"): void;
}>();

const details = defineModel<Company>();

const form = reactive<Company>(cloneDeep(details.value ?? ({} as Company)));

const { yup } = useYup();
const { t } = useI18n();
const { $message } = useNuxtApp();

const Yup = yup();
const touched = reactive<{ [key: string]: boolean }>({});
const errors = reactive<{ [key: string]: string }>({});

const formatLabel = (str: string) =>
    str
        .split("_")
        .map((s) => s.charAt(0).toUpperCase() + s.slice(1))
        .join(" ");

const touchField = (key: string) => {
    touched[key] = true;
};

const validateField = async (key: string) => {
    touchField(key);
    try {
        await schema.validateAt(key, form);
        errors[key] = "";
    } catch (err: any) {
        errors[key] = err.message;
    }
};

const schema = Yup.object({
    name: Yup.string().required(t("Name is required")),
    tin_no: Yup.string().required(t("TIN No. is required")),
    address: Yup.string().required(t("Address is required")),
    phone: Yup.string().required(t("Phone is required")),
    email: Yup.string().email(t("Invalid email format")).required(t("Email is required")),
    file: Yup.mixed().notRequired(),
});

const validateForm = async () => {
    try {
        await schema.validate(form, { abortEarly: false });
        Object.keys(errors).forEach((key) => delete errors[key]);
    } catch (err: any) {
        if (err.inner) {
            err.inner.forEach((e: any) => {
                if (e.path) errors[e.path] = e.message;
            });
        }
    }
};

const submitForm = async () => {
    Object.keys(form).forEach((key) => (touched[key] = true));
    await validateForm();
    if (Object.keys(errors).length > 0) return;

    try {
        const formData = new FormData();
        Object.entries(form).forEach(([key, value]) => {
            if (key === "file") {
                if (value && value instanceof File) formData.append("file", value);
            } else {
                formData.append(key, String(value ?? ""));
            }
        });

        await useClient(`/api/setup/company/${form.uuid}`, { method: "PUT", body: formData });

        $message("success", t("action.saved"));
        emit("updated");
    } catch (error: any) {
        Object.assign(errors, error?.errors ?? {});
        $message("error", error?.message ?? t("error.failed_request"));
    }
};

watch(
    () => details.value,
    (value) => {
        if (value) Object.assign(form, cloneDeep(value));
    },
    { immediate: true }
);
</script>