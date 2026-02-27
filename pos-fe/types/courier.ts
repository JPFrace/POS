import type { DeliveryType } from "./delivery-type";

export interface Courier {
    uuid: string;
    name: string;
    description: string;
    delivery_types: Partial<DeliveryType>[];
}
