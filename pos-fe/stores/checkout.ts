import { useLocalStorage } from "@vueuse/core";
import type { Address } from "~/types/address";
import type { Cart, CartValue } from "~/types/cart";
import type { Checkout } from "~/types/checkout";
import type { Courier } from "~/types/courier";
import type { DeliveryType } from "~/types/delivery-type";
import type { PaymentMethod } from "~/types/payment-method";

export const useCheckoutStore = defineStore("checkout", () => {
    const cart = useCart();
    const form = useFormBuilderStore();
    const data = useLocalStorage<Partial<Checkout>>("checkout", {
        cart: cart.carts,
    });

    const setCourier = (courier: Partial<Courier>) => {
        data.value = {
            ...data.value,
            courier: courier,
            delivery_type: null,
            payment_method: null,
        };
    };

    const setDeliveryAddress = (address: Partial<Address>) => {
        data.value.address = address;
    };

    const setPaymentMethod = (method: Partial<PaymentMethod>) => {
        data.value.payment_method = method;
    };

    const setDeliveryType = (type: Partial<DeliveryType>) => {
        data.value = {
            ...data.value,
            delivery_type: type,
            payment_method: null,
        };
    };

    const setCart = (cart: Cart[]) => {
        data.value = {
            ...data.value,
            cart: cart,
        };
    };

    const refresh = () => {
        data.value = {
            ...data.value,
            cart: cart.carts.filter(Boolean),
            total: cart.total(),
        };
    };

    const $reset = () => {
        cart.$reset();
        form.$reset();
        data.value = {
            courier: null,
            delivery_type: null,
            payment_method: null,
        };
    };

    onMounted(() => {
        setCart(cart.carts);
    });

    watch(cart, (value: CartValue) => {
        setCart(value.carts);
    });

    return {
        data,
        $reset,
        refresh,
        setCourier,
        setDeliveryAddress,
        setPaymentMethod,
        setDeliveryType,
    };
});
