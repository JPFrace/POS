<template>
    <KTIcon
        title="Edit Record"
        icon-name="notepad-edit"
        icon-class="!text-3xl cursor-pointer !text-blue-500 hover:!text-blue-700 dark:hover:!text-blue-400"
        icon-type="outline"
        @click="show"
    />

    <Drawer
        :id="`${drawerKey}_edit`"
        title="Edit"
        description="Update record"
        :processing="processing"
        :width="width"
        :width-lg="widthLg"
        @submit="submit"
        @cancel="drawer.hide()"
    >
        <template #footer v-if="$slots.drawerFooter">
            <slot
                name="drawerFooter"
                :submit="submit"
                :processing="processing"
                :cancel="() => drawer.hide()"
            />
        </template>
        <slot
            name="form"
            :errors="errors"
            :form="setForm"
            :sync-form="syncForm"
            :schema="schemaSync"
        />
    </Drawer>
</template>

<script lang="ts" setup>
import type { Method, Throwable } from "~/types/common";
import type { AnyObject } from "yup";
import type { Ref } from "vue";
import isEqual from "lodash/isEqual";
import { usePageEvent } from "#imports";

interface Props {
    id: string;
    endpoint: string | Function;
    method?: Method["method"];
    transform?: Function;
    validate?: Function;
    widthLg?: string;
    width?: string;
    clientOptions?: object;
}

const { send } = usePageEvent();

const props = withDefaults(defineProps<Props>(), {
    method: "PATCH",
    headers: {
        Accept: "application/json",
        "Cache-Control": "no-cache",
        "Content-Type": "multipart/form-data",
    },
});

const emits = defineEmits(["beforeShow"]);

const show = () => {
    emits("beforeShow");

    // Reset the form to last saved/original state
    if (originalData.value && form.value) {
        Object.assign(
            form.value,
            JSON.parse(JSON.stringify(originalData.value)),
        );
    }

    drawer.value.show();
};

const formKey = ref(props.id);

const key = id(useRoute().fullPath);

const drawerKey = key + "_" + props.id;

const drawer = ref();

const { t } = useI18n();

const { $message, $bus, $drawer } = useNuxtApp();

const { validate } = useYup();

const form = ref<any>();

// This watch monitors the 'form' ref for changes
watch(
    // The source we are watching: when 'form.value' changes, the callback runs
    () => form.value,

    // Callback: runs whenever 'form.value' changes
    (val) => {
        // Check if the new form value exists and 'originalData' hasn't been set yet
        if (val && !originalData.value) {
            // Save a deep copy of the form value as the original data
            // This is used to compare later if the user made any changes
            originalData.value = JSON.parse(JSON.stringify(val));
        }
    },

    // Options object for the watch
    {
        // Run the callback immediately on first setup (e.g., on page load)
        immediate: true,
    },
);

const schema = ref<AnyObject>();

const endpoint = (): string => {
    let endpoint = props.endpoint;
    if (typeof endpoint == "function") {
        endpoint = endpoint();
    }

    return endpoint as string;
};

const transformForm = () => {
    let data = form.value;
    if (typeof props.transform == "function") {
        data = props.transform(data);
    }

    const formData = new FormData();
    const keys = Object.keys(data);
    const hasFile = Object.keys(data).some((key) => {
        const val = data[key];
        return (
            val instanceof File || // direct file
            (Array.isArray(val) && val[0]?.file instanceof File) // array of { file: File }
        );
    });
    const hasFileExist = (val: any): val is Record<string, any> =>
        typeof val === "object" && val.filename !== null && val !== null;
    const isPlainObject = (val: any): val is Record<string, any> =>
        typeof val === "object" &&
        val !== null &&
        !Array.isArray(val) &&
        !(val instanceof File);

    const isFile = (value: unknown): value is File => {
        return value instanceof File;
    };
    const isFormDataNotEmpty = (formData: FormData): boolean => {
        for (const _ of formData.entries()) {
            return true;
        }
        return false;
    };
    if (hasFile) {
        for (var key of keys) {
            if (Array.isArray(data[key]) && isFile(data[key][0].file)) {
                if (data[key]?.length) {
                    formData.append(key, data[key][0]?.file);
                }
            } else if (isPlainObject(data[key])) {
                formData.append(key, JSON.stringify(data[key]));
            } else {
                formData.append(key, data[key]);
            }
        }
    }

    return isFormDataNotEmpty(formData) ? formData : data;
};

const validateForm = () => {
    if (typeof props.validate == "function") {
        return props.validate(schema.value, form.value);
    }

    return validate(schema.value, { data: form.value });
};

const setForm = (value: Ref) => {
    form.value = value;
};

const syncForm = (value: Ref) => {
    form.value = { ...form.value, ...value };
    console.log(form.value);
};

const schemaSync = (value: AnyObject) => {
    schema.value = value;
};

const clientOptions = computed(() => {
    if (typeof props.clientOptions === "object") {
        return props.clientOptions;
    }

    return {};
});

const execute = () => {
    return useClient(endpoint(), {
        method: props.method,
        body: transformForm(),
        ...clientOptions.value,
    });
};

const processing = ref(false);

const errors = ref([]);

const originalData = ref<any>(); // This will store the original data before editing

const submit = async (closure?: Function) => {
    try {
        if (processing.value) return;

        processing.value = true;

        await validateForm();

        const transformed = transformForm();

        // Optional fallback in case originalData isn't set yet
        if (!originalData.value) {
            originalData.value = JSON.parse(JSON.stringify(form.value));
        }

        // Check if the form data has changed compared to the original
        // If nothing changed, skip the API request and just close the drawer
        if (isEqual(transformed, originalData.value)) {
            processing.value = false;
            $message("info", t("action.no_changes")); // Display toast message
            drawer.value.hide();
            return;
        }

        await useClient(endpoint(), {
            method: props.method,
            headers: props.headers,
            body: transformed,
            ...clientOptions.value,
        });

        // Save updated state only after a successful request
        originalData.value = JSON.parse(JSON.stringify(form.value));

        processing.value = false;

        errors.value = [];

        $message("success", t("action.saved"));

        drawer.value.hide();

        send(`refresh`);

        if (typeof closure == "function") {
            closure();
        }
    } catch (error: Throwable) {
        errors.value = error?.errors ?? [];
        processing.value = false;
        $message("error", error.message ?? t("error.failed_request"));
    }
};

const showDrawer = () => {
    drawer.value.show();

    // Save a copy of the current form data before editing
    // This lets us compare later if the user made any changes
    originalData.value = JSON.parse(JSON.stringify(form.value));

    $bus.emit(`${drawerKey}:on:edit`);
};

const { receive, dismiss } = usePageEvent();

onMounted(() => {
    drawer.value = $drawer(drawerKey + "_edit");

    receive(`clear${formKey.value}`, () => {
        if (originalData.value && form.value) {
            Object.assign(
                form.value,
                JSON.parse(JSON.stringify(originalData.value)),
            );
        }
    });
});
</script>
