import { useLocalStorage } from "@vueuse/core";
import type { Cart, CartValue } from "~/types/cart";
import type { User } from "~/types/user";
import type { Product } from "~/types/products";

export const useCart = defineStore("cart", () => {
    const { $bus } = useNuxtApp();
    const user = useSanctumUser<User>();
    const _carts = useLocalStorage<CartValue>("cart", {});

    const identifier = (user.value?.uuid ?? "") as string;

    const identityCarts = computed(() => _carts.value[identifier] || []);

    /**
     * Replace and refresh reactive value
     * @param value
     */
    const replace = (value: Cart[]) => {
        _carts.value[identifier as any] = value;

        refresh();
    };

    /**
     * refresh reactive value
     * @param value
     */
    const refresh = () => {
        _carts.value[identifier] = [...identityCarts.value.filter(Boolean)];
    };

    /**
     * Add product to cart
     * @param product
     * @param quantity
     * @returns
     */
    const put = (product: Partial<Product>, quantity: number = 1) => {
        const index = identityCarts.value.findIndex(
            (cart: Cart) => cart.product.uuid == product.uuid
        );

        if (index != -1) {
            identityCarts.value[index].quantity += quantity;

            replace(identityCarts.value);

            // event updated
            $bus.emit("cart:increase", identityCarts.value[index]);

            return;
        }

        const _value = {
            id: uuid(),
            product,
            quantity,
        } as Cart;

        replace(identityCarts.value.concat(_value));

        // event added
        $bus.emit("cart:added", _value);
    };

    /**
     * Remove product from the cart
     * @param product
     * @param quantity
     */
    const pop = (product: Partial<Product>, quantity: number = 1) => {
        const index = identityCarts.value.findIndex(
            (cart: Cart) => cart.product.uuid == product.uuid
        );

        if (index !== -1) {
            const cart = identityCarts.value[index];

            identityCarts.value[index].quantity -= quantity;

            if (identityCarts.value[index].quantity <= 0) {
                delete identityCarts.value[index];

                // event deleted
                $bus.emit("cart:deleted", cart);

                refresh();

                if (!identityCarts.value.length) {
                    $bus.emit("cart:empty", true);
                }

                return;
            }

            replace(identityCarts.value);

            // event updated
            $bus.emit("cart:reduced", cart);
        }
    };

    /**
     * Empty cart
     */
    const $reset = () => {
        _carts.value = {};
    };

    const total = (): number => {
        return identityCarts.value.reduce((total: number, cart: Cart) => {
            total += (cart.product.price as number) * cart.quantity;

            return total;
        }, 0);
    };

    return {
        carts: identityCarts,
        put,
        pop,
        $reset,
        total,
        identifier,
    };
});
