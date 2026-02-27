import type { Option } from "./form";

export interface Province {
    uuid: string;
    name: string;
}

export interface City {
    uuid: string;
    name: string;
}

export interface Barangay {
    uuid: string;
    name: string;
}

export interface MemberAddress {
    uuid: string;
    address1: string;
    address2: string;
    address3: string;
    zipcode: number;
    contact_name: string;
    contact_no: string;
    default: boolean;
    provinces: Partial<Province>;
    cities: Partial<City>;
    barangays: Partial<Barangay>;
}

export interface Form {
    uuid: string;
    address1: string;
    address2: string;
    address3: string;
    zipcode: number;
    default: boolean;
    contact_name: string;
    contact_no: string;
    provinces: Partial<Option> | null;
    cities: Partial<Option> | null;
    barangays: Partial<Option> | null;
}
