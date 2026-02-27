export const usePageEvent = () => {
    const route = useRoute();
    const { $bus } = useNuxtApp();

    const key = id(route.path);

    return {
        key: () => key,
        receive: async (identifier: string, callback: Function) => {
            $bus.on(`${key}_${identifier}`, callback);
        },
        send: async (identifier: string, data?: any) => {
            $bus.emit(`${key}_${identifier}`, data);
        },
        dismiss: async (identifier: string) => {
            $bus.off(`${key}_${identifier}`);
        },
    };
};
