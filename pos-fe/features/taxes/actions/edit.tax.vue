<template>
    <KTIcon
        title="Edit Record"
        icon-name="notepad-edit"
        icon-class="!text-3xl cursor-pointer !text-blue-500 hover:!text-blue-700 dark:hover:!text-blue-400"
        @click="show"
    />
</template>

<script lang="ts" setup>
import type { Method, Throwable } from "~/types/common";
import type { AnyObject } from "yup";
import type { Ref } from "vue";
import isEqual from "lodash/isEqual";
import { usePageEvent } from "#imports";
import type { Tax } from "~/types/Tax";
const isVisible = ref(false);

interface Props {
    id: string;
    endpoint: string;
    method?: Method["method"];
    uuid: string;

    transform?: Function;
    validate?: Function;
    widthLg?: string;
    width?: string;
    clientOptions?: object;
    data: object;
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

const emits = defineEmits<{
    (e: "beforeShow"): void;
    (e: "registerEditSubmit", fn: () => void): void;
}>();

const hide = () => {
    isVisible.value = false;
};
const show = () => {
    emits("beforeShow");
    console.log("Show");
    // Populate form with fresh props.data
    if (form.value) {
        Object.assign(form.value, JSON.parse(JSON.stringify(props.data)));
    } else {
        form.value = JSON.parse(JSON.stringify(props.data));
    }

    // Save original data for reset
    originalData.value = JSON.parse(JSON.stringify(props.data));
    console.log("PROPS: ", props);
    $bus.emit("tax:edit", props.data);
};

const formKey = ref(props.id);

const { t } = useI18n();

const { $message, $bus, $drawer } = useNuxtApp();

const { validate } = useYup();

const form = ref<any>();

// This watch monitors the 'form' ref for changes
watch(
    () => form.value,
    (val) => {
        if (val && !originalData.value) {
            console.log("CHANGEDL");
            originalData.value = JSON.parse(JSON.stringify(val));
        }
    },

    {
        immediate: true,
    },
);

const schema = ref<AnyObject>();

const endpoint = (row: any) => {
    console.log("API ", props.endpoint.trimEnd() + "/" + row.uuid);
    return props.endpoint.trimEnd() + "/" + row.uuid;
};

const transformForm = () => {
    console.log("submitting ", form.value);
    let data = form.value;
    if (typeof props.transform == "function") {
        data = props.transform(data);
    }
    console.log("1");
    const formData = new FormData();
    const keys = Object.keys(data);
    const hasFile = Object.keys(data).some((key) => {
        const val = data[key];
        return (
            val instanceof File || // direct file
            (Array.isArray(val) && val[0]?.file instanceof File) // array of { file: File }
        );
    });
    console.log("2");

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
    console.log("3");

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
    console.log("4");

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
    console.log("changed:", form.value);
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
    console.log("Submitting form dtaa:", form.value);
    console.log("Submitting props data:", props.data);

    try {
        if (processing.value) return;

        processing.value = true;
        // await validateForm();

        const transformed = transformForm();
        console.log("5");
        console.log(originalData);

        // Optional fallback in case originalData isn't set yet
        if (!originalData.value) {
            originalData.value = JSON.parse(JSON.stringify(props.data));
        }
        console.log("6");

        await useClient(endpoint(form.value), {
            method: props.method,
            headers: props.headers,
            body: transformed,
            ...clientOptions.value,
        });

        // Save updated state only after a successful request
        originalData.value = JSON.parse(JSON.stringify(props.data));

        processing.value = false;

        errors.value = [];

        $message("success", t("action.saved"));

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
// defineExpose({
//     submit,
// });
const { receive, dismiss } = usePageEvent();
onMounted(() => {
    // 👉 register this submit function to parent
    emits("registerEditSubmit", submit);
    // receive(`clear${formKey.value}`, () => {
    //     if (originalData.value && form.value) {
    //         Object.assign(
    //             form.value,
    //             JSON.parse(JSON.stringify(originalData.value)),
    //         );
    //     }
    // });
    $bus.on("tax:update", (row: Tax) => {
        console.log("set: ", row);
        // setForm(row);
        console.log(row);

        form.value = row;
        console.log("new form value: ", form.value);
        // console.log("new form loaded: ", form.value);
    });
});
watch(props, (value) => console.log("LOGGING PROPS", value));
</script>
