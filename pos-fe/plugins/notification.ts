import { ElNotification } from "element-plus";
import type { NotificationOptions } from "element-plus";

export default defineNuxtPlugin((nuxtApp) => {
    /*
    These are the available props
    interface Props {
        title: string;
        message: string;
        dangerouslyUseHTMLString: boolean;
        type: "success" | "warning" | "info" | "error";
        icon: string | Component;
        customClass: string;
        position: "top-right" | "top-left" | "bottom-right" | "bottom-left";
        showClose: Function;
        onClose: Function;
        onClick: Function;
        offset: number;
        duration: number;
        appendTo: HTMLElement;
        zIndex: number;
        close: Function
    }
    */

    const notify = (
        type: NotificationOptions["type"],
        title: NotificationOptions["title"],
        message: NotificationOptions["message"],
        props?: Partial<NotificationOptions>
    ) => {
        ElNotification({
            ...props,
            title,
            message,
            type,
        });
    };

    return {
        provide: {
            success: (
                title: string,
                message: string,
                props?: Partial<NotificationOptions>
            ) => notify("success", title, message, props),
            warning: (
                title: string,
                message: string,
                props?: Partial<NotificationOptions>
            ) => notify("warning", title, message, props),
            info: (
                title: string,
                message: string,
                props?: Partial<NotificationOptions>
            ) => notify("info", title, message, props),
            error: (
                title: string,
                message: string,
                props?: Partial<NotificationOptions>
            ) => notify("error", title, message, props),
        },
    };
});
