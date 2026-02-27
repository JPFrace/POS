import type { ContactClass } from "./contact-class";
import type { ContactSubType } from "./contact-type";
import type { Country } from "./country";
import type { File as FileObject } from "./file";
import type { WithholdingTax } from "./withholding-tax";

export interface Contact {
    name: string;
    address: string;
    contact_number: string;
}

export interface Contacts {
    uuid: string;
    type: number;
    id_no?: string;
    id_no_auto?: boolean;
    first_name: string;
    last_name: string;
    middle_name?: string;
    suffix?: string;
    email?: string;
    billing_address?: string;
    country?: Country | null;
    zip_code?: string;
    contact_number?: string;
    contacts: Contact[];
    name: string;
    sub_type: ContactSubType | null;
    class: ContactClass | null;
    tax?: WithholdingTax | null;
    file?: File | FileObject | null;
}
