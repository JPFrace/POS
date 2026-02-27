import type { Option } from "./form";
import type { Product } from "./products";

export interface InvoiceItem {
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
}

export interface Invoice {
    uuid?: string;
    date: string | null;
    due_date: string | null;
    invoice_no: string | null;
    remarks: string | null;
    attachment: File | null;
    status: InvoiceStatus;
    items: Partial<InvoiceItem>[];
    invoice_no_auto: boolean | null;
    customer: Option;
    customer_idno: string | null;
    customer_name: string | null;
    customer_email: string | null;
    billing_address: string | null;
    payment_method: Option;
}

export interface InvoiceStatus {
    uuid?: string;
    name: string;
    description: string;
}
