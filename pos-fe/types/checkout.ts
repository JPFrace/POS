import type { Address } from "./address";
import type { Cart } from "./cart";
import type { Courier } from "./courier";
import type { DeliveryType } from "./delivery-type";
import type { PaymentMethod } from "./payment-method";

export interface Checkout {
    uuid: string;
    address: Partial<Address> | null;
    cart: Cart[];
    courier: Partial<Courier> | null;
    delivery_type: Partial<DeliveryType> | null;
    payment_method: Partial<PaymentMethod> | null;
    total: number;
}
