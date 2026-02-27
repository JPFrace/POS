<template>
    <div
        :class="`card shadow-sm ${classNames(
            flush,
            resetSidePaddings,
            bordered,
            dashed,
            linkableClasses
        )}`"
    >
        <div
            v-if="!noHeader"
            :class="`card-header ${classNames(collapsibleClasses)}`"
            :data-bs-toggle="collapsible ? 'collapse' : null"
            :data-bs-target="collapsible ? '#' + collapsibleId : null"
        >
            <h3 class="card-title">{{ title }}</h3>
            <div
                class="card-toolbar"
                :class="{
                    'rotate-180': collapsible,
                }"
            >
                <i v-if="collapsible" class="ki-duotone ki-down fs-1" />
                <slot v-if="!collapsible" name="toolbar" />
                <span
                    v-if="removable && !collapsible"
                    class="btn btn-icon btn-sm btn-active-color-primary cursor-pointer"
                    data-kt-card-action="remove"
                    data-kt-card-confirm="true"
                    data-kt-card-confirm-message="Are you sure to remove this card ?"
                    data-bs-toggle="tooltip"
                    title="Remove card"
                    data-bs-dismiss="click"
                >
                    <i class="ki-duotone ki-cross fs-1"
                        ><span class="path1" /><span class="path2"
                    /></i>
                </span>
            </div>
        </div>
        <!-- Collapsible -->
        <div v-if="collapsible" :id="collapsibleId" :class="`collapse show `">
            <div class="card-body" :class="bodyClasses"><slot /></div>
            <div v-if="!noFooter" class="card-footer">
                <slot name="footer" />
            </div>
        </div>

        <!-- Default -->
        <div
            v-if="!collapsible"
            :class="`card-body ${classNames(height, bodyClasses)}`"
        >
            <slot />
        </div>
        <div v-if="!collapsible && $slots.footer" class="card-footer">
            <slot name="footer" />
        </div>
    </div>
</template>

<script lang="ts" setup>
import { computed, onMounted } from "vue";
interface Props {
    title: string;
    scrollable: boolean;
    collapsible: boolean;
    linkable: boolean;
    removable: boolean;
    flush: boolean;
    resetSidePaddings: boolean;
    bordered: boolean;
    dashed: boolean;
    height: string;
    noHeader: boolean;
    noFooter: boolean;
    bodyClasses: string;
}

const props = withDefaults(defineProps<Partial<Props>>(), {});

const height = computed(() =>
    props.height && props.scrollable ? props.height : ""
);

const scrollable = computed(() => (props.scrollable ? " card-scroll" : ""));

const flush = computed(() => (props.flush ? "card-flush" : ""));

const resetSidePaddings = computed(() =>
    props.resetSidePaddings ? "card-px-0" : ""
);

const bordered = computed(() => (props.bordered ? "card-bordered" : ""));

const dashed = computed(() => (props.dashed ? "card-dashed" : ""));

const collapsibleClasses = computed(() =>
    props.collapsible ? " collapsible  cursor-pointer rotate" : ""
);

const collapsibleId = computed(
    () =>
        props?.title?.replace(/\s/, "-").toLowerCase() +
        Math.random().toString().replace(".", "")
);

const linkableClasses = computed(() =>
    props.linkable ? "hover-elevate-up parent-hover" : ""
);
</script>
