<template>
    <MenuComponent
        menu-selector="#kt-config-menu"
        v-if="!pending && data.length > 0"
    >
        <template #toggle>
            <div
                id="kt_header_config"
                class="header-config d-flex align-items-stretch"
                data-kt-menu-target="#kt-config-menu"
                data-kt-menu-trigger="click"
                data-kt-menu-attach="parent"
                data-kt-menu-placement="bottom-end"
                data-kt-menu-flip="bottom"
            >
                <div
                    id="kt_header_config_toggle"
                    class="d-flex align-items-center"
                >
                    <div
                        class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px"
                        title="Open Configurations"
                    >
                        <KTIcon
                            icon-name="setting-2"
                            icon-class="fs-2 text-gray-900"
                        />
                    </div>
                </div>
            </div>
        </template>

        <template #content>
            <div
                id="kt-config-menu"
                class="menu menu-sub menu-sub-dropdown menu-column p-7 w-325px w-md-375px"
                data-kt-menu="true"
            >
                <!-- <div class="flex items-center gap-2 mb-4">
                    <KTIcon
                        icon-name="setting-2"
                        icon-class="fs-2 text-gray-900"
                    />
                    <span class="font-semibold">{{ parentConfig?.name.toUpperCase() }} CONFIGURATIONS</span>
                </div> -->
                <div v-if="pending" class="text-center py-4">
                    Loading configs...
                </div>

                <div v-else class="space-y-6">
                    <ConfigNode
                        v-for="parent in reactiveTree"
                        :key="parent.uuid"
                        :node="parent"
                    />
                </div>
                <Placeholder />
                <div class="mt-4 flex justify-end">
                    <button
                        @click="saveAll"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
                        :disabled="saving"
                    >
                        {{ saving ? "Applying..." : "Apply" }}
                    </button>
                </div>
            </div>
        </template>
    </MenuComponent>
</template>

<script lang="ts" setup>
import { ref, reactive, computed, onMounted } from "vue";
import MenuComponent from "~/layouts/theme/menu/MenuComponent.vue";
import Placeholder from "./component/place-holders.vue";
import ConfigNode from "./config-node.vue";
import type { Config } from "~/types/config";

const route = useRoute();
const pending = ref(false);
const saving = ref(false);
const data = ref<Config[]>([]);
const reactiveTree = reactive<Config[]>([]);
const parentConfig = ref<Config | any>(null);

const pageTitle = computed(() => {
    return route.path
        .slice(1)
        .replace(/[\/-]/g, "_")
        .replace(String(route.params?.uuid ?? "").replace(/-/g, "_"), "")
        .replace(/_+/g, "_")
        .replace(/^_|_$/g, "")
        .toLowerCase();
});

function processConfig(c: any): Config {
    let value: any = c.value;
    let options: any[] = [];

    if (c.type?.startsWith("json:")) {
        if (typeof c.options === "string" && c.options.trim()) {
            try {
                options = JSON.parse(c.options);
            } catch {
                options = [];
            }
        } else if (Array.isArray(c.options)) {
            options = c.options;
        }
    }

    switch (c.type) {
        case "json:single":
            value = Array.isArray(value)
                ? (value[0] ?? "")
                : typeof value === "string"
                  ? value.trim()
                  : "";
            break;
        case "json:multi":
            if (typeof value === "string" && value.trim()) {
                try {
                    value = JSON.parse(value);
                } catch {
                    value = value.split(",").map((v: string) => v.trim());
                }
            } else if (!Array.isArray(value)) {
                value = [];
            }
            break;
        case "string":
            if (value == null) value = "";
            break;
        case "integer":
            if (value == null) value = 0;
            break;
        case "boolean":
            if (value == null) value = false;
            break;
    }

    return {
        ...c,
        value,
        options,
        children: (c.children || []).map(processConfig), // recursive normalize
    };
}

function flattenConfigs(configs: Config[]): Partial<Config>[] {
    const result: Partial<Config>[] = [];

    function recurse(node: Config) {
        if (node.type !== "none") {
            let value: any = node.value;

            if (node.type === "boolean") {
                value = node.value === "1" ? 1 : 0;
            }

            if (node.type === "json:multi" && Array.isArray(value)) {
                value = JSON.stringify(value);
            }

            const payload: Partial<Config> = {
                uuid: node.uuid,
                value,
                use_prefix: !!node.use_prefix,
                use_suffix: !!node.use_suffix,
            };

            if (node.use_prefix) {
                payload.prefix = node.prefix;
            }

            if (node.use_suffix) {
                payload.suffix = node.suffix;
            }

            result.push(payload);
        }

        if (node.children?.length) {
            node.children.forEach(recurse);
        }
    }

    configs.forEach(recurse);
    return result;
}

async function fetchConfigs() {
    pending.value = true;
    try {
        const slug = pageTitle.value;
        const res: any = await useClient(`/api/setup/config`, {
            method: "GET",
            params: { query: { parent_slug: slug } },
        });

        const items = res?.data ?? res;

        if (!items?.length) {
            data.value = [];
            reactiveTree.splice(0);
            return;
        }

        parentConfig.value = items.find(
            (c: any) => c.slug.toLowerCase() === slug,
        );
        if (!parentConfig.value) {
            data.value = [];
            reactiveTree.splice(0);
            return;
        }

        const processedRoot = processConfig(parentConfig.value);

        data.value = [processedRoot];
        reactiveTree.splice(0, reactiveTree.length, processedRoot);

        localStorage.setItem("config", JSON.stringify(processedRoot));
    } catch (error) {
        console.error("Error fetching configs:", error);
        data.value = [];
        reactiveTree.splice(0);
    } finally {
        pending.value = false;
    }
}

async function saveAll() {
    try {
        saving.value = true;
        const configsToSave = flattenConfigs(reactiveTree);
        await useClient(`/api/setup/config/${uuid()}`, {
            method: "PUT",
            body: configsToSave,
        });
        await fetchConfigs();
    } finally {
        saving.value = false;
    }
}

onMounted(fetchConfigs);
</script>
