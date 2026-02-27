export const useFormErrors = (errors: () => object | undefined) => {
    const isValid = (key: string): boolean | null => {
        const errs = errors();
        if (!errs) return null;
        const fieldErrors = (errs as Record<string, string[]>)[key];
        if (fieldErrors === undefined) return null;
        return fieldErrors.length === 0;
    };

    return { isValid };
};
