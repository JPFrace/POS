// Defined your global helper here

import type { UnwrapRef } from "vue";
import { v4 as uuidv4 } from "uuid";
import { toNumber } from "lodash";
import type { Tax } from "~/types/Tax";

export const classNames = (...classes: string[]) => {
    return classes.filter(Boolean).join(" ");
};

export const clearKeyValue = (
    model: object[] | UnwrapRef<any>,
    transform?: Function,
) => {
    for (const key of Object.keys(model)) {
        if (transform) {
            model[key] = transform(model, key);
        } else {
            if (typeof model[key] == "object") {
                model[key] = null;
            } else if (typeof model[key] == "boolean") {
                model[key] = false;
            } else {
                model[key] = "";
            }
        }
    }

    return model;
};

export const id = (value: string | number) =>
    value.toString().replace(/\/|\b\d*/g, "");

export const uuid = () => uuidv4();

export const objectToArray = (value: Record<string, any>) => {
    const data = [];
    for (var obj of Object.keys(value)) {
        data.push({ [obj]: value[obj] });
    }

    return data;
};

export const currencyFormat = (amount: any, decimal = 0, code = "en-PH") => {
    if (!amount) {
        return amount;
    }

    return new Intl.NumberFormat(code, {
        style: "decimal",
        minimumFractionDigits: decimal,
    }).format(numberOnly(amount));
};

export const money = (amount: any, decimal = 0, code = "en-PH") => {
    return currencyFormat(amount, decimal, code);
};

export const reportsMoney = (
    amount: any,
    decimal = 0,
    showZero = true,
    code = "en-PH",
) => {
    if (!showZero && Number(amount) === 0) {
        return "";
    }

    return currencyFormat(amount, decimal, code);
};
export const numberOnly = (amount: any) => {
    if (!amount) {
        return 0;
    }

    if (Number.isNaN(amount)) {
        return 0;
    }

    return parseFloat(amount.toString().replace(/[^0-9.-]/g, ""));
};

export const calculateTaxRate = (tax: Tax, amount: number) => {
    let rate = tax?.rate ?? 0;

    if (tax?.rate_type == "percent") {
        rate = amount * (tax.rate / 100);
    }

    return rate;
};

export const transactionType = (type: number) => {
    if (type == 10) {
        return "Journal";
    } else if (type == 20) {
        return "Sales";
    } else if (type == 30) {
        return "Payment";
    } else if (type == 70) {
        return "Deposit";
    }
};

export const transactionPayment = (
    debit: number,
    credit: number,
    type: number,
) => {
    if (type == 20) {
        return debit;
    } else if (type == 30) {
        return credit;
    } else if (type == 70) {
        return credit > 0 ? credit : debit;
    }
};

export const formatDate = (dateString: string) => {
    if (!dateString) return "";

    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, "0");
    const month = date.toLocaleDateString("en-US", { month: "short" });
    const year = String(date.getFullYear()).slice(-2);

    return `${day}-${month}-${year}`;
};

// For Check Voucher Date format
export const formatDate2 = (dateString: string) => {
    if (!dateString) return "";

    const date = new Date(dateString);
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const day = String(date.getDate()).padStart(2, "0");
    const year = String(date.getFullYear());

    return `${month} ${day} ${year}`;
};

// For Customer Ledger Date format
export const formatDateShort = (dateString: string) => {
    if (!dateString) return "";

    const date = new Date(dateString);
    const month = date.getMonth() + 1;
    const day = date.getDate();
    const year = String(date.getFullYear()).slice(-2);

    return `${month}/${day}/${year}`;
};
