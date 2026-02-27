import type { Option } from "./form";
import type { Product } from "./products";

export interface OrderItem {
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
}

export interface Order {
    uuid?: string;
    date: string | null;
    due_date: string | null;
    order_no: string | null;
    remarks: string | null;
    attachment: File | null;
    items: Partial<OrderItem>[];
    order_no_auto: boolean | null;
    vendor: Option;
    vendor_idno: string | null;
    vendor_name: string | null;
    vendor_email: string | null;
    billing_address: string | null;
    payment_method: Option;
}
