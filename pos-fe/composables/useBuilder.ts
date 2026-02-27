import { RowWrap } from "./builder/row";
import { ColumnWrap } from "./builder/column";
import { ComponentWrap } from "./builder/component";
import { RawWrap } from "./builder/Raw";
import {
    types,
    type Column,
    type Component,
    type Entry,
    type Row,
} from "~/types/builder";

export const useBuilder = (key: string) => {
    const store = useFormBuilderStore();

    store.identifier(key);

    return {
        row: RowWrap(key),
        column: ColumnWrap(key),
        component: ComponentWrap(key),
        raw: RawWrap(key).raw,
        clearData: () => {
            let state = [] as object[];
            if (!store.entryValue(key)) {
                return state;
            }

            store.replace(key, {
                rows: store.entryValue(key)!.entry.rows.map((row: Row) => {
                    row.columns.map((column: Column) => {
                        column.components.map((component: Component) => {
                            component = {
                                ...component,
                                value: "",
                            };

                            return component;
                        });

                        return column;
                    });

                    return row;
                }),
            } as Entry);

            return state;
        },
        data: () => {
            let state = [] as object[];
            store.entryValue(key)!.entry.rows.forEach((row: Row) => {
                row.columns.forEach((column: Column) => {
                    column.components.forEach((component: Component) => {
                        state = {
                            ...state,
                            [component.attributes!.key]: (() => {
                                if (component.type == types.SELECT) {
                                    if (component.attributes?.multipleOptions) {
                                        return component.value.length
                                            ? component.value[0].value
                                            : null;
                                    }
                                    return component.value?.value;
                                }

                                return component.value;
                            })(),
                        };
                    });
                });
            });

            return state;
        },
    };
};
