<template>
    <div class="flex-column flex-lg-row-auto w-100 w-xl-300px mb-10 mt-4">
        <!--begin::Card-->
        <div class="card mb-5 mb-xl-8">
            <!--begin::Card body-->
            <div class="card-body pt-15">
                <!--begin::Summary-->
                <div class="d-flex flex-center flex-column mb-5">
                    <!--begin::Avatar-->
                    <input
                        ref="fileInput"
                        type="file"
                        accept="image/*"
                        class="hidden"
                        @change="handleAvatarChange"
                    />
                    <div
                        class="symbol symbol-100px symbol-circle mb-7 avatar-wrapper"
                        @click="fileInput?.click()"
                    >
                        <!--previewUrl for new and avatarUrl for current-->
                        <img
                            :src="previewUrl || avatarUrl"
                            alt="image"
                            class="avatar-img"
                        />
                        <div class="edit-icon">
                            <KTIcon
                                icon-name="user-edit"
                                icon-type="outline"
                                icon-class="fs-3 text-white"
                            />
                        </div>
                        <div
                            v-if="hasCustomAvatar"
                            class="remove-icon"
                            @click.stop="handleRemoveAvatar"
                        >
                            <KTIcon
                                icon-name="cross-circle"
                                icon-type="outline"
                                icon-class="fs-6 text-white"
                            />
                        </div>
                    </div>
                    <!--end::Avatar-->

                    <!--begin::Name-->
                    <span
                        class="w-100 fs-3 text-gray-800 fw-bold mb-1 d-block text-break text-center"
                    >
                        {{ displayName || "No name provided" }}
                    </span>
                    <!--end::Name-->

                    <!--begin::ID_No-->
                    <div class="badge badge-light-info d-inline fs-7 mb-6">
                        ID No:
                        <span>
                            {{ form.id_no || "N/A" }}
                        </span>
                    </div>
                    <!--end::ID_No-->
                </div>

                <!--begin::Details toggle-->
                <div class="d-flex flex-stack fs-4 py-3">
                    <button
                        type="button"
                        class="fw-bold btn btn-link p-0 text-start d-flex align-items-center"
                        @click="toggleDetails"
                    >
                        Details
                        <span
                            class="ms-2 arrow transition-transform"
                            :class="{ open: isOpen }"
                        >
                            <KTIcon icon-name="down" icon-class="fs-3" />
                        </span>
                    </button>
                </div>
                <!--end::Details toggle-->

                <div class="separator separator-dashed my-3"></div>

                <!--begin::Details content-->
                <transition name="slide-fade">
                    <div v-if="isOpen" ref="detailsRef" class="py-1 fs-6">
                        <div class="fw-bold mt-5">Sub Type</div>
                        <div class="text-gray-600">
                            {{ form.sub_type.label }}
                        </div>
                        <div class="fw-bold mt-5">Classification</div>
                        <div class="text-gray-600">
                            {{ form.class.label }}
                        </div>
                        <div class="fw-bold mt-5">Email Address</div>
                        <div class="text-gray-600">
                            {{ form.email || "No email provided" }}
                        </div>
                        <div class="fw-bold mt-5">Billing Address</div>
                        <div class="text-gray-600">
                            {{ form.billing_address || "No address provided" }}
                        </div>
                        <div class="fw-bold mt-5">Contact No.</div>
                        <div class="text-gray-600">
                            {{
                                form.contact_number || "No contact no. provided"
                            }}
                        </div>
                        <div class="fw-bold mt-5">Country</div>
                        <div class="text-gray-600">
                            {{ form.country?.label || "No country provided" }}
                        </div>
                        <div class="fw-bold mt-5">Tax Code</div>
                        <div class="text-gray-600">
                            {{ form.tax?.code || "No tax provided" }}
                        </div>
                    </div>
                </transition>
                <!--end::Details content-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
</template>

<script lang="ts" setup>
const props = defineProps<{
    form: any;
    avatarUrl: string;
    displayName: string;
}>();

const emit = defineEmits<{
    "update:file": [file: File | null];
}>();

const { form, avatarUrl, displayName } = toRefs(props);

const isOpen = ref(false);
const detailsRef = ref<HTMLElement | null>(null);
const fileInput = ref<HTMLInputElement | null>(null);
const previewUrl = ref<string | null>(null);

const DEFAULT_AVATAR = "/media/avatars/blank.png";

const hasCustomAvatar = computed(() => {
    if (previewUrl.value) return true;

    return avatarUrl.value !== DEFAULT_AVATAR;
});

function toggleDetails() {
    isOpen.value = !isOpen.value;
    if (isOpen.value) {
        nextTick(() => {
            detailsRef.value?.scrollIntoView({
                behavior: "smooth",
                block: "start",
            });
        });
    }
}

function handleAvatarChange(event: Event) {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];

    if (file) {
        // Clean up old preview if exists
        if (previewUrl.value) {
            URL.revokeObjectURL(previewUrl.value);
        }

        previewUrl.value = URL.createObjectURL(file);
        emit("update:file", file);
    }
}

function handleRemoveAvatar() {
    if (previewUrl.value) {
        URL.revokeObjectURL(previewUrl.value);
        previewUrl.value = null;
    }

    if (fileInput.value) {
        fileInput.value.value = "";
    }

    emit("update:file", null);
}

onUnmounted(() => {
    if (previewUrl.value) {
        URL.revokeObjectURL(previewUrl.value);
    }
});
</script>

<style scoped>
/* Details arrow */
.arrow {
    display: inline-block;
    transition: transform 0.3s ease;
    transform: rotate(0deg); /* closed = up */
}

.arrow.open {
    transform: rotate(180deg); /* open = down */
}

/* Details content */
.slide-fade-enter-active,
.slide-fade-leave-active {
    transition: all 0.3s ease;
}
.slide-fade-enter-from,
.slide-fade-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
.slide-fade-enter-to,
.slide-fade-leave-from {
    opacity: 1;
    transform: translateY(0);
}

.avatar-wrapper {
    position: relative;
    display: inline-block;
    cursor: pointer;
}

.avatar-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Avatar */
.edit-icon {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%); /* centers the icon */
    background-color: rgba(0, 0, 0, 0.6);
    border-radius: 50%;
    width: 27px;
    height: 27px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 6px;
    opacity: 0;
    transition: opacity 0.2s ease;
    cursor: pointer;
}

.avatar-wrapper:hover .edit-icon {
    opacity: 1;
}

.remove-icon {
    position: absolute;
    top: 4px;
    right: -2px;
    background-color: rgba(0, 0, 0, 0.6);
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 6px;
    opacity: 0;
    transition: opacity 0.2s ease;
    cursor: pointer;
}

.avatar-wrapper:hover .remove-icon {
    opacity: 1;
}
</style>
