export const useConfig = () => {
    return {
        get: (key: string) => {
            let config = localStorage.getItem("config");

            if (config) {
                config = JSON.parse(config);

                const value = config.children.filter((c: any) => c.slug == key);

                return value[0] ?? null;
            }

            return null;
        },
    };
};
