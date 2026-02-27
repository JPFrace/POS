<template>
    <el-table>
        <template #default>
            <slot />
        </template>
        <template v-if="$slots.append" #append>
            <slot name="append" />
        </template>
        <template v-if="$slots.empty" #empty>
            <slot name="empty" />
        </template>
    </el-table>
</template>
