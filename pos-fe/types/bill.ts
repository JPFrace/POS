import type { Option } from "./form";
import type { Order } from "./order";
import type { Product } from "./products";

export interface BillTerm {
    uuid: string;
    name: string;
    description: string;
}

export interface BillItem {
    uuid?: string;
    product: Option & Product;
    rate: number;
    quantity: number;
    product_name: string;
    product_description: string;
    active: boolean;
    product_active: boolean;
    sub_total: string | number;
    price: string | number;
    order: Order;
    delivered: number | null;
    balance: number | null;
    original_quantity: number | null;
}

export interface Bill {
    uuid?: string;
    date: string | null;
    due_date: string | null;
    bill_no: string | null;
    term: BillTerm | null;
    remarks: string | null;
    attachment: File | null;
    /** Line items (API returns this as `details`) */
    items?: Partial<BillItem>[];
    /** Line items from API response */
    details?: Partial<BillItem>[];
    bill_no_auto: boolean | null;
    vendor: Option;
    status: BillStatus;
    vendor_idno: string | null;
    vendor_name: string | null;
    vendor_email: string | null;
    billing_address: string | null;
}

export interface BillStatus {
    uuid?: string;
    name: string;
    description: string;
}
