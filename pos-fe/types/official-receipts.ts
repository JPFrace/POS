import type { Option } from "./form";
import type { Invoice } from "./invoice";
import type { Product } from "./products";

export interface OfficialReceiptItem {
    uuid?: string;
    product: Option & Product;
    rate: number;
    tax_rate: number;
    quantity: number;
    product_name: string;
    product_description: string;
    active: boolean;
    product_active: boolean;
    sub_total: string | number;
    price: string | number;
    invoice: Invoice;
}

export interface OfficialReceipt {
    uuid?: string;
    date: string | null;
    or_no: string | null;
    or_no_auto: boolean;
    ref_no: string | null;
    ref_no_auto: boolean;
    remarks: string | null;
    attachment: File | null;
    status: OfficialReceiptStatus;
    items: Partial<OfficialReceiptItem>[];
    customer: Option;
    customer_idno: string | null;
    customer_name: string | null;
    customer_email: string | null;
    billing_address: string | null;
    total: number;
    dimension?: Option[] | null;
    actual_receive_amount: number;
    gross_amount: number;
    details?: Array<{
        description: string;
        quantity: number;
        rate: number;
        sub_total: number;
    }>;
    journals: Array<{
        contact_name: string;
        posted_at: string;
        description: string;
    }>;
    denominations: Partial<Denomination>[] | null;
    references: string;
}

export interface OfficialReceiptStatus {
    uuid?: string;
    name: string;
    description: string;
}

export interface Denomination {
    uuid?: string;
    depositAccount?: Option | null;
    payment_method?: Option | null;
    quantity?: number;
    denomination?: number;
    bank?: string;
    reference_date?: string;
    reference_no?: string;
    amount?: number;
}
