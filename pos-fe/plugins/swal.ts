import Swal from "sweetalert2/dist/sweetalert2.js";

import type { Action } from "~/types/common";

export default defineNuxtPlugin((nuxtApp) => {
    interface Props {
        title?: string;
        text?: string;
        html?: string;
        titleText?: string;
        buttonsStyling?: boolean;
        heightAuto?: boolean;
        customClass?: object | null;
        confirmButtonText?: string | null;
        denyButtonText?: string | null;
        cancelButtonText?: string | null;
        showConfirmButton?: boolean;
        showDenyButton?: boolean;
        showCancelButton?: boolean;
        allowEscapeKey?: boolean;
        allowOutsideClick?: boolean;
        reverseButtons?: boolean;
        inputLabel?: string | null;
        inputPlaceholder?: string | null;
        input?: "text" | null;
        preConfirm?: Function | null;
    }

    const swal = (
        icon: Action["type"] | "loading",
        {
            showConfirmButton = true,
            showDenyButton = false,
            showCancelButton = false,
            buttonsStyling = false,
            allowEscapeKey = false,
            allowOutsideClick = false,
            reverseButtons = true,
            customClass = {
                confirmButton: "btn fw-semibold btn-light-primary",
                cancelButton: "btn fw-semibold btn-light",
                denyButton: "btn fw-semibold btn-light-danger",
                title: "text-2xl",
            },
            ...props
        }: Props
    ) => {
        if (icon === "loading") {
            return Swal.fire({
                ...props,
                allowEscapeKey: false,
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });
        }
        return Swal.fire({
            ...props,
            icon,
            buttonsStyling,
            customClass,
            showConfirmButton,
            showDenyButton,
            showCancelButton,
            allowEscapeKey,
            allowOutsideClick,
            reverseButtons,
        });
    };

    return {
        provide: {
            swal,
        },
    };
});
