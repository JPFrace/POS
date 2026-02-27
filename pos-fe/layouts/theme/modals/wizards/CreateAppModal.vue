<template>
    <div
        id="kt_modal_create_app"
        ref="modalRef"
        class="modal fade"
        tabindex="-1"
        aria-hidden="true"
    >
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-900px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header">
                    <!--begin::Modal title-->
                    <h2>Create App</h2>
                    <!--end::Modal title-->

                    <!--begin::Close-->
                    <div
                        class="btn btn-sm btn-icon btn-active-color-primary"
                        data-bs-dismiss="modal"
                    >
                        <KTIcon icon-name="cross" icon-class="fs-1" />
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->

                <!--begin::Modal body-->
                <div class="modal-body py-lg-10 px-lg-10">
                    <CreateAppWizardStepper :close-hadler="closeModal" />
                </div>
                <!--end::Modal body-->
            </div>
            <!--end::Modal content-->
        </div>
        <!--end::Modal dialog-->
    </div>
</template>

<script setup lang="ts">
import CreateAppWizardStepper from "~/layouts/theme/wizards/create-app-wizard/CreateAppWizardStepper.vue";
import { hideModal } from "~/core/helpers/modal";
import { ref } from "vue";

const modalRef = ref<HTMLElement | null>(null);

const closeModal = () => {
    hideModal(modalRef.value);
};
</script>
