import type { Row, Column, Component, Attribute } from "~/types/builder";
import { types } from "~/types/builder";
import type { Option } from "~/types/form";

export const ComponentWrap = (key: string) => {
    const store = useFormBuilderStore();

    store.identifier(key);

    return {
        add: (column: Column, value: Component) =>
            store.component.add(column, builder.componentWrap(value)),
        remove: (column: Column, value: Component) =>
            store.component.remove(column, value),
        attributes: (component: Component, value: Record<Attribute, any>[]) => {
            store.component.attributes(component, value);
        },
        setValue: (component: Component, value: any) => {
            store.component.value(component, value);
        },
        setDefaultValue: (component: Component, value: any) => {
            store.component.defaultValue(component, value);
        },
        value: (component: Component) => {
            return component.value;
        },
        defaultValue: (component: Component) => {
            return component.defaultValue;
        },
        id: (component: Component) => {
            const { id } = component;

            return id;
        },
        type: (component: Component) => {
            const { type } = component;

            return type;
        },
        attributeType: (component: Component) => {
            const { type } = component.attributes;

            return type;
        },
        float: (component: Component) => {
            const { type } = component;

            if (type == types.INPUT) {
                return true;
            }

            return null;
        },
        placeholder: (component: Component) => {
            const { title, placeholder } = component.attributes;

            return placeholder ? placeholder : title;
        },
        label: (component: Component) => {
            const { title, label } = component.attributes;

            return label ? label : title;
        },
        options: (component: Component) => {
            const {
                type,
                attributes: { options },
            } = component;

            if (type == types.SELECT) {
                return (options ?? []).map((option: Option) => {
                    const key = uuid();
                    return {
                        ...option,
                        key: key,
                        id: key,
                    };
                });
            }

            return null;
        },
        multiple: (component: Component) => {
            const {
                type,
                attributes: { multipleOptions },
            } = component;

            if (type == types.SELECT) {
                return multipleOptions ?? false;
            }

            return null;
        },
        isValid: (component: Component) => component.valid,
        error: (component: Component) => component.error,
        setError: (component: Component, message: string | null) => {
            store.component.error(component, message);
            store.component.valid(component, !message);
        },
        setErrors: (errors: any[]) => {
            store.component.errors(key, errors);
        },
        clearErrors: () => store.component.clearErrors(key),
    };
};
