import type { Product } from "./products";

export interface Cart {
    id: string;
    uuid: string;
    product: Partial<Product>;
    quantity: number;
    price: number;
    total: number;
    total_price_display: string;
    price_display: string;
    status: string;
    registration: object;
}

export interface CartValue {
    [key: string]: Cart[];
}
