<template>
     <el-dropdown>
        <KTIcon
            title="More options"
            icon-name="dots-square-vertical"
            icon-type="outline"
            icon-class="!text-3xl cursor-pointer !text-gray-500 hover:!text-gray-700 dark:hover:!text-gray-400"
        />
        <template #dropdown>
            <el-dropdown-menu>
                <slot />
            </el-dropdown-menu>
        </template>
    </el-dropdown>
</template>