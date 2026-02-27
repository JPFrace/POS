<template>
    <el-dialog
        v-model="open"
        :title="props.title"
        :width="props.width"
        :before-close="handler"
    >
        <slot name="body" />
        <template #footer>
            <slot name="footer" />
        </template>
    </el-dialog>
</template>
<script lang="ts" setup>
import { watch, onMounted } from "vue";
interface Props {
    tabindex?: number;
    id?: string;
    title?: number | string;
    width?: string | number;
    centered?: boolean;
    scrollable?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    tabindex: -1,
    width: "500px",
    centered: false,
    scrollable: false,
});

const open = defineModel("open", {
    default: false,
    type: Boolean,
});

const centered = computed(() =>
    props.centered ? "modal-dialog-centered" : "",
);

const handler = () => {
    open.value = false;
};

const scrollable = computed(() =>
    props.scrollable ? "modal-dialog-scrollable" : "",
);

watch(open, (value) => console.log(value));

onMounted(() => {
    console.log(props.centered);
});
</script>
