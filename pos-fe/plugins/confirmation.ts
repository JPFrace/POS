import { ElMessageBox } from "element-plus";
import type { ElMessageBoxOptions } from "element-plus";
import type { Component, VNode } from "vue";

export default defineNuxtPlugin((nuxtApp) => {
    /*
    available properties
    interface Props {
        title: string;
        message: string | VNode;
        type: "success" | "warning" | "info" | "error";
        beforeClose: Function;
        showClose: Function;
        onClose: Function;
        onClick: Function;
        showCancelButton: boolean;
        showConfirmButton: boolean;
        cancelButtonText: string;
        confirmButtonText: string;
        cancelButtonLoadingIcon: string | Component;
        confirmButtonLoadingIcon: string | Component;
    }
    */

    const confirm = (
        type: ElMessageBoxOptions["type"],
        title: ElMessageBoxOptions["title"],
        message: ElMessageBoxOptions["message"],
        props?: Partial<ElMessageBoxOptions>
    ) => {
        return ElMessageBox.confirm(message, title, {
            ...props,
            confirmButtonText: "OK",
            cancelButtonText: "Cancel",
            type,
        });
    };

    return {
        provide: {
            confirm,
        },
    };
});
