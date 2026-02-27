<template>
    <div>
        <NuxtLayout>
            <template #toolbarLeft>
                <AppPageSearch query="name_code" no-options prefix="all" />
            </template>
            <template #toolbarRight>
                <div class="space-x-standard">
                    <AppPageAdd
                        endpoint="/api/accounting/chart-accounts"
                        width="60%"
                        width-lg="30%"
                        @before-show="send('clear')"
                    >
                        <template #drawerFooter="{ submit, cancel }">
                            <Button
                                variant="light"
                                class="ms-auto btn btn-light fw-semibold"
                                icon="black-left"
                                @click="cancel(() => send('clear'))"
                            >
                                <span>Cancel</span>
                            </Button>
                            <Button
                                variant="primary"
                                class="btn btn-primary fw-semibold"
                                icon="add-folder"
                                @click="submit(() => send('all'))"
                            >
                                <span>Submit</span>
                            </Button>
                        </template>
                        <template #form="{ errors, form, schema }">
                            <Form
                                :errors="errors"
                                :form="form"
                                :schema="schema"
                            />
                        </template>
                    </AppPageAdd>
                </div>
            </template>
            <div class="">
                <ul class="mb-5 nav nav-tabs nav-line-tabs fs-6">
                    <li class="nav-item">
                        <a
                            v-tab="`all`"
                            class="uppercase nav-link active"
                            data-bs-toggle="tab"
                            href="#kt_tab_all"
                            @click="tabClick('all')"
                            >All</a
                        >
                    </li>
                    <li v-for="category in categories" class="nav-item">
                        <a
                            class="uppercase nav-link"
                            data-bs-toggle="tab"
                            :href="`#kt_tab_${category.uuid}`"
                            @click="tabClick(category.uuid)"
                            >{{ category.name }}</a
                        >
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <div
                        class="tab-pane active show"
                        id="kt_tab_all"
                        role="tabpanel"
                    >
                        <TabContent />
                    </div>
                    <div
                        v-for="category in categories"
                        class="tab-pane"
                        :id="`kt_tab_${category.uuid}`"
                        role="tabpanel"
                    >
                        <TabContent :category="category" />
                    </div>
                </div>
            </div>
        </NuxtLayout>
    </div>
</template>

<script lang="ts" setup>
import TabContent from "~/features/accounting/chart-of-accounts/tab-content.vue";
import Form from "~/features/accounting/chart-of-accounts/form.vue";
import type { DirectiveBinding, VNode } from "vue";
import { usePageTitle } from "~/composables/usePageTitle";

usePageTitle();

definePageMeta({
    permission: "Accounting.Chart of Accounts.View",
});

const { send, receive } = usePageEvent();
const route = useRoute();
const currentTab = ref("all");

const { data: dataCategories } = useAsyncData(
    `${route.path}_categories`,
    () =>
        useClient("/api/accounting/account-categories", {
            method: "GET",
        }),
    {
        server: false,
        lazy: true,
    },
);

const vTab = {
    mounted(
        el: HTMLElement,
        binding: DirectiveBinding,
        vnode: VNode<any, any>,
    ) {
        receive(`${binding.value}search`, (data: any) => {
            console.log("receiver of" + binding.value);
            el.click();
        });
    },
};

const tabClick = (category: string) => {
    send(category);
    currentTab.value = category;
};

const categories = computed(() => dataCategories.value?.data ?? []);
</script>
