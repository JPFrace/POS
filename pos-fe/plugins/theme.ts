import { Tooltip } from "bootstrap";
import i18n from "~/core/plugins/i18n";

import ApiService from "~/core/services/ApiService";

import { initApexCharts } from "~/core/plugins/apexcharts";
import { initInlineSvg } from "~/core/plugins/inline-svg";
import { initVeeValidate } from "~/core/plugins/vee-validate";
import { initKtIcon } from "~/core/plugins/keenthemes";

import "~/core/plugins/prismjs";
import {
    DrawerComponent,
    defaultDrawerOptions,
    MenuComponent,
    defaultMenuOptions,
} from "~/assets/ts/components";

export default defineNuxtPlugin((nuxtApp) => {
    ApiService.init(nuxtApp.vueApp);

    initApexCharts(nuxtApp.vueApp);
    initInlineSvg(nuxtApp.vueApp);
    initKtIcon(nuxtApp.vueApp);
    initVeeValidate();

    nuxtApp.vueApp.directive("tooltip", (el) => {
        new Tooltip(el);
    });

    const drawer = (id: string) => {
        const instance = document.body.querySelector(`#${id}`);

        return (
            instance &&
            new DrawerComponent(instance as HTMLElement, defaultDrawerOptions)
        );
    };

    const menu = (id: string) => {
        const instance = document.body.querySelector(`#${id}`);

        return (
            instance &&
            new MenuComponent(instance as HTMLElement, defaultMenuOptions)
        );
    };

    return {
        provide: {
            drawer,
            menu,
        },
    };
});
