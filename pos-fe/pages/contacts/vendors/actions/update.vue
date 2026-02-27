<template>
    <div class="page-actions">
        <NuxtLink :to="`/contacts/vendors/`">
            <Button
                variant="secondary"
                class="btn btn-light ms-auto fw-semibold"
                icon="black-left"
            >
                <span>Back</span>
            </Button>
        </NuxtLink>
        <Button
            variant="primary"
            class="btn btn-primary fw-semibold"
            icon="update-folder"
            :disabled="isUpdating"
            @click="submit"
        >
            <span v-if="!isUpdating">Update</span>
            <span v-else>Updating...</span>
        </Button>
    </div>
</template>

<script lang="ts" setup>
import type { Vendors } from "~/types/vendors";

const route = useRoute();
const uuid = computed(() => route.params.uuid as string | null);
const { validate } = useYup();
const { yup } = useYup();
const { send } = usePageEvent();
const { $swal } = useNuxtApp();
const { t } = useI18n();
const Yup = yup();

const props = defineProps<{
    isIndividual: boolean;
}>();

const emit = defineEmits<{
    "update:success": [];
}>();

const data = defineModel<Partial<Vendors>>();
const isUpdating = ref(false);

const validateForm = () => {
    const form = Yup.object().shape({
        id_no: Yup.string().when("id_no_auto", {
            is: false,
            then: (schema) => schema.required(),
            otherwise: (schema) => schema.notRequired(),
        }),
        first_name: Yup.string().when("$isIndividual", {
            is: true,
            then: (schema) => schema.required(),
            otherwise: (schema) => schema.notRequired(),
        }),
        last_name: Yup.string().when("$isIndividual", {
            is: true,
            then: (schema) => schema.required(),
            otherwise: (schema) => schema.notRequired(),
        }),
        middle_name: Yup.string().notRequired(),
        suffix: Yup.string().notRequired(),
        name: Yup.string().when("$isIndividual", {
            is: false,
            then: (schema) => schema.required(),
            otherwise: (schema) => schema.notRequired(),
        }),
        email: Yup.string().email().notRequired(),
        billing_address: Yup.string().notRequired(),
        country: Yup.object()
            .shape({
                value: Yup.string().notRequired(),
            })
            .notRequired(),
        zip_code: Yup.string().notRequired(),
        contact_number: Yup.string().notRequired(),
        file: Yup.mixed().notRequired(),
        contacts: Yup.array()
            .of(
                Yup.object().shape({
                    name: Yup.string().required(),
                    address: Yup.string().required(),
                    contact_number: Yup.string().required(),
                })
            )
            .max(3),
        sub_type: Yup.object()
            .shape({
                value: Yup.string().required(),
            })
            .required(),
        class: Yup.object()
            .shape({
                value: Yup.string().required(),
            })
            .required(),
        tax: Yup.object()
            .shape({
                value: Yup.string().notRequired(),
            })
            .notRequired(),
    });

    return validate(form, {
        data: data.value,
        context: { isIndividual: props.isIndividual },
    });
};

const submit = async () => {
    if (!data.value || isUpdating.value) return;

    isUpdating.value = true;

    try {
        await validateForm();

        const subTypeLabel = data.value?.sub_type?.label?.toLowerCase();
        const isIndividualType = subTypeLabel === "individual";

        const fieldsToClear = new Set(
            isIndividualType
                ? ["name"]
                : ["first_name", "middle_name", "last_name", "suffix"]
        );

        const formData = new FormData();
        const jsonFields = ["sub_type", "class", "contacts", "tax", "country"];

        Object.entries(data.value).forEach(([key, value]) => {
            if (key === "file") {
                if (value instanceof File) {
                    formData.append(key, value);
                } else if (value === null) {
                    formData.append("file", "");
                }
            } else if (jsonFields.includes(key)) {
                formData.append(key, JSON.stringify(value ?? {}));
            } else {
                const valueToSend = fieldsToClear.has(key)
                    ? ""
                    : String(value ?? "");
                formData.append(key, valueToSend);
            }
        });

        await useClient(`/api/contacts/vendors/${uuid.value}`, {
            method: "PUT",
            body: formData,
        });

        fieldsToClear.forEach((field) => {
            if (data.value) data.value[field as keyof Vendors] = "" as any;
        });

        await $swal("success", {
            title: "Updated",
            text: t("action.updated"),
        });

        emit("update:success");
    } catch (error: any) {
        const errors = error?.errors ?? [];
        send("on:error", errors);

        const messages = Object.values(errors).map((e) => (e as string[])[0]);
        const html =
            "<ol>" + messages.map((m) => `<li>${m}</li>`).join("") + "</ol>";

        $swal("error", {
            title: error.message ?? t("error.failed_request"),
            html,
        });

        throw error;
    } finally {
        isUpdating.value = false;
    }
};
</script>

<style scoped>
/* For update and cancel buttons */
.page-actions {
    display: flex;
    flex-direction: row;
    gap: 1.5rem;
    justify-content: flex-end;
    align-items: center;
    position: absolute;
    top: 5.5rem;
    right: 5.5rem;
    margin: 0;
    z-index: 2;
}
.page-actions .btn {
    width: auto;
    min-width: 120px;
}
</style>
