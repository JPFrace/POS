<template>
    <NuxtLayout>
        <!-- Loading State -->
        <div
            v-if="!dataLoaded"
            class="h-screen flex justify-center items-start mt-50"
            role="status"
            aria-live="polite"
        >
            <div class="flex items-center gap-5">
                <div
                    class="spinner-border text-blue-500"
                    aria-hidden="true"
                ></div>
                <p class="text-gray-500 font-medium text-[14px] mt-4">
                    Loading...
                </p>
            </div>
        </div>
        <div v-else>
            <div class="d-flex flex-column flex-xl-row gap-8 mt-4">
                <DetailsContent
                    :form="savedData"
                    :avatar-url="avatarUrl"
                    :display-name="savedDisplayName"
                    @update:file="handleFileUpdate"
                />
                <Actions
                    v-model="form"
                    :is-individual="isIndividual"
                    @update:success="syncSavedData"
                />
                <!-- Begin::Tabs -->
                <div class="flex-grow-1 mr-6">
                    <ul
                        :id="props.data?.uuid + '_myTabContent'"
                        class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-4"
                        role="tablist"
                    >
                        <!-- ... Tab headers ... -->
                        <li class="nav-item">
                            <a
                                :id="props.data?.uuid + '_kt_tab_0'"
                                class="nav-link text-active-primary pb-2 active"
                                data-bs-toggle="tab"
                                :href="
                                    '#' + props.data?.uuid + '_kt_tab_pane_0'
                                "
                                role="tab"
                                :aria-controls="
                                    props.data?.uuid + '_kt_tab_pane_0'
                                "
                                aria-selected="true"
                            >
                                Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a
                                :id="props.data?.uuid + '_kt_tab_1'"
                                class="nav-link text-active-primary pb-2"
                                data-bs-toggle="tab"
                                :href="
                                    '#' + props.data?.uuid + '_kt_tab_pane_1'
                                "
                                role="tab"
                                :aria-controls="
                                    props.data?.uuid + '_kt_tab_pane_1'
                                "
                                aria-selected="false"
                            >
                                Billing Address
                            </a>
                        </li>
                        <li class="nav-item">
                            <a
                                :id="props.data?.uuid + '_kt_tab_2'"
                                class="nav-link text-active-primary pb-2"
                                data-bs-toggle="tab"
                                :href="
                                    '#' + props.data?.uuid + '_kt_tab_pane_2'
                                "
                                role="tab"
                                :aria-controls="
                                    props.data?.uuid + '_kt_tab_pane_2'
                                "
                                aria-selected="false"
                            >
                                Contacts
                            </a>
                        </li>
                        <li class="nav-item">
                            <a
                                :id="props.data?.uuid + '_kt_tab_3'"
                                class="nav-link text-active-primary pb-2"
                                data-bs-toggle="tab"
                                :href="
                                    '#' + props.data?.uuid + '_kt_tab_pane_3'
                                "
                                role="tab"
                                :aria-controls="
                                    props.data?.uuid + '_kt_tab_pane_3'
                                "
                                aria-selected="false"
                            >
                                Payments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a
                                :id="props.data?.uuid + '_kt_tab_4'"
                                class="nav-link text-active-primary pb-2"
                                data-bs-toggle="tab"
                                :href="
                                    '#' + props.data?.uuid + '_kt_tab_pane_4'
                                "
                                role="tab"
                                :aria-controls="
                                    props.data?.uuid + '_kt_tab_pane_4'
                                "
                                aria-selected="false"
                            >
                                Activities
                            </a>
                        </li>
                    </ul>

                    <div
                        :id="props.data?.uuid + '_myTabContent'"
                        class="tab-content"
                    >
                        <div
                            :id="props.data?.uuid + '_kt_tab_pane_0'"
                            class="tab-pane fade show active"
                            role="tabpanel"
                            :aria-labelledby="props.data?.uuid + '_kt_tab_0'"
                        >
                            <Profile
                                :form="form"
                                :is-valid="isValid"
                                v-model:selectedSubType="form.sub_type"
                                v-model:selectedClass="form.class"
                                v-model:selectedTax="form.tax"
                                :subTypes="subTypes"
                                :classes="classes"
                                :taxes="taxes"
                                @update:subTypes="(v) => (subTypes = v)"
                                @update:classes="(v) => (classes = v)"
                                @update:taxes="(v) => (taxes = v)"
                                :is-individual="isIndividual"
                            />
                        </div>

                        <div
                            :id="props.data?.uuid + '_kt_tab_pane_1'"
                            class="tab-pane fade"
                            role="tabpanel"
                            :aria-labelledby="props.data?.uuid + '_kt_tab_1'"
                        >
                            <BillingAddress
                                :form="form"
                                :is-valid="isValid"
                                v-model:selectedCountry="form.country"
                                :countries="countries"
                                @update:countries="(v) => (countries = v)"
                            />
                        </div>

                        <div
                            :id="props.data?.uuid + '_kt_tab_pane_2'"
                            class="tab-pane fade"
                            role="tabpanel"
                            :aria-labelledby="props.data?.uuid + '_kt_tab_2'"
                        >
                            <Contacts v-model:form="form" :is-valid="isValid" />
                        </div>
                    </div>
                </div>
                <!-- End::Tabs -->
            </div>
        </div>
    </NuxtLayout>
</template>

<script lang="ts" setup>
import type { Customers } from "~/types/customers";
import type { Option } from "~/types/form";
import DetailsContent from "../components/details.vue";
import Actions from "../actions/update.vue";
import Profile from "../components/profile.vue";
import BillingAddress from "../components/billing.vue";
import Contacts from "../components/contacts.vue";

const route = useRoute();
definePageMeta({
    permission: "Contacts.Customers.Edit",
});

interface Props {
    errors?: object;
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
    file: null,
    sub_type: null,
    class: null,
    tax: null,
};

// Separate state for saved data (shown in details content) and editable form data
const savedData = ref<Partial<Customers>>({ ...defaultForm });
const form = ref<Partial<Customers>>({ ...defaultForm });
const dataLoaded = ref(false);

const props = defineProps<Props>();
const subTypes = ref<Option[]>();
const classes = ref<Option[]>();
const taxes = ref<Option[]>();
const countries = ref<Option[]>();

const isIndividual = computed(() => {
    const subType = form.value?.sub_type;
    const text = String(subType?.label ?? "");
    return text.toLowerCase() === "individual";
});

const handleFileUpdate = (file: File | null) => {
    if (file === null) {
        // User clicked remove icon - set to default avatar
        form.value.file = null;
        savedData.value.file = null;
    } else {
        // User uploaded new file
        form.value.file = file;
    }
};

const avatarUrl = computed(() => {
    const file = savedData.value?.file;
    if (!file) return "/media/avatars/blank.png";
    return typeof file === "object" ? file.url : file;
});

const savedDisplayName = computed(() => {
    if (!savedData.value) return "";

    const savedSubTypeText = String(savedData.value.sub_type?.label ?? "");
    const wasSavedAsIndividual =
        savedSubTypeText.toLowerCase() === "individual";

    if (wasSavedAsIndividual) {
        return [
            savedData.value.first_name,
            savedData.value.middle_name,
            savedData.value.last_name,
            savedData.value.suffix,
        ]
            .filter((n) => n && n.trim() !== "")
            .join(" ");
    } else {
        return savedData.value.name || "";
    }
});

const {
    data: customerData,
    refresh,
    status,
} = useAsyncData(
    `${id(route.fullPath)}.customers`,
    async () =>
        await useClient(`/api/contacts/customers/${route.params.uuid}`, {
            method: "GET",
            params: {
                query: {
                    createdBy: true,
                    type: true,
                    class: true,
                    file: true,
                    tax: true,
                    country: true,
                },
            },
        }),
    {
        server: false,
        lazy: true,
        immediate: true,
    }
);

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
        file: value.file ?? null,
        name: value.name ?? "",
        contacts:
            Array.isArray(value.contacts) && value.contacts.length > 0
                ? value.contacts.slice(0, 3).map((c) => ({
                      uuid: c.uuid,
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
                      ? `${value.tax.name} - ${value.tax.rate}%`
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

const preloadImage = (url: string): Promise<void> => {
    return new Promise((resolve) => {
        if (url === "/media/avatars/blank.png") {
            resolve();
            return;
        }

        const img = new Image();
        const timeout = setTimeout(() => resolve(), 3000);

        img.onload = () => {
            clearTimeout(timeout);
            resolve();
        };

        img.onerror = () => {
            clearTimeout(timeout);
            resolve();
        };
        img.src = url;
    });
};

watch(
    customerData,
    async (value) => {
        if (!value) return;

        setForm(value);
        savedData.value = { ...form.value };

        await preloadImage(avatarUrl.value);
        dataLoaded.value = true;
    },
    { immediate: true }
);

onMounted(async () => {
    if (props.data) {
        setForm(props.data);
        savedData.value = { ...form.value };

        await preloadImage(avatarUrl.value);
        dataLoaded.value = true;
    }
});

const syncSavedData = (updated?: any) => {
    if (updated?.file?.url) {
        savedData.value.file = updated.file.url;
    } else if (updated?.file === null) {
        // Handle file removal
        savedData.value.file = null;
    } else {
        savedData.value = { ...form.value };
    }
};
</script>
