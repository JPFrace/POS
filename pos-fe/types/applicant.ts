import type { Component, Entry } from "./builder";

export interface Contents {
    rows: Component[];
}

export interface Attribute {
    uuid: string;
    contents: Contents;
}
export interface Form {
    uuid: string;
    attribute: Attribute;
    name: string;
    slug: string;
}

export interface Document {
    form: Form;
}

export interface Registration {
    uuid: string;
    name: string;
    fullname: string;
    first_name: string;
    last_name: string;
    email: string;
    position: string;
    contact_number: string;
    mobile_number: string;
    status_label: string;
    entry: Entry;
    document: Document;
}
