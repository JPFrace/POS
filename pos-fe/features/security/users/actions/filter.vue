<template>
    <el-popover
        :visible="visible"
        :width="300"
        popper-style="box-shadow: rgb(14 18 22 / 35%) 0px 10px 38px -10px, rgb(14 18 22 / 20%) 0px 10px 20px -15px; padding: 20px;"
    >
        <template #reference>
            <KTIcon
                title="More filter options"
                icon-name="filter-square"
                icon-class="fs-4x cursor-pointer hover:dark:text-white hover:text-slate-900"
                @click="visible = !visible"
            />
        </template>
        <template #default>
            <h3>Filter options</h3>
            <div class="flex flex-col gap-y-4">
                <Input
                    v-model="form.email"
                    float
                    placeholder="Email address"
                    label="Email address"
                />
                <AppRoles v-model:selected="form.roles_uuids" />
            </div>
            <div class="flex items-center justify-end gap-x-4 mt-8">
                <Button variant="light" @click="cancel">Cancel / Clear</Button>
                <Button @click="search">Search</Button>
            </div>
        </template>
    </el-popover>
</template>
<script lang="ts" setup>
import type { Option } from "~/types/form";

const { $bus } = useNuxtApp();

const visible = ref(false);
const form = ref({
    email: "",
    roles_uuids: [],
});

const cancel = () => {
    form.value = clearKeyValue(form.value);
    $bus.emit("users:search", form.value);
    visible.value = false;
};

const search = () => {
    $bus.emit("users:search", {
        ...form.value,
        roles_uuids: form.value.roles_uuids.map((row: Option) => row.uuid),
    });
    visible.value = false;
};
</script>
