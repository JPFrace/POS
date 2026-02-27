import type { Entry } from "./builder";

export interface Form {
    uuid: string;
    name: string;
    slug: string;
    description: string;
    attribute: Partial<Attribute>;
}

export interface Attribute {
    uuid: string;
    form: Partial<Form>;
    contents: Entry;
}
