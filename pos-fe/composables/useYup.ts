import type { AnyObject } from "yup";
import * as Yup from "yup";
import type { Throwable } from "~/types/common";

const formDataToObject = (data: FormData) => {
    const entries = {};

    data.entries().forEach((row) => {
        const [index, value] = row;
        entries[index] = value;
    });

    return entries;
};

export const useYup = () => ({
    validate: (
        schema: AnyObject,
        {
            data,
            callback,
            options = {
                abortEarly: false,
            },
        }: { data: object | FormData; callback?: Function; options?: {} }
    ): Promise<any> => {
        return new Promise(async (resolve, reject) => {
            try {
                let _data = data;
                if (data instanceof FormData) {
                    _data = formDataToObject(data);
                }

                await schema.validate(_data, options);

                if (callback) {
                    resolve(callback);
                    return;
                }
                resolve(true);
            } catch (responseError: any) {
                console.log(responseError);
                reject({
                    message: responseError.message,
                    errors: responseError.inner.reduce(
                        (errors: object, row: any) => {
                            errors[row.path] = [row.message];

                            return errors;
                        },
                        {}
                    ),
                } as Throwable);
            }
        });
    },
    yup: () => Yup,
});
