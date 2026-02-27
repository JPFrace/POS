<template>
    <div class="bookmark-container">
        <el-tooltip content="Save to bookmarks" placement="right">
            <el-dropdown ref="bookmarkButton" trigger="manual">
                <el-button
                    :type="isEmpty(bookmark) ? 'info' : 'success'"
                    @click.prevent="saveBookmark"
                    :disabled="bookmarkDisabled"
                    style="height: 43px"
                >
                    <el-icon><star-filled /></el-icon>
                </el-button>
                <template #dropdown>
                    <DropdownContent
                        v-model="bookmark"
                        :isUpdate="isBookmarkToUpdate"
                        :bookmarkButton="bookmarkButton"
                    />
                </template>
            </el-dropdown>
        </el-tooltip>
    </div>
</template>

<script lang="ts" setup>
import { StarFilled } from "@element-plus/icons-vue";
import type { DropdownInstance } from "element-plus";
import DropdownContent from "./dropdown-content.vue";

const { t } = useI18n();
const { $message, $swal } = useNuxtApp();
const { yup, validate } = useYup();
const { send } = usePageEvent();
const Yup = yup();

const { data, error, refresh, execute } = await useFetch(
    () => "/api/reports/bookmarks"
);
const bookmark = ref({});
const bookmarkDisabled = ref(true);
const isBookmarkToUpdate = ref(false);
const bookmarkButton = ref<DropdownInstance>();
defineExpose({ bookmarkButton });
const entry = defineModel();
watch(
    entry,
    async (a, b) => {
        const checkDateFrom = a.date_from != "";
        const checkDateTo = a.date_to != "";
        const checkReport = a.report != "";

        if (checkDateFrom && checkDateTo && checkReport) {
            bookmarkDisabled.value = false;
            await checkBookmarkExist();
        } else {
            bookmarkDisabled.value = true;
        }
    },
    { deep: true }
);

const bookmarkValidateForm = () => {
    const form = Yup.object().shape({
        date_from: Yup.string(),
        date_to: Yup.string(),
        report: Yup.string(),
    });

    return validate(form, {
        data: entry.value,
    });
};

const saveBookmark = async () => {
    const formData = new FormData();

    const keys = Object.keys(entry.value);
    for (var key of keys) {
        formData.append(key, entry.value[key]);
    }

    try {
        await bookmarkValidateForm();

        await checkBookmarkExist();

        if (isEmpty(bookmark.value)) {
            await useClient("/api/reports/bookmarks", {
                method: "POST",
                body: formData,
            });
            await checkBookmarkExist();
            isBookmarkToUpdate.value = false;
            bookmarkButton.value?.handleOpen();
            return;
        }

        isBookmarkToUpdate.value = true;
        bookmarkButton.value?.handleOpen();
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
        throw error;
    }
};

const checkBookmarkExist = async () => {
    await refresh();
    for (var item of data.value.data) {
        const checkDateFrom = entry.value.date_from == item.date_from;
        const checkDateTo = entry.value.date_to == item.date_to;
        const checkReport = entry.value.report == item.report_details.uuid;
        if (checkDateFrom && checkDateTo && checkReport) {
            bookmark.value = item;
            return true;
        }
    }
    bookmark.value = {};
    return false;
};

const isEmpty = (obj: object) => Object.keys(obj).length === 0;
</script>

<style scoped>
.bookmark-container {
    position: absolute;
    top: 170px;
}
</style>
