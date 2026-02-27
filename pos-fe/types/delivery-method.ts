import type { Option } from "./form";

export interface Courier {
    uuid: string;
    name: string;
}

export interface DeliveryType {
    uuid: string;
    name: string;
}

export interface PaymentMethod {
    uuid: string;
    name: string;
}

export interface DeliveryMethod {
    uuid: string;
    rate: number;
    courier: Partial<Courier>;
    delivery_type: Partial<DeliveryType>;
    payment_method: Partial<PaymentMethod>;
}

export interface Form {
    uuid: string;
    rate: number;
    courier: Partial<Option> | null;
    delivery_type: Partial<Option> | null;
    payment_method: Partial<Option> | null;
}
