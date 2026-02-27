<template>
    <!-- Trigger Button -->
    <Button variant="primary" label="Add Tax" @click="show" />

    <!-- Drawer -->
    <Drawer
        :id="drawerKey"
        title="Add Tax"
        description="Add record"
        :processing="processing"
        :width="width"
        :width-lg="widthLg"
        no-cancel
        no-submit
    >
        <!-- Form Slot -->
        <template v-if="isVisible">
            <slot
                ref="taxFormRef"
                name="form"
                :errors="errors"
                :form="syncForm"
                :schema="schemaSync"
                :data-ref="form"
                :submit="submit"
                :hide="hide"
                :processing="processing"
            />
        </template>
    </Drawer>
</template>

<script lang="ts" setup>
import type { AnyObject } from "yup";
const { $message, $bus, $drawer } = useNuxtApp();
const { send } = usePageEvent();
const { validate } = useYup();
const { t } = useI18n();
const taxFormRef = ref(null);

const isVisible = ref(false);
const drawerKey = "tax_add";
const drawer = ref<any>();
const emits = defineEmits(["beforeShow", "success", "open"]);
import type { Method, Throwable } from "~/types/common";
const form = ref<any>();
const originalData = ref<any>();
const schema = ref<AnyObject>();
const processing = ref(false);
const errors = ref<any[]>([]);
const showForm = ref(false);
const isEdit = ref(false);
interface Props {
    endpoint: string;
    method?: Method["method"];
    transform?: Function;
    validate?: Function;
    widthLg?: string;
    width?: string;
    clientOptions?: object;
    params: any;
}

const props = withDefaults(defineProps<Partial<Props>>(), {
    method: "POST",
    headers: {
        Accept: "application/json",
        "Cache-Control": "no-cache",
        "Content-Type": "multipart/form-data",
    },
    title: "Add New",
    description: "Add new record",
});

const hide = () => {
    isVisible.value = false;
    drawer.value.hide();
};

const execute = () =>
    useClient(props.endpoint, {
        method: props.method,
        headers: props.headers,
        body: transformForm(),
    });

const transformForm = () => {
    let data = form.value;
    console.log("submitting ", form.value);
    const formData = new FormData();

    if (typeof props.transform == "function") {
        data = props.transform(data);
    }

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

const submit = async (closure?: Function) => {
    console.log(form.value);
    // Call toggleForm on the child form
    try {
        if (processing.value) return;

        processing.value = true;

        await validateForm();

        await execute();
        form.value = clearKeyValue(form.value);
        errors.value = [];

        processing.value = false;

        $message("success", t("action.created"));

        // Emit success so parent can reset form (e.g., via resetKey++)
        emits("success");

        send(`refresh`);

        if (typeof closure == "function") {
            closure();
        }
    } catch (error: Throwable) {
        errors.value = error.errors;
        processing.value = false;
        $message("error", error.message ?? t("error.failed_request"));
    }
};

const syncForm = (value: Ref) => (form.value = value);
const schemaSync = (value: AnyObject) => (schema.value = value);

const show = async () => {
    emits("beforeShow");

    isVisible.value = true;

    drawer.value.show();
};

const validateForm = () => {
    if (typeof props.validate == "function") {
        return props.validate(schema.value, form.value);
    }

    return validate(schema.value, { data: form.value });
};

onMounted(() => {
    drawer.value = $drawer(drawerKey);
});
</script>
