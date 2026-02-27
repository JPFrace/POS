import mitt from "mitt";

export default defineNuxtPlugin((nuxtApp) => {
    return {
        provide: {
            bus: mitt(),
        },
    };
});
