<template>
    <el-dropdown-menu>
        <div style="padding: 20px;">
            <h2 style="margin-bottom: 10px;">{{ isUpdate ? 'Edit Bookmark' : 'Bookmark Added' }}</h2>
            <Input v-model="additionalData.name" placeholder="Name" label="Name" class="my-2" float />
            <Input v-model="additionalData.group" placeholder="Group" label="Group" class="my-2" float />
            <div class="flex justify-end space-x-2">
                <Button variant="primary" label="Save" class="!uppercase" @click="save" style="margin-right: 5px;" />
                <Button variant="secondary" label="Remove" class="!uppercase" @click="remove" />
            </div>
        </div>
    </el-dropdown-menu>
</template>

<script lang="ts" setup>
import type { DropdownInstance } from 'element-plus';


interface Props {
    isUpdate?: boolean,
    bookmarkButton?: DropdownInstance,
}
const props = withDefaults(defineProps<Props>(), {
    isUpdate: false,
})

const { t } = useI18n();
const { $swal } = useNuxtApp();
const { yup, validate } = useYup();
const { send } = usePageEvent();
const Yup = yup();

const data = defineModel();

const bookmarkValidateForm = () => {
    const form = Yup.object().shape({
        name: Yup.string(),
        group: Yup.string(),
    })

    return validate(form, {
        data: additionalData.value,
    });
}

const save = async () => {
    const formData = new FormData();

    formData.append('date_from', data.value.date_from);
    formData.append('date_to', data.value.date_to);
    formData.append('report_id', data.value.report_details.uuid);
    formData.append('name', additionalData.value.name);
    formData.append('group', additionalData.value.group);

    try {
        await bookmarkValidateForm();;

        await useClient(`/api/reports/bookmarks/${data.value.uuid}`, {
            method: "PATCH",
            body: Object.fromEntries(formData),
        });
        props.bookmarkButton?.handleClose();
        ElMessage({
            message: 'Bookmark edited.',
            type: 'success',
        })
    } catch (error: any) {
        const errors = error?.errors ?? [];

        send("on:error", errors);

        var messages = [];

        for (var e of Object.values(errors)) {
            messages.push((e as string[])[0]);
        }

        var html = "<ol>";
        for (var m of messages) {
            html += `<li>${m}</li>`;
        }

        html += "</ol>";


        $swal("error", {
            title: error.message ?? t("error.failed_request"),
            html,
        });
        throw error
    }
}

const remove = async () => {
    try {
        await useClient(`/api/reports/bookmarks/${data.value.uuid}`, {
            method: "DELETE",
        });
        data.value = {};
        props.bookmarkButton?.handleClose();
        ElMessage({
            message: 'Bookmark removed.',
            type: 'success',
        });
    } catch (error: any) {
    }
}

const additionalData = ref({
    name: '',
    group: ''
});
</script>