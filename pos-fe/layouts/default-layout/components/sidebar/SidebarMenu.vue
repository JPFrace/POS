<template>
    <!--begin::sidebar menu-->
    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <!--begin::Menu wrapper-->
        <div
            id="kt_app_sidebar_menu_wrapper"
            class="app-sidebar-wrapper hover-scroll-overlay-y my-5"
            data-kt-scroll="true"
            data-kt-scroll-activate="true"
            data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
            data-kt-scroll-wrappers="#kt_app_sidebar_menu"
            data-kt-scroll-offset="5px"
            data-kt-scroll-save-state="true"
        >
            <!--begin::Menu-->
            <div
                id="#kt_app_sidebar_menu"
                class="menu menu-column menu-rounded menu-sub-indention px-3"
                data-kt-menu="true"
            >
                <template v-for="(item, i) in MainMenuConfig" :key="i">
                    <template v-if="hasPagePermissions(item.pages)">
                        <div v-if="item.heading" class="menu-item pt-5">
                            <div class="menu-content">
                                <span
                                    class="menu-heading fw-bold text-uppercase fs-7"
                                >
                                    {{ translate(item.heading) }}
                                </span>
                            </div>
                        </div>
                    </template>
                    <template v-for="(menuItem, j) in item.pages" :key="j">
                        <template
                            v-if="hasPermission(menuItem.permissions ?? '')"
                        >
                            <template v-if="menuItem.heading">
                                <div class="menu-item">
                                    <NuxtLink
                                        v-if="menuItem.route"
                                        class="menu-link"
                                        active-class="active"
                                        :to="menuItem.route"
                                    >
                                        <span
                                            v-if="
                                                menuItem.keenthemesIcon ||
                                                menuItem.bootstrapIcon
                                            "
                                            class="menu-icon"
                                        >
                                            <i
                                                v-if="
                                                    sidebarMenuIcons ===
                                                    'bootstrap'
                                                "
                                                :class="menuItem.bootstrapIcon"
                                                class="bi fs-3"
                                            />
                                            <KTIcon
                                                v-else-if="
                                                    sidebarMenuIcons ===
                                                    'keenthemes'
                                                "
                                                :icon-name="
                                                    menuItem.keenthemesIcon
                                                "
                                                icon-class="fs-2"
                                            />
                                        </span>
                                        <span class="menu-title">{{
                                            translate(menuItem.heading)
                                        }}</span>
                                    </NuxtLink>
                                </div>
                            </template>
                            <div
                                v-if="menuItem.sectionTitle && menuItem.route"
                                :class="{
                                    show: hasActiveChildren(menuItem.route),
                                }"
                                class="menu-item menu-accordion"
                                data-kt-menu-sub="accordion"
                                data-kt-menu-trigger="click"
                            >
                                <span class="menu-link">
                                    <span
                                        v-if="
                                            menuItem.keenthemesIcon ||
                                            menuItem.bootstrapIcon
                                        "
                                        class="menu-icon"
                                    >
                                        <i
                                            v-if="
                                                sidebarMenuIcons === 'bootstrap'
                                            "
                                            :class="menuItem.bootstrapIcon"
                                            class="bi fs-3"
                                        />
                                        <KTIcon
                                            v-else-if="
                                                sidebarMenuIcons ===
                                                'keenthemes'
                                            "
                                            :icon-name="menuItem.keenthemesIcon"
                                            icon-class="fs-2"
                                        />
                                    </span>
                                    <span class="menu-title">{{
                                        translate(menuItem.sectionTitle)
                                    }}</span>
                                    <span class="menu-arrow" />
                                </span>
                                <div
                                    :class="{
                                        show: hasActiveChildren(menuItem.route),
                                    }"
                                    class="menu-sub menu-sub-accordion"
                                >
                                    <template
                                        v-for="(item2, k) in menuItem.sub"
                                        :key="k"
                                    >
                                        <template
                                            v-if="
                                                hasPermission(item2.permissions)
                                            "
                                        >
                                            <div
                                                v-if="item2.heading"
                                                class="menu-item"
                                            >
                                                <NuxtLink
                                                    v-if="item2.route"
                                                    class="menu-link"
                                                    active-class="active"
                                                    :to="item2.route"
                                                >
                                                    <span class="menu-bullet">
                                                        <span
                                                            class="bullet bullet-dot"
                                                        />
                                                    </span>
                                                    <span class="menu-title">{{
                                                        translate(item2.heading)
                                                    }}</span>
                                                </NuxtLink>
                                            </div>
                                            <div
                                                v-if="
                                                    item2.sectionTitle &&
                                                    item2.route
                                                "
                                                :class="{
                                                    show: hasActiveChildren(
                                                        item2.route,
                                                    ),
                                                }"
                                                class="menu-item menu-accordion"
                                                data-kt-menu-sub="accordion"
                                                data-kt-menu-trigger="click"
                                            >
                                                <span class="menu-link">
                                                    <span class="menu-bullet">
                                                        <span
                                                            class="bullet bullet-dot"
                                                        />
                                                    </span>
                                                    <span class="menu-title">{{
                                                        translate(
                                                            item2.sectionTitle,
                                                        )
                                                    }}</span>
                                                    <span class="menu-arrow" />
                                                </span>
                                                <div
                                                    :class="{
                                                        show: hasActiveChildren(
                                                            item2.route,
                                                        ),
                                                    }"
                                                    class="menu-sub menu-sub-accordion"
                                                >
                                                    <template
                                                        v-for="(
                                                            item3, k
                                                        ) in item2.sub"
                                                        :key="k"
                                                    >
                                                        <template
                                                            v-if="
                                                                hasPermission(
                                                                    item3.permissions,
                                                                )
                                                            "
                                                        >
                                                            <div
                                                                v-if="
                                                                    item3.heading
                                                                "
                                                                class="menu-item"
                                                            >
                                                                <NuxtLink
                                                                    v-if="
                                                                        item3.route
                                                                    "
                                                                    class="menu-link"
                                                                    active-class="active"
                                                                    :to="
                                                                        item3.route
                                                                    "
                                                                >
                                                                    <span
                                                                        class="menu-bullet"
                                                                    >
                                                                        <span
                                                                            class="bullet bullet-dot"
                                                                        />
                                                                    </span>
                                                                    <span
                                                                        class="menu-title"
                                                                        >{{
                                                                            translate(
                                                                                item3.heading,
                                                                            )
                                                                        }}</span
                                                                    >
                                                                </NuxtLink>
                                                            </div>
                                                        </template>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </template>
                </template>

                <div class="menu-item pt-5 !hidden">
                    <!--begin:Menu content-->
                    <div class="menu-content">
                        <span class="menu-heading fw-bold text-uppercase fs-7"
                            >Help</span
                        >
                    </div>
                    <!--end:Menu content-->
                </div>
                <!--begin:Menu item-->
                <div class="menu-item !hidden">
                    <!--begin:Menu link-->
                    <a
                        class="menu-link"
                        href="https://preview.keenthemes.com/metronic8/vue/docs/base/utilities"
                    >
                        <span class="menu-icon">
                            <i
                                v-if="sidebarMenuIcons === 'bootstrap'"
                                class="bi bi-briefcase fs-3"
                            />
                            <KTIcon
                                v-else-if="sidebarMenuIcons === 'keenthemes'"
                                icon-name="rocket"
                                icon-class="fs-2"
                            />
                        </span>
                        <span class="menu-title">Components</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->
                <div class="menu-item !hidden">
                    <!--begin:Menu link-->
                    <a
                        class="menu-link"
                        href="https://preview.keenthemes.com/metronic8/vue/docs/index"
                    >
                        <span class="menu-icon">
                            <i
                                v-if="sidebarMenuIcons === 'bootstrap'"
                                class="bi bi-box fs-3"
                            />
                            <KTIcon
                                v-else-if="sidebarMenuIcons === 'keenthemes'"
                                icon-name="abstract-26"
                                icon-class="fs-2"
                            />
                        </span>
                        <span class="menu-title">Documentation</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--begin:Menu item-->
                <!--end:Menu item-->
                <div class="menu-item !hidden">
                    <!--begin:Menu link-->
                    <a
                        class="menu-link"
                        href="https://preview.keenthemes.com/metronic8/vue/docs/getting-started/changelog"
                    >
                        <span class="menu-icon">
                            <i
                                v-if="sidebarMenuIcons === 'bootstrap'"
                                class="bi bi-diagram-3 fs-3"
                            />
                            <KTIcon
                                v-else-if="sidebarMenuIcons === 'keenthemes'"
                                icon-name="code"
                                icon-class="fs-2"
                            />
                        </span>
                        <span class="menu-title">Changelog</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--begin:Menu item-->
                <!--end:Menu item-->
            </div>
            <!--end::Menu-->
        </div>
        <!--end::Menu wrapper-->
    </div>
    <!--end::sidebar menu-->
</template>

<script lang="ts">
import { defineComponent, onMounted, ref } from "vue";
import { useRoute } from "vue-router";
import MainMenuConfig from "~/layouts/default-layout/config/MainMenuConfig";
import { sidebarMenuIcons } from "~/layouts/default-layout/config/helper";
import { useI18n } from "vue-i18n";
import type { User } from "~/types/user";
import { head } from "lodash";
import { roleTypes } from "element-plus";

export default defineComponent({
    name: "SidebarMenu",
    components: {},
    setup() {
        const { t, te } = useI18n();
        const route = useRoute();
        const scrollElRef = ref<null | HTMLElement>(null);
        const user = useSanctumUser<User>();

        onMounted(() => {
            if (scrollElRef.value) {
                scrollElRef.value.scrollTop = 0;
            }
        });

        const translate = (text: string) => {
            if (te(text)) {
                return t(text);
            } else {
                return text;
            }
        };

        const hasRoleAccess = (menuItemRoles: string[] | undefined) => {
            if (!menuItemRoles || !user.value) return true;

            return menuItemRoles.some((role) =>
                user.value?.role?.includes(role),
            );
        };

        const hasPagePermissions = (pages: string[] | undefined) => {
            return pages.some((page) => hasPermission(page.permissions));
        };

        const hasPermission = (menuItemPermissions: string[] | undefined) => {
            return canAccess(user.value, menuItemPermissions);
        };

        const hasActiveChildren = (match: string) => {
            return route.path.indexOf(match) !== -1;
        };

        return {
            hasActiveChildren,
            MainMenuConfig,
            sidebarMenuIcons,
            translate,
            hasRoleAccess,
            hasPermission,
            hasPagePermissions,
        };
    },
});
</script>
