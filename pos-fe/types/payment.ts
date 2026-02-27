import type { Option } from "./form";
import type { Product } from "./products";
import type { Contacts } from "./contacts";

export interface PaymentItem {
    uuid?: string;
    product: Option & Product;
    rate: number;
    withholding_tax_rate: number;
    purchase_tax_rate: number;
    quantity: number;
    product_name: string;
    product_description: string;
    sub_contact?: Partial<Option & Contacts>;
    active: boolean;
    product_active: boolean;
    sub_contact_active: boolean;
    sub_total: string | number;
    price: string | number;
}

export interface PaymentJournalRow {
    id?: number;
    debit: number;
    credit: number;
    chart_account?: {
        code?: string;
        name?: string;
        usage_type?: { code?: string };
    };
}

export interface Payment {
    uuid?: string;
    date: string | null;
    ref_no: string | null;
    check_no: string | null;
    remarks: string | null;
    attachment: File | null;
    status: PaymentStatus;
    items: Partial<PaymentItem>[];
    ref_no_auto: boolean;
    check_no_auto: boolean;
    contact: Option;
    payee_idno: string | null;
    payee_name: string | null;
    payee_email: string | null;
    payee_address: string | null;
    payment_method: Option;
    cash_in_bank: Option;
    cash_bank?: Option;
    net_in_words?: string | null;
    journals?: PaymentJournalRow[];
    dimension?: Option[] | null;
}

export interface PaymentStatus {
    uuid?: string;
    name: string;
    description: string;
}
