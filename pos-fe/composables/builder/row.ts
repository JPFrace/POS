import type { Row } from "~/types/builder";

export const RowWrap = (key: string) => {
    const store = useFormBuilderStore();

    store.identifier(key);

    return {
        add: (value?: Row) => store.row.add(key, builder.rowWrap()),
        remove: (value: Row) => store.row.remove(key, value),
    };
};
