import { ElMessage } from "element-plus";
import type { MessageOptions } from "element-plus";

export default defineNuxtPlugin((nuxtApp) => {
    /*
    available properties
    interface Props {
        message: string | VNode;
        type: "success" | "warning" | "info" | "error";
    }
    */

    const message = (
        type: MessageOptions["type"],
        message: MessageOptions["message"],
        props?: Partial<MessageOptions>
    ) => {
        return ElMessage({ ...props, type, message });
    };

    return {
        provide: {
            message,
        },
    };
});
