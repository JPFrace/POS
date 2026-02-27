export interface Member {
    uuid: string;
    name: string;
    email: string;
    address: Address;
}

export interface Address {
    uuid: string;
    member_uuid: string;
    contact_name: string;
    contact_no: string;
    address1: string;
    address2: string;
    address3: string;
    province: Province;
    city: City;
    barangay: Barangay;
    default: boolean;
    zipcode: string;
    full_address: string;
}

export interface Province {
    uuid: string;
    name: string;
    region: Region;
}

export interface City {
    uuid: string;
    name: string;
    province: Province;
}

export interface Barangay {
    uuid: string;
    name: string;
    city: City;
}

export interface Region {
    uuid: string;
    name: string;
}
