import type { PaymentMethod } from "./payment-method";

export interface DeliveryType {
    uuid: string;
    name: string;
    description: string;
    payment_methods: Partial<PaymentMethod>[];
}
