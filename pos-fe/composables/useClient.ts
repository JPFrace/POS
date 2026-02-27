import type { Throwable } from "~/types/common";

export const useClient = <T = any>(endpoint: string, options?: object) => {
    return new Promise<T>(async (resolve, reject) => {
        try {
            const response = await useSanctumClient()(endpoint, options);
            resolve(response as T);
        } catch (error: any) {
            reject({
                message: error.data?.message || error?.message,
                errors: error.data?.errors || error?.errors,
            } as Throwable);
        }
    });
};
