import type { Product } from "./product";

export interface TransactionTemplate {
    name: string;
    uuid?: string;
    description?: string;
    is_inactive?: boolean;
    details: TransactionTemplateDetails[];
}

export interface TransactionTemplateDetails {
    uuid?: string | null;
    quantity: number;
    amount?: number | null;
    product: Product | null;
}
