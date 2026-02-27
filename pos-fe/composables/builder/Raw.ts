import type { Entry, Row } from "~/types/builder";

export const RawWrap = (key: string) => {
    const store = useFormBuilderStore();

    store.identifier(key);

    return {
        raw: (data: Entry) => {
            console.log(data);
            store.raw(key, data);
        },
    };
};
