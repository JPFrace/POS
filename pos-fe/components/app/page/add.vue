<template>
    <Button type="button" variant="primary" icon="plus-square" @click="show"
        >Add New</Button
    >
    <Drawer
        :id="`${key}_add`"
        :title="title"
        :description="description"
        :processing="processing"
        :width="width"
        :width-lg="widthLg"
        @submit="submit"
        @cancel="cancel"
    >
        <template #footer v-if="$slots.drawerFooter">
            <slot
                name="drawerFooter"
                :submit="submit"
                :cancel="cancel"
                :processing="processing"
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
import { ref, type Ref } from "vue";
import type { Throwable, Method } from "~/types/common";
import type { AnyObject } from "yup";

interface Props {
    endpoint: string;
    method: Method["method"];
    widthLg: string;
    width: string;
    transform: Function;
    validate: Function;
    title: string;
    description: string;
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

const emits = defineEmits(["beforeShow", "success"]);

const route = useRoute();
const { t } = useI18n();
const { validate } = useYup();
const { $message, $drawer } = useNuxtApp();
const { send } = usePageEvent();

const form = ref<unknown>();

const key = id(route.fullPath);
const processing = ref(false);
const drawer = ref();
const errors = ref([]);
const schema = ref<AnyObject>();

const execute = () =>
    useClient(props.endpoint, {
        method: props.method,
        headers: props.headers,
        body: transformForm(),
    });

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

const cancel = (closure?: Function) => {
    drawer.value.hide();
    if (closure) {
        closure();
    }
};

const transformForm = () => {
    let data = form.value;

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

const validateForm = () => {
    if (typeof props.validate == "function") {
        return props.validate(schema.value, form.value);
    }

    return validate(schema.value, { data: form.value });
};

const show = () => {
    emits("beforeShow");

    drawer.value.show();
};

const submit = async (closure?: Function) => {
    console.log(form.value);
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

        drawer.value.hide();

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

onMounted(() => {
    drawer.value = $drawer(`${key}_add`);
});
</script>
