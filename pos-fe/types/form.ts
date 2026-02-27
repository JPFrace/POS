export interface Form {
    type?:
        | "text"
        | "number"
        | "date"
        | "datetime"
        | "password"
        | "checkbox"
        | "radio"
        | "email";
    id?: string;
    label?: string;
    placeholder?: string;
    size?: "sm" | "md" | "lg";
    name?: string;
    isValid?: boolean | null;
    class?: string;
    title?: string;
    float?: boolean;
    group?: boolean;
    solid?: boolean;
    transparent?: boolean;
    disabled?: boolean;
    readonly?: boolean;
    parentClass?: string;
    block?: boolean;
    topDesc?: string;
    belowDesc?: string;
}

export interface Option {
    id: number | null;
    uuid?: string | null;
    value: string | number | null;
    label: string;
    flag?: string | null;
    name?: string | null;
    children?: Option[];
    columns?: string[];
}

export type Methods = "POST" | "GET";

export interface Select {
    url?: string;
    method?: Methods;
    query?: string | string[];
    loadingText?: string;
    noMatchText?: string;
    noDataText?: string;
    remote?: boolean;
    remoteMethod?: Function;
    loading?: boolean;
    multiple?: boolean;
    filterable?: boolean;
    group?: boolean;
    column?: boolean;
    isValid?: boolean | null;
    change?: Function;
    visibleChange?: Function;
    removeTag?: Function;
    clear?: Function;
    blur?: Function;
    focus?: Function;
    allowCreate?: boolean;
    mapQuery?: Function;
    mapResult?: Function;
    cascader?: boolean;
    customColumn?: boolean;
}

export interface File {
    file: any;
    url: string;
    filename: string;
    uuid: string;
    original_filename: string;
}
