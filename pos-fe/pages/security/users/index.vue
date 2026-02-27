<template>
    <div>
        <NuxtLayout>
            <template #toolbarLeft>
                <Search />
            </template>
            <template #toolbarRight>
                <div class="space-x-standard">
                    <AddNew />
                    <Delete />
                </div>
            </template>
            <div>
                <Table />
            </div>
        </NuxtLayout>
    </div>
</template>

<script lang="ts" setup>
import AddNew from "~/features/security/users/actions/add.vue";
import Delete from "~/features/security/users/actions/delete-sel.vue";
import Search from "~/features/security/users/actions/search.vue";
import Table from "~/features/security/users/components/table.vue";
import { usePageTitle } from "~/composables/usePageTitle";

usePageTitle();
// definePageMeta({
//     permission: "Security.Users.View",
// });
</script>
