<template>
    <form class="space-y-4 py-1 px-1">
        <div>
            <PersonalInfo
                v-model:selectedSubType="form.sub_type"
                v-model:selectedClass="form.class"
                v-model:selectedTax="form.tax"
                :form="form"
                :is-valid="isValid"
                :is-individual="isIndividual"
                :subTypes="subTypes"
                :classes="classes"
                :taxes="taxes"
                @update:subTypes="(v) => (subTypes = v)"
                @update:classes="(v) => (classes = v)"
                @update:taxes="(v) => (taxes = v)"
            />
        </div>
    </form>
    <div class="mb-5 mt-8">
        <div class="d-grid">
            <ul
                :id="props.data?.uuid + '_myTabContent'"
                class="nav nav-tabs flex-nowrap text-nowrap"
                role="tablist"
            >
                <li class="nav-item" role="presentation">
                    <button
                        :id="props.data?.uuid + '_kt_tab_1'"
                        ref="billingTab"
                        class="nav-link active btn btn-active-light btn-color-gray-600 btn-active-color-primary rounded-bottom-0"
                        data-bs-toggle="tab"
                        :data-bs-target="
                            '#' + props.data?.uuid + '_kt_tab_pane_1'
                        "
                        type="button"
                        role="tab"
                        :aria-controls="props.data?.uuid + '_kt_tab_pane_1'"
                        aria-selected="true"
                        style="font-size: 1rem"
                    >
                        <i class="ki-solid ki-geolocation me-1 fs-3" />
                        BILLING ADDRESS
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button
                        :id="props.data?.uuid + '_kt_tab_2'"
                        class="nav-link btn btn-active-light btn-color-gray-600 btn-active-color-primary rounded-bottom-0"
                        data-bs-toggle="tab"
                        :data-bs-target="
                            '#' + props.data?.uuid + '_kt_tab_pane_2'
                        "
                        type="button"
                        role="tab"
                        :aria-controls="props.data?.uuid + '_kt_tab_pane_2'"
                        aria-selected="false"
                        style="font-size: 1rem"
                    >
                        <i class="ki-solid ki-address-book me-1 fs-3" />
                        CONTACTS
                    </button>
                </li>
            </ul>
            <BillingTab
                v-model:selectedCountry="form.country"
                :form="form"
                :is-valid="isValid"
                :countries="countries"
                @update:countries="(v) => (countries = v)"
            />
            <ContactsTab v-model:form="form" :is-valid="isValid" />
        </div>
    </div>
</template>

<script lang="ts" setup>
import type { Customers } from "~/types/customers";
import type { Option } from "~/types/form";
import PersonalInfo from "./components/personal-info.vue";
import BillingTab from "./components/billing.vue";
import ContactsTab from "./components/contacts.vue";

const { yup } = useYup();
const Yup = yup();
const { receive } = usePageEvent();

const props = defineProps<Props>();

interface Props {
    errors?: object;
    schema: Function;
    form: Function;
    data?: Customers;
}

const defaultForm: Partial<Customers> = {
    id_no: "",
    id_no_auto: true,
    first_name: "",
    last_name: "",
    middle_name: "",
    suffix: "",
    email: "",
    billing_address: "",
    contact_number: "",
    country: null,
    zip_code: "",
    contacts: [],
    name: "",
    tax: null,
    sub_type: {
        id: 1,
        value: 1,
        label: "Individual",
    },
    class: null,
};

const form = ref<Partial<Customers>>({ ...defaultForm });

const subTypes = ref<Option[] | null>(null);
const classes = ref<Option[] | null>(null);
const taxes = ref<Option[] | null>(null);
const countries = ref<Option[] | null>(null);

const billingTab = ref<HTMLButtonElement | null>(null);

const isIndividual = computed(() => {
    const subType = form.value?.sub_type;
    const text = String(subType?.label ?? "");
    return text.toLowerCase() === "individual";
});

// Computed property to filter form data based on current subtype
const filteredFormData = computed(() => {
    const baseData = {
        uuid: form.value.uuid,
        id_no: form.value.id_no,
        id_no_auto: form.value.id_no_auto,
        email: form.value.email,
        billing_address: form.value.billing_address,
        contact_number: form.value.contact_number,
        country: form.value.country,
        zip_code: form.value.zip_code,
        contacts: form.value.contacts,
        tax: form.value.tax,
        sub_type: form.value.sub_type,
        class: form.value.class,
    };

    if (isIndividual.value) {
        return {
            ...baseData,
            first_name: form.value.first_name,
            middle_name: form.value.middle_name,
            last_name: form.value.last_name,
            suffix: form.value.suffix,
        };
    } else {
        return {
            ...baseData,
            name: form.value.name,
        };
    }
});

const setForm = (value: any) => {
    form.value = {
        uuid: value.uuid ?? form.value.uuid ?? "",
        id_no: value.id_no ?? "",
        id_no_auto:
            typeof value.id_no_auto === "boolean" ? value.id_no_auto : true,
        first_name: value.first_name ?? "",
        last_name: value.last_name ?? "",
        middle_name: value.middle_name ?? "",
        suffix:
            typeof value.suffix === "object" &&
            value.suffix !== null &&
            "value" in value.suffix
                ? value.suffix.value
                : (value.suffix ?? ""),
        email: value.email ?? "",
        billing_address: value.billing_address ?? "",
        zip_code: value.zip_code ?? "",
        contact_number: value.contact_number ?? "",
        name: value.name ?? "",
        contacts:
            Array.isArray(value.contacts) && value.contacts.length > 0
                ? value.contacts.slice(0, 3).map((c) => ({
                      name: c.name ?? "",
                      address: c.address ?? "",
                      contact_number: c.contact_number ?? "",
                  }))
                : [],
        sub_type: value.sub_type
            ? {
                  id: value.sub_type.id,
                  value: value.sub_type.id,
                  label: value.sub_type.name,
              }
            : null,

        class: value.class
            ? {
                  id: value.class.id,
                  value: value.class.id,
                  label: value.class.name,
              }
            : null,
        tax: value.tax
            ? {
                  id: value.tax.uuid,
                  value: value.tax.uuid,
                  label: value.tax
                      ? `${value.tax.code} - ${value.tax.rate}%`
                      : "",
                  code: value.tax.code,
                  rate: value.tax.rate,
              }
            : null,
        country: value.country
            ? {
                  id: value.country.uuid,
                  value: value.country.uuid,
                  label: value.country.name,
              }
            : null,
    };

    subTypes.value = value.sub_type
        ? [
              {
                  id: value.sub_type?.id,
                  value: value.sub_type?.id,
                  label: value.sub_type?.name,
              },
          ]
        : [];

    classes.value = value.class
        ? [
              {
                  id: value.class?.id,
                  value: value.class?.id,
                  label: value.class?.name,
              },
          ]
        : [];

    taxes.value = value.tax
        ? [
              {
                  id: value.tax?.uuid,
                  value: value.tax?.uuid,
                  label: value.tax
                      ? `${value.tax?.code} - ${value.tax?.rate}%`
                      : "",
                  code: value.tax?.code,
                  rate: value.tax?.rate,
              },
          ]
        : [];
    countries.value = value.country
        ? [
              {
                  id: value.country?.uuid,
                  value: value.country?.uuid,
                  label: value.country?.name,
              },
          ]
        : [];
};

const isValid = (key: string) =>
    props.errors
        ? Object.keys(props.errors).includes(key)
            ? props?.errors[key]?.length <= 0
            : null
        : null;

const resetToBillingTab = () => {
    billingTab.value?.click();
};

watch(
    () => form.value.id_no_auto,
    (isAuto) => {
        if (isAuto) {
            form.value.id_no = "";
        }
    }
);

watch(
    filteredFormData,
    (value) => {
        props.form(value);
    },
    {
        deep: true,
    }
);

onMounted(() => {
    if (props.data) {
        setForm(props.data);
    }

    receive("clear", () => {
        form.value = { ...defaultForm, contacts: [] };
        resetToBillingTab();
    });

    props.schema(
        Yup.object().shape({
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
        })
    );
});
</script>
