export const usePaymentMethod = () => {
    /**
     * Fetch the payment method name for a given payment UUID
     * @param paymentUuid string
     * @returns string | null (lowercased payment method name)
     */
    const getSavedPaymentMethod = async (
        paymentUuid: string
    ): Promise<string | null> => {
        try {
            const result = await useClient(`/api/business/payments`, {
                method: "GET",
                params: {
                    query: {
                        uuid: paymentUuid,
                        payment_method: true,
                    },
                },
            });

            return result.data[0]?.payment_method?.name?.toLowerCase() ?? null;
        } catch (error) {
            console.error("Failed to fetch payment method:", error);
            return null;
        }
    };

    return {
        getSavedPaymentMethod,
    };
};
