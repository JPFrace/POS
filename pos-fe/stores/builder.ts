import { useLocalStorage } from "@vueuse/core";
import { isEmpty } from "lodash";

import type {
    Row,
    Column,
    Component,
    Entry,
    Attribute,
    BuilderValue,
} from "~/types/builder";

export const useFormBuilderStore = defineStore("form-builder", () => {
    const entry = useLocalStorage<BuilderValue[]>("form-builder", []);

    const errorsBags = ref([]);

    const _identifier = useState<string>();

    const identifier = (key: string) => (_identifier.value = key);

    const entryValue = (key: string) => {
        const index = getIndex(key);

        if (index !== -1) {
            return entry.value[index];
        }

        return null;
    };

    const refresh = () => {
        entry.value = [...entry.value.filter(Boolean)];
    };

    const pop = (key: string) => {
        const index = getIndex(key);

        if (index === -1) {
            return;
        }

        delete entry.value[index];

        refresh();
    };

    const put = (key: string, data: Entry) => {
        if (contains(key)) {
            return;
        }

        entry.value = entry.value.concat({
            key,
            entry: data,
        });

        console.log([entry.value, data]);

        refresh();
    };

    const contains = (key: string): Boolean => {
        return entry.value.filter((d: BuilderValue) => d.key == key).length > 0
            ? true
            : false;
    };

    const getIndex = (key: string) => {
        return entry.value.findIndex((d: BuilderValue) => d.key == key);
    };

    const $reset = () => {
        entry.value = [];

        refresh();
    };

    const replace = (key: string, data: Entry) => {
        const index = getIndex(key);

        if (index !== -1) {
            entry.value[index].entry = data;

            refresh();
            return;
        }

        put(key, data);
    };

    const raw = (key: string, data: Entry) => {
        replace(key, data);
    };

    const row = {
        add: (key: string, value: Row) => {
            const index = getIndex(key);

            if (index !== -1) {
                entry.value[index].entry.rows =
                    entryValue(key)!.entry.rows.concat(value);
            } else {
                entry.value = entry.value.concat({
                    key: key,
                    entry: {
                        rows: [value],
                    },
                });
            }

            refresh();
        },
        remove: (key: string, value: Row) => {
            const index = getIndex(key);

            entry.value[index].entry.rows = entry.value[
                index
            ].entry.rows.filter((row) => row.id != value.id);

            refresh();
        },
    };

    const column = {
        add: (row: Row, value: Column) => {
            row.columns = row.columns.concat(value);

            refresh();
        },
        remove: (row: Row, value: Column) => {
            row.columns = row.columns.filter((column) => column.id != value.id);

            refresh();
        },
    };

    const component = {
        add: (column: Column, value: Component) => {
            column.components = column.components.concat(value);

            refresh();
        },
        remove: (column: Column, value: Component) => {
            column.components = column.components.filter(
                (component) => component.id != value.id
            );

            refresh();
        },
        attributes: (
            component: Component,
            value: Record<Attribute, string>[]
        ) => {
            component.attributes = {
                ...component.attributes,
                ...value,
            };

            refresh();
        },
        value: (component: Component, value: any) => {
            component.value = value;

            refresh();
        },
        defaultValue: (component: Component, value: any) => {
            component = {
                ...component,
                defaultValue: value,
            };

            refresh();
        },
        error: (component: Component, error: string | null) => {
            component = {
                ...component,
                error,
            };

            refresh();
        },
        valid: (component: Component, valid: boolean | null) => {
            component = {
                ...component,
                valid,
            };

            refresh();
        },
        errors: (key: string, errors: any[]) => {
            const index = getIndex(key);

            for (var error of errors) {
                const key = Object.keys(error)[0];

                entry.value[index].entry.rows = entry.value[
                    index
                ].entry.rows.map((row: Row) => {
                    row.columns = row.columns.map((column: Column) => {
                        column.components = column.components.map(
                            (component: Component) => {
                                if (component.attributes.key == key) {
                                    component.valid = error[key] ? false : null;
                                    component.error = error[key];
                                }

                                return component;
                            }
                        );

                        return column;
                    });

                    return row;
                });
            }

            refresh();
        },
        clearErrors: (key: string) => {
            const index = getIndex(key);

            entry.value[index].entry.rows = entry.value[index].entry.rows.map(
                (row: Row) => {
                    row.columns = row.columns.map((column: Column) => {
                        column.components = column.components.map(
                            (component: Component) => {
                                component.valid = null;
                                component.error = null;

                                return component;
                            }
                        );

                        return column;
                    });

                    return row;
                }
            );

            refresh();
        },
    };

    return {
        identifier,
        pop,
        put,
        entry,
        row,
        column,
        component,
        errorsBags,
        raw,
        $reset,
        contains,
        entryValue,
        replace,
        getIndex,
    };
});
