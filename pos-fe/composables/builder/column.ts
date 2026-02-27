import type { Column, Row } from "~/types/builder";

export const ColumnWrap = (key: string) => {
    const store = useFormBuilderStore();

    store.identifier(key);

    return {
        add: (row: Row, value?: Column) =>
            store.column.add(row, builder.columnWrap()),
        remove: (row: Row, value: Column) => store.column.remove(row, value),
    };
};
