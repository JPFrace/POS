<template>
  <div class="flex items-center relative" ref="wrapper">
    <button
      title="Bookmark Report"
      v-if="report && dates"
      class="flex rounded-full p-2 border-0"
      @click="toggleDialog"
    >
      <i
        :class="[
          form.uuid
            ? 'ki-solid ki-star text-warning'
            : 'ki-solid ki-star text-default',
          'fs-2tx',
        ]"
      ></i>
    </button>

    <Transition name="fade">
      <div
        v-if="showDialog"
        ref="dialog"
        class="absolute top-full right-0 mt-2 w-[380px] bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50"
      >
        <div class="p-4">
          <h3 class="text-sm fw-bold mb-3 text-gray-800 dark:text-gray-100">
            {{ form.uuid ? "Edit Bookmark" : "Add Bookmark" }}
          </h3>

          <div class="space-y-3">
            <div class="flex items-center gap-2">
              <label class="form-label text-xs text-gray-600 w-1/5 text-left">Name</label>
              <el-input v-model="form.name" placeholder="Bookmark name" />
            </div>
            <p v-if="errors.name" class="text-danger text-xs ml-[20%]">
              {{ errors.name }}
            </p>

            <div class="flex items-center gap-2">
              <label class="form-label text-xs text-gray-600 w-1/5 text-left">Group</label>
              <el-select v-model="form.group" placeholder="Group">
                <el-option value="Bookmarks" label="Bookmarks" />
                <el-option value="Operational Reports" label="Operational Reports" />
                <el-option value="Financial Reports" label="Financial Reports" />
                <el-option value="Administrative" label="Administrative" />
              </el-select>
            </div>
            <p v-if="errors.group" class="text-danger text-xs ml-[20%]">
              {{ errors.group }}
            </p>
          </div>

          <div class="d-flex justify-content-between align-items-center mt-4">
            <div>
              <button
                v-if="form.uuid"
                type="button"
                class="btn btn-link text-danger p-0"
                @click="removeBookmark"
              >
                Remove
              </button>
            </div>

            <div class="d-flex gap-2">
              <button type="button" class="btn btn-light btn-sm" @click="closeDialog">
                Cancel
              </button>
              <button
                type="button"
                class="btn btn-primary btn-sm"
                @click="saveBookmark"
                :disabled="loading"
              >
                {{ loading ? "Saving..." : "Done" }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup lang="ts">
import { ref, watch, onMounted, onBeforeUnmount } from "vue";
import * as yup from "yup";
import { v4 as uuidv4 } from "uuid";

const props = defineProps({
  report: Object,
  dates: Array,
});

const emit = defineEmits(["saved", "deleted"]);

const showDialog = ref(false);
const loading = ref(false);
const errors = ref<Record<string, string>>({});
const dialog = ref<HTMLElement | null>(null);
const wrapper = ref<HTMLElement | null>(null);

const form = ref({
  uuid: null,
  name: "",
  group: "",
  report_id: "",
  date_from: "",
  date_to: "",
  template: "",
  description: "",
});

const schema = yup.object({
  name: yup.string().required("Name is required"),
  group: yup.string().required("Group is required"),
});

const toggleDialog = () => (showDialog.value = !showDialog.value);
const closeDialog = () => (showDialog.value = false);

const LS_KEY = "report_bookmarks";

const loadBookmarks = (): any[] => {
  const stored = localStorage.getItem(LS_KEY);
  return stored ? JSON.parse(stored) : [];
};

const saveBookmarks = (bookmarks: any[]) => {
  localStorage.setItem(LS_KEY, JSON.stringify(bookmarks));
};

const findBookmark = (reportId: string) => {
  const bookmarks = loadBookmarks();
  return bookmarks.find((b) => b.report_id === reportId);
};

const setForm = (report: any, dates: any) => {
  const existing = report?.id ? findBookmark(report.id) : null;
  form.value = {
    uuid: existing?.uuid ?? null,
    name: existing?.name ?? report?.label ?? "",
    group: existing?.group ?? "Bookmarks",
    report_id: report?.id ?? "",
    template: report?.template ?? "Default",
    description: report?.description ?? "",
    date_from: (dates as any)?.[0] ? new Date((dates as any)[0]) : "",
    date_to: (dates as any)?.[1] ? new Date((dates as any)[1]) : "",
  };
};

const saveBookmark = async () => {
  errors.value = {};
  loading.value = true;

  try {
    await schema.validate(form.value, { abortEarly: false });
    const bookmarks = loadBookmarks();
    let updatedList;
    if (form.value.uuid) {
      updatedList = bookmarks.map((b) =>
        b.uuid === form.value.uuid ? form.value : b
      );
    } else {
      form.value.uuid = uuidv4();
      updatedList = [...bookmarks, { ...form.value }];
    }
    saveBookmarks(updatedList);
    emit("saved", form.value);
    closeDialog();
  } catch (err: any) {
    if (err.name === "ValidationError") {
      err.inner.forEach((e: any) => {
        errors.value[e.path] = e.message;
      });
    }
  } finally {
    loading.value = false;
  }
};

const removeBookmark = () => {
  if (!form.value.uuid) return;
  const bookmarks = loadBookmarks();
  const updatedList = bookmarks.filter((b) => b.uuid !== form.value.uuid);
  saveBookmarks(updatedList);
  emit("deleted", form.value.uuid);
  form.value.uuid = null;
  closeDialog();
};

const handleClickOutside = (event: MouseEvent) => {
  if (!showDialog.value) return;
  const target = event.target as Node;
  if (
    dialog.value &&
    !dialog.value.contains(target) &&
    wrapper.value &&
    !wrapper.value.contains(target)
  ) {
    closeDialog();
  }
};

onMounted(() => {
  document.addEventListener("mousedown", handleClickOutside);
});

onBeforeUnmount(() => {
  document.removeEventListener("mousedown", handleClickOutside);
});

watch(
  [() => props.report, () => props.dates],
  ([report, dates]) => {
    if (report && dates) setForm(report, dates);
  },
  { immediate: true }
);
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
