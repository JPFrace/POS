<template>
  <div class="card card-flush" :class="className">
    <div class="card-header pt-5">
      <h3 class="card-title text-gray-800 fw-bold">Bookmarks</h3>
      <div class="card-toolbar">
        <button
          class="btn btn-icon btn-color-gray-500 btn-active-color-primary justify-content-end"
          data-kt-menu-trigger="click"
          data-kt-menu-placement="bottom-end"
          data-kt-menu-overflow="true"
        >
          <KTIcon icon-name="dots-square" icon-class="fs-1 text-gray-300 me-n1" />
        </button>
        <div
          class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 
                 menu-state-bg-light-primary fw-semibold w-200px"
          data-kt-menu="true"
        >
          <div class="menu-item px-3">
            <a href="#" class="menu-link px-3 text-danger" @click.prevent="clearBookmarks">
              <KTIcon icon-name="trash" icon-class="fs-5 me-2 text-danger" />
              Clear Bookmarks
            </a>
          </div>
        </div>
      </div>
    </div>

    <div class="card-body pt-3 pb-2">
      <template v-for="(group, index) in groupedBookmarks" :key="index">
        <h5 class="fw-bold text-gray-700 fs-7 text-uppercase mb-2">{{ group.name }}</h5>
        <template v-for="bookmark in group.items" :key="bookmark.uuid">
          <div
            class="d-flex flex-stack align-items-center cursor-pointer py-1"
            @click="redirect(bookmark)"
          >
            <span class="fw-semibold fs-6 transition-all me-2">
              <KTIcon icon-name="book" icon-class="fs-4 me-1 text-warning" />
              {{ bookmark.name || bookmark.report?.name || "Untitled" }}
            </span>
            <button
              type="button"
              class="btn btn-icon btn-sm h-auto btn-color-gray-400 btn-active-color-primary"
            >
              <KTIcon icon-name="exit-right-corner" icon-class="fs-2" />
            </button>
          </div>
        </template>
        <div
          v-if="index !== groupedBookmarks.length - 1"
          class="separator separator-dashed my-2"
        ></div>
      </template>
      <div v-if="!groupedBookmarks.length" class="text-gray-500 text-center py-5">
        No favorites yet.
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref, computed, onMounted, onBeforeUnmount } from "vue";
import type { Bookmark } from "~/types/bookmark";

const props = defineProps({
  className: { type: String, default: "" },
});

const LS_KEY = "report_bookmarks";
const bookmarks = ref<Bookmark[]>([]);

const loadBookmarks = (): Bookmark[] => {
  try {
    const stored = localStorage.getItem(LS_KEY);
    return stored ? JSON.parse(stored) : [];
  } catch {
    return [];
  }
};

const updateBookmarks = () => {
  bookmarks.value = loadBookmarks();
};

const clearBookmarks = () => {
  localStorage.removeItem(LS_KEY);
  updateBookmarks();
};

const handleStorageChange = (event: StorageEvent) => {
  if (event.key === LS_KEY) updateBookmarks();
};

onMounted(() => {
  updateBookmarks();
  window.addEventListener("storage", handleStorageChange);
});

onBeforeUnmount(() => {
  window.removeEventListener("storage", handleStorageChange);
});

const groupedBookmarks = computed(() => {
  const grouped: Record<string, Bookmark[]> = {};
  for (const b of bookmarks.value) {
    const group = b.group || "Ungrouped";
    if (!grouped[group]) grouped[group] = [];
    grouped[group].push(b);
  }
  return Object.entries(grouped).map(([name, items]) => ({ name, items }));
});

const redirect = async (bookmark: Bookmark) => {
  const sharedData = useState<Bookmark>("bookmarkData", () => bookmark);
  sharedData.value = bookmark;
  await navigateTo("/reports");
};
</script>
