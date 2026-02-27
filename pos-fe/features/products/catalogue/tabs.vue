<template>
    <div>
        <el-menu
            :default-active="activeTab"
            mode="horizontal"
            @select="activeTab = $event"
        >
            <el-menu-item index="general">General</el-menu-item>
            <el-menu-item index="tax-settings">Tax Settings</el-menu-item>
        </el-menu>

        <div class="pt-8">
            <Form :errors :form :schema :data :active-tab="activeTab" />
        </div>
    </div>
</template>

<script lang="ts" setup>
import Form from "~/features/products/catalogue/form.vue";

interface Props {
    errors?: any;
    form: Function;
    schema: Function;
    data?: any;
}

defineProps<Props>();

const activeTab = ref("general");
</script>
