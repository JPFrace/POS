<template>
    <el-dropdown>
        <KTIcon
            title="More options"
            icon-name="dots-square-vertical"
            icon-class="!text-3xl cursor-pointer !text-gray-500 hover:!text-gray-700 dark:hover:!text-gray-400"
            icon-type="outline"
        />
        <template #dropdown>
            <el-dropdown-menu>
                <el-dropdown-item>
                    <KTIcon
                        icon-name="toggle-on-circle"
                        icon-class="fs-2 cursor-pointer hover:dark:text-white hover:text-slate-700"
                    />
                    Deactivate</el-dropdown-item
                >
                <el-dropdown-item @click="openDrawer">
                    <KTIcon
                        icon-name="password-check"
                        icon-class="fs-2 cursor-pointer hover:dark:text-white hover:text-slate-700"
                    />Change Password
                </el-dropdown-item>
                <el-dropdown-item
                    ><KTIcon
                        icon-name="key-square"
                        icon-class="fs-2 cursor-pointer hover:dark:text-white hover:text-slate-700"
                    />Block</el-dropdown-item
                >
            </el-dropdown-menu>
        </template>
    </el-dropdown>
    <PasswordReset :data="props.data" :drawer="drawer" />
</template>

<script lang="ts" setup>
import { data } from "autoprefixer";
import PasswordReset from "./password-reset.vue";
import type { User } from "~/types/user";
interface Props {
    data: User;
}

const { $drawer } = useNuxtApp();
const drawer = ref();

const props = defineProps<Props>();

const openDrawer = () => {
    drawer.value.show();
};

onMounted(() => {
    drawer.value = $drawer(`change_pass_${props.data.uuid}`);
});
</script>
