<template>
    <NuxtLayout>
        <template #toolbarLeft>
            <Input group placeholder="Search">
                <template #default>
                    <InputNative
                        v-model="search"
                        placeholder="Search transactions..."
                        title="Type and enter key to search"
                        @keyup.enter="handleSearch"
                    />
                </template>
                <template #append>
                    <div>
                        <KTIcon
                            title="Click me to search"
                            icon-name="click"
                            icon-class="fs-2 cursor-pointer"
                            @click="handleSearch"
                        />
                    </div>
                </template>
            </Input>
        </template>
        <template #toolbarRight>
            <FilterDaterange v-model="dates" />
        </template>
        <div>
            <!-- Pass UUID and search params to TableAccess -->
            <TableAccess
                :uuid="route.params.uuid"
                :search="searchQuery"
                :dateFilter="dateQuery"
            />
        </div>
    </NuxtLayout>
</template>

<script setup lang="ts">
import moment from "moment";
import FilterDaterange from "~/features/reports/filter-daterange.vue";
import TableAccess from "~/features/chart-accounts/table.vue";

import { ref, computed, watch } from "vue";
import { useRoute, useRouter } from "vue-router";
const end = moment().format("YYYY-MM-DD");
const start = moment().format("YYYY-MM-DD");

const route = useRoute();
const router = useRouter();
const dates = ref<[string, string] | null>(
    route.query.from && route.query.to
        ? [route.query.from as string, route.query.to as string]
        : null,
);
// input model
const search = ref<string>((route.query.search as string) || "");

// search value comes from URL
const searchQuery = computed(() => (route.query.search as string) || "");
const dateQuery = computed<[string, string]>(() => {
    const from = route.query.from as string | undefined;
    const to = route.query.to as string | undefined;

    if (!from || !to) return ["", ""];

    return [from, to];
});

// push search to URL
const handleSearch = () => {
    router.push({
        query: {
            ...route.query,
            search: search.value || undefined, // removes param if empty
        },
    });
};

// keep input synced when URL changes (back/forward)
watch(
    () => route.query.search,
    (val) => {
        search.value = (val as string) || "";
    },
);

watch(dateQuery, (val) => {
    console.log("dateQuery: ", val);
});
// 📅 push dates to URL (from / to)
watch(
    dates,
    (val) => {
        if (!val) {
            router.push({
                query: {
                    ...route.query,
                    from: undefined,
                    to: undefined,
                },
            });
            return;
        }

        const [from, to] = val;

        router.push({
            query: {
                ...route.query,
                from: moment(from).format("YYYY-MM-DD"),
                to: moment(to).format("YYYY-MM-DD"),
            },
        });
    },
    { deep: true },
);

// 🔁 restore dates when URL changes (back / forward / manual edit)
watch(
    () => [route.query.from, route.query.to],
    ([from, to]) => {
        if (from && to) {
            dates.value = [from as string, to as string];
        } else {
            dates.value = null;
        }
        console.log("dates: ", dates);
    },
);
</script>
