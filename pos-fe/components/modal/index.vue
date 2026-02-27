<template>
    <div :id="id" class="modal fade" :tabindex="tabindex">
        <div :class="`modal-dialog ${classNames(centered, scrollable)}`">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 v-if="!$slots.title" class="modal-title">
                        {{ title }}
                    </h3>
                    <slot name="title" />

                    <!--begin::Close-->
                    <div
                        class="btn btn-icon btn-sm btn-active-light-primary ms-2"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                        @click="open = false"
                    >
                        <i class="ki-duotone ki-cross fs-1"
                            ><span class="path1"/><span class="path2"/></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <slot name="body" />
                </div>

                <div class="modal-footer">
                    <slot name="footer" />
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { watch, onMounted } from "vue";
interface Props {
    tabindex?: number;
    id?: string;
    title?: number | string;
    centered?: boolean;
    scrollable?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    tabindex: -1,
    centered: false,
    scrollable: false,
});

const open = defineModel("open");

const centered = computed(() =>
    props.centered ? "modal-dialog-centered" : ""
);

const scrollable = computed(() =>
    props.scrollable ? "modal-dialog-scrollable" : ""
);

watch(open, (value) => console.log(value));

onMounted(() => {
    console.log(props.centered);
});
</script>
