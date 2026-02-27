<template>
    <div class="flex flex-col gap-y-4">
        <div class="flex flex-col gap-y-2">
            <h5>Basic</h5>
            <Alert :title="data.title" :content="data.content" />
            <Alert
                variant="success"
                :title="data.title"
                :content="data.content"
            />
        </div>
        <div class="flex flex-col gap-y-2">
            <h5>Solid</h5>
            <Alert solid :title="data.title" :content="data.content" />
            <Alert
                solid
                variant="danger"
                :title="data.title"
                :content="data.content"
            />
        </div>
        <div class="flex flex-col gap-y-2">
            <h5>Light</h5>
            <Alert light :title="data.title" :content="data.content" />
            <Alert
                light
                variant="warning"
                :title="data.title"
                :content="data.content"
            />
        </div>
        <div class="flex flex-col gap-y-2">
            <h5>Bordered</h5>
            <Alert
                bordered
                solid
                variant="success"
                :title="data.title"
                :content="data.content"
            />
        </div>
        <div class="flex flex-col gap-y-2">
            <h5>Custom</h5>
            <Alert solid>
                <!-- optional -->
                <template #icon>
                    <i
                        class="ki-duotone ki-search-list text-white fs-2hx me-4 mb-5 mb-sm-0"
                        ><span class="path1"/><span class="path2"/><span class="path3"/></i>
                </template>
                <!-- optional -->
                <template #title>
                    <h4 class="mb-2 light">Custom title</h4>
                </template>
                <!-- optional -->
                <template #content>
                    <span>Custom content</span>
                </template>
            </Alert>
        </div>
    </div>
</template>
<script lang="ts" setup>
import { ref } from "vue";

interface Props {
    title: string;
    content: string;
}

const data = ref<Props>({
    title: "This is an alert",
    content:
        "The alert component can be used to highlight certain parts of your page for higher content visibility.",
});
</script>
