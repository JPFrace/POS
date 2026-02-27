import type { Option } from "./form";

export interface Region {
    uuid: string;
    name: string;
}

export interface Rates {
    uuid: string;
    rate: number;
    region: Partial<Region>;
}

export interface Form {
    uuid: string;
    rate: number;
    region: Partial<Option> | null;
}
