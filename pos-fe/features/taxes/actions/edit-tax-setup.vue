<template>
    <Button variant="primary" label="Edit Tax Settings" @click="show" />

    <Drawer
        :id="drawerKey"
        title="Edit Tax Settings"
        description="Update record"
        :processing="processing"
        :width="width"
        :width-lg="widthLg"
        @submit="submit"
        @cancel="hide"
    >
        <template #footer v-if="$slots.drawerFooter">
            <slot
                name="drawerFooter"
                :submit="submit"
                :processing="processing"
                :hide="hide"
            />
        </template>
        <template v-if="isVisible">
            <slot
                name="form"
                :errors="errors"
                :form="syncForm"
                :schema="schemaSync"
                :data-ref="form"
            />
        </template>
    </Drawer>
</template>

<script lang="ts" setup>
import type { Method, Throwable } from "~/types/common";
import type { AnyObject } from "yup";
import type { Ref } from "vue";
import isEqual from "lodash/isEqual";
import { usePageEvent } from "#imports";

interface Props {
    endpoint: string | (() => string);
    method?: Method["method"];
    transform?: Function;
    validate?: Function;
    widthLg?: string;
    width?: string;
    clientOptions?: object;
    params: any;
}

const props = withDefaults(defineProps<Props>(), {
    method: "PATCH",
    headers: {
        Accept: "application/json",
        "Cache-Control": "no-cache",
        "Content-Type": "multipart/form-data",
    },
});

const emits = defineEmits(["beforeShow"]);
const drawerKey = "tax_setup_edit";
const drawer = ref<any>();

const form = ref<any>();
const originalData = ref<any>();
const schema = ref<AnyObject>();
const processing = ref(false);
const errors = ref<any[]>([]);

const { t } = useI18n();
const { $message, $bus, $drawer } = useNuxtApp();
const { validate } = useYup();
const { send } = usePageEvent();

const endpoint = (): string => {
    let ep = props.endpoint;
    if (typeof ep === "function") ep = ep();
    return ep as string;
};

const syncForm = (value: Ref) => (form.value = value);
const schemaSync = (value: AnyObject) => (schema.value = value);

const transformForm = () => {
    let data = form.value;
    if (typeof props.transform === "function") {
        data = props.transform(data);
    }

    if (data.start_tax_period && typeof data.start_tax_period === "object") {
        data.start_tax_period = data.start_tax_period.value;
    }

    if (data.period && typeof data.period === "object") {
        data.period = data.period.value;
    }

    if (data.reporting_method && typeof data.reporting_method === "object") {
        data.reporting_method = data.reporting_method.value;
    }

    const formData = new FormData();
    const keys = Object.keys(data);

    const isFile = (value: any): value is File => value instanceof File;
    const isPlainObject = (val: any) =>
        typeof val === "object" &&
        val !== null &&
        !Array.isArray(val) &&
        !(val instanceof File);

    let hasFile = false;

    for (const key of keys) {
        const val = data[key];
        if (
            isFile(val) ||
            (Array.isArray(val) && val[0]?.file instanceof File)
        ) {
            hasFile = true;
            break;
        }
    }

    if (hasFile) {
        for (const key of keys) {
            const val = data[key];

            if (Array.isArray(val) && isFile(val[0]?.file)) {
                if (val.length) formData.append(key, val[0].file);
            } else if (isFile(val)) {
                formData.append(key, val);
            } else if (isPlainObject(val)) {
                formData.append(key, JSON.stringify(val));
            } else {
                formData.append(key, val);
            }
        }

        return formData;
    }

    return data;
};

const validateForm = () => {
    if (typeof props.validate === "function") {
        return props.validate(schema.value, form.value);
    }
    return validate(schema.value, { data: form.value });
};

const isVisible = ref(false);

const hide = () => {
    isVisible.value = false;
    drawer.value.hide();
};
const params = defineModel<{ query: object }>("params");

const show = async () => {
    emits("beforeShow");

    try {
        const res = await useClient(endpoint(), {
            method: "GET",
            params: {
                ...params.value,
                query: {
                    ...(params.value?.query ?? {}),
                },
            },
        });

        //refresh
        if (originalData.value && form.value) {
            Object.assign(
                form.value,
                JSON.parse(JSON.stringify(originalData.value)),
            );
        }

        const payload = res?.data?.value ?? res;
        form.value = JSON.parse(JSON.stringify(payload.data[0]));
        originalData.value = JSON.parse(JSON.stringify(payload.data[0]));
    } catch (error: any) {
        $message("error", error?.message ?? t("error.failed_request"));
    }
    isVisible.value = true;

    drawer.value.show();
};

const submit = async (closure?: Function) => {
    try {
        if (processing.value) return;
        processing.value = true;

        await validateForm();
        // Transform start_tax_period to just its value if it's an object

        const transformed = transformForm();

        if (!originalData.value)
            originalData.value = JSON.parse(JSON.stringify(form.value));

        if (isEqual(transformed, originalData.value)) {
            processing.value = false;
            $message("info", t("action.no_changes"));
            drawer.value.hide();
            return;
        }

        await useClient(`/api/taxes/tax-setup/${form.value.uuid}`, {
            method: props.method,
            headers: props.headers,
            body: transformed,
            ...props.clientOptions,
        });

        originalData.value = JSON.parse(JSON.stringify(form.value));
        processing.value = false;
        errors.value = [];
        $message("success", t("action.saved"));
        drawer.value.hide();
        send("refresh");

        if (typeof closure === "function") closure();
    } catch (error: Throwable) {
        errors.value = error?.errors ?? [];
        processing.value = false;
        $message("error", error.message ?? t("error.failed_request"));
    }
};

onMounted(() => {
    drawer.value = $drawer(drawerKey);
});
</script>
