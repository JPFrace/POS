<template>
    <el-dropdown-item @click="handlePost">
    <el-icon class="!text-green-600" :size="18"><Position /></el-icon
    >Post</el-dropdown-item
  >
  </template>
  
  <script setup lang="ts">
  import { Position } from "@element-plus/icons-vue";
  
  const { t } = useI18n();
  const { $message, $swal } = useNuxtApp();
  
  interface Props {
    selectedRows: any[];
    onSuccess?: () => void;
  }
  
  const props = defineProps<Props>();
  
  const handlePost = async () => {
    if (!props.selectedRows.length) {
      $message("warning", t("journal.validation.no_entry"));
      return;
    }
  
    const alreadyPosted = props.selectedRows.filter(
      (row) => row.status.name.toLowerCase() === "posted",
    );
  
    if (alreadyPosted.length > 0) {
      $message(
        "error",
        `Cannot post: ${alreadyPosted.length} ${alreadyPosted.length === 1 ? "entry is" : "entries are"} already posted.`,
      );
      return;
    }
  
    const result = await $swal("warning", {
      text: `Are you sure you want to post ${props.selectedRows.length} ${props.selectedRows.length === 1 ? "entry" : "entries"}?`,
      showCancelButton: true,
      confirmButtonText: "Post",
      cancelButtonText: "Cancel",
      reverseButtons: false,
    });
  
    if (!result.isConfirmed) {
      $message("info", t("action.post_canceled"));
      return;
    }
  
    try {
      const uuids = props.selectedRows.map((row) => row.uuid);
  
      await useClient("/api/business/journal-entries/update-status", {
        method: "PATCH",
        body: {
          uuids,
          status: "posted",
        },
      });
  
      $message(
        "success",
        `${t("action.posted")} ${uuids.length} ${uuids.length === 1 ? "entry" : "entries"}.`,
      );
  
      props.onSuccess?.();
    } catch (error: any) {
      $message(
        "error",
        error?.data?.message ??
          t("journal.error.failed_to_post", props.selectedRows.length),
      );
    }
  };
  </script>