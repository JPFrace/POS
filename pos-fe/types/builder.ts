import type { Option } from "./form";
import type { Attribute as FormAttribute } from "./applicant";

export interface Types {
    acceptable:
        | "ROW"
        | "COLUMN"
        | "INPUT"
        | "SELECT"
        | "TEXTAREA"
        | "CHECKBOX"
        | "RADIO"
        | "FILE";
}

export interface FormComponentTypes {
    acceptable: "INPUT" | "SELECT" | "TEXTAREA" | "CHECKBOX" | "RADIO" | "FILE";
}

export type Attribute =
    | "title"
    | "placeholder"
    | "label"
    | "value"
    | "defaultValue"
    | "multiple"
    | "key"
    | "required"
    | "searchable"
    | "number"
    | "options"
    | "acceptedFileTypes"
    | "multipleOptions"
    | "map"
    | "type";

export type Rules =
    | "number"
    | "string"
    | "boolean"
    | "email"
    | "date"
    | "required"
    | "file";

export const types = {
    ROW: "ROW",
    COLUMN: "COLUMN",
    INPUT: "INPUT",
    SELECT: "SELECT",
    RADIO: "RADIO",
    FILE: "FILE",
    TEXTAREA: "TEXTAREA",
    CHECKBOX: "CHECKBOX",
    COMPONENT: "COMPONENT",
};

export const componentTypes = [
    types.INPUT,
    types.SELECT,
    types.RADIO,
    types.FILE,
    types.TEXTAREA,
    types.CHECKBOX,
];

export interface Row {
    id: string;
    type: "ROW";
    accept: Types["acceptable"][];
    value: Record<Attribute, any>[];
    columns: Column[];
}

export interface Column {
    id: string;
    type: "COLUMN";
    accept: Types["acceptable"][];
    value: Record<Attribute, any>[];
    components: Component[];
}

export interface Component {
    id: string;
    type: FormComponentTypes["acceptable"];
    title: string;
    accept: Types["acceptable"][];
    attributes: Record<Attribute, any>;
    rules: Record<Rules, boolean>;
    value: any;
    defaultValue: any;
    valid: boolean | null;
    error: string | null;
}

export interface ComponentValue {
    title: string;
    type: Types["acceptable"];
    value: unknown;
}

export interface Entry {
    rows: Row[];
}

export interface BuilderValue {
    key: string;
    entry: Entry;
}

export interface Attributes {
    title: string;
    key: string;
    placeholder: string;
    value: any;
    map: Option;
    searchable: boolean;
    required: boolean;
    number: boolean;
    type: Option;
    options: Option[];
    acceptedFileTypes: Option[];
    multipleOptions: boolean;
}
