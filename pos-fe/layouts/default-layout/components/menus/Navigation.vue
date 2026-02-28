<template>
  <MenuComponent menu-selector="#kt-navigation-menu">
    <!-- TOGGLE BUTTON -->
    <template #toggle>
      <div
        id="kt_header_navigation"
        class="header-config d-flex align-items-stretch"
        data-kt-menu-target="#kt-navigation-menu"
        data-kt-menu-trigger="click"
        data-kt-menu-attach="parent"
        data-kt-menu-placement="bottom-end"
        data-kt-menu-flip="bottom"
      >
        <div id="kt_header_navigation_toggle" class="d-flex align-items-center">
          <div
            class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px flex items-center justify-center rounded-lg transition-all duration-200 ease-out hover:scale-100 active:scale-80"
            title="Quick Actions"
          >
            <KTIcon icon-name="plus-circle" icon-class="fs-2" />
          </div>
        </div>
      </div>
    </template>

    <!-- DROPDOWN CONTENT -->
    <template #content>
      <div
        id="kt-navigation-menu"
        class="menu menu-sub menu-sub-dropdown menu-column p-7 h-md-200px w-md-450px rounded-xl shadow-xl app-header-dropdown"
        data-kt-menu="true"
      >
        <div class="grid grid-cols-3 gap-y-6">
          <template v-for="item in NavigationMenu" :key="item.heading">
            <div class="min-w-[180px]">
              <!-- HEADING -->
              <h4
                class="mb-3 font-semibold tracking-wide app-header-dropdown__heading"
              >
                {{ item.heading }}
              </h4>

              <!-- LINKS -->
              <ul class="space-y-1 list-none p-0">
                <li v-for="sub in item.pages" :key="sub.title">
                  <NuxtLink
                    :to="sub.route"
                    class="group flex items-center gap-2 px-0.5 py-2 rounded-md text-sm app-header-dropdown__link transition-all duration-200"
                  >
                    <!-- Icon -->
                    <KTIcon
                      v-if="sub.keenthemesIcon"
                      :icon-name="sub.keenthemesIcon"
                      class="transition-transform duration-200 app-header-dropdown__icon"
                    />

                    <!-- Text -->
                    <span
                      class="transition-transform duration-200 hover:border-b-[1px] border-primary"
                    >
                      {{ sub.title }}
                    </span>
                  </NuxtLink>
                </li>
              </ul>
            </div>
          </template>
        </div>
      </div>
    </template>
  </MenuComponent>
</template>

<script lang="ts" setup>
import MenuComponent from "~/layouts/theme/menu/MenuComponent.vue";
import NavigationMenu from "~/layouts/default-layout/config/NavigationMenu";
import type { User } from "~/types/user";

const user = useSanctumUser<User>();

const hasPagePermissions = (pages: any[]) => {
  return pages?.some((page) => hasPermission(page.permissions));
};

const hasPermission = (menuItemPermissions: string[] | undefined) => {
  return canAccess(user.value, menuItemPermissions);
};
</script>
