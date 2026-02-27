<template>
    <div>
        <NuxtLayout>
            <Profile v-model="form" :details="data?.data" @updated="refresh" />
        </NuxtLayout>
    </div>
</template>

<script lang="ts" setup>
import Profile from "~/features/setup/company/form.vue";
import { usePageTitle } from "~/composables/usePageTitle";
import type { Company } from "~/types/company";

usePageTitle();
definePageMeta({
    permission: "Setup.Company.View",
});

const form = ref<Company>({
    uuid: "",
    name: "",
    tin_no: "",
    address: "",
    phone: "",
    email: "",
    file: undefined,
});

const client = useSanctumClient();
const route = useRoute();
const { data, refresh, status } = useAsyncData(
    `${id(route.fullPath)}.company`,
    () =>
        client("/api/setup/company", {
            method: "GET",
            params: {
                query: {
                    file: true,
                },
            },
        }),
    {
        server: false,
        lazy: true,
        immediate: true,
    },
);
watch(data, () => {
    const company = data?.value?.data;
    form.value = {
        uuid: company.uuid,
        name: company.name,
        tin_no: company.tin_no,
        address: company.address,
        phone: company.phone,
        email: company.email,
        file: company.file,
    };
});
</script>
