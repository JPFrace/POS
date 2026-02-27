<template>
    <AppPageMoreItem @click.stop="handleClick">
      <KTIcon
          icon-name="trash"
          icon-type="outline"
          :icon-class="iconClasses"
      />
      Delete
    </AppPageMoreItem>
  </template>
  
  <script lang="ts" setup>
  import type { Throwable } from "~/types/common";
  
  interface Props {
      id?: string;
      uuid: string;
      title: string;
      endpoint: string | Function;
      data?: object | null;
      after?: Function;
      disabled?: boolean;
      warningMessage?: string; 
  }
  
  const props = withDefaults(defineProps<Props>(), {
      disabled: false,
      warningMessage: '',
  });
  
  const key = id(useRoute().fullPath);
  const { t } = useI18n();
  const { $swal, $message } = useNuxtApp();
  const { send } = usePageEvent();
  
  const iconClasses = computed(() => {
      if (props.disabled) {
          return "!text-3xl cursor-not-allowed !text-gray-400";
      }
      return "!text-3xl cursor-pointer !text-red-500 hover:!text-red-700 dark:hover:!text-red-400";
  });
  
  const endpoint = (row: any) => {
      if (typeof props.endpoint === "function") {
          return props.endpoint(row);
      }
      return props.endpoint.trimEnd() + "/" + props.uuid;
  };
  
  const handleClick = () => {
      if (props.disabled) {
          if (props.warningMessage) {
              $message("warning", props.warningMessage);
          }
          return;
      }
  
      $swal("warning", {
          title: props.title,
          text: t("action.delete_confirm"),
          showCancelButton: true,
          showConfirmButton: true,
          confirmButtonText: "Delete",
          cancelButtonText: "Cancel",
          reverseButtons: false,
      })
          .then(async (res: any) => {
              if (res.isConfirmed) {
                  await useClient(endpoint(props.data), {
                      method: "DELETE",
                  });
  
                  $message("success", t("action.deleted"));
                  send(`refresh`, props.id);
  
                  if (typeof props.after === "function") {
                      props.after();
                  }
              } else if (res.isDismissed) {
                  $message("info", t("action.delete_canceled"));
              }
          })
          .catch((error: Throwable) => {
              $message("error", error?.message ?? t("error.failed_request"));
          });
  };
  </script>