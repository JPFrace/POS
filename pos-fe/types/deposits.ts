import type { Option } from "./form";

export interface DepositItem {
    uuid?: string;
    payment_method: Option;
    rate: number;
    contact_idno: string;
    memo: string;
}

export interface Deposit {
    uuid?: string;
    date: string | null;
    or_no: string | null;
    or_no_auto: boolean;
    ref_no: string | null;
    ref_no_auto: boolean;
    remarks: string | null;
    attachment: File | null;
    items: Partial<DepositItem>[];
    cash_in_bank: Option;
}
