<template>
    <div>
        <Button
            id="drawer_toggle"
            data-bs-dismiss="click"
            data-bs-trigger="hover"
            >Drawer</Button
        >
        <Drawer id="drawer" title="Drawer" description="Description">
            <p>Drawer Contents</p>
        </Drawer>
    </div>
    <div>
        <Button
            id="custom_drawer_toggle"
            data-bs-dismiss="click"
            data-bs-trigger="hover"
            >Custom Drawer</Button
        >
        <Drawer id="custom_drawer">
            <template #title>
                <h3 class="card-title fs-3 fw-bold text-white flex-column m-0">
                    <span class="flex gap-x-2 items-center justify-start"
                        ><i class="fa-brands fa-facebook text-lg" /> Custom
                        Title</span
                    >
                    <small class="text-white opacity-50 fs-7 fw-semibold pt-1"
                        >Custom Description</small
                    >
                </h3>
            </template>
            <template #footer>
                <Button
                    variant="light"
                    class="btn btn-light flex-grow-1 fw-semibold"
                >
                    <!--begin::Indicator label-->
                    <span class="indicator-label">Cancel</span>
                    <!--end::Indicator label-->
                    <!--begin::Indicator progress-->
                    <span class="indicator-progress"
                        >Please wait...
                        <span
                            class="spinner-border spinner-border-sm align-middle ms-2"
                        /></span>
                    <!--end::Indicator progress-->
                </Button>
                <Button
                    variant="success"
                    class="btn btn-primary flex-grow-1 fw-semibold"
                >
                    <!--begin::Indicator label-->
                    <span class="indicator-label">Update</span>
                    <!--end::Indicator label-->
                </Button>
            </template>
            <p>Drawer Contents</p>
        </Drawer>
    </div>
</template>
