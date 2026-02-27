import type { ChartAccount } from "./chart-account";
import type { File, Option } from "./form";
import type { Attribute } from "./registration";
import type { Tax } from "./Tax";

export interface Category {
    uuid: string;
    name: string;
}

export interface Product {
    uuid: string;
    name: string;
    sku: string;
    description: string;
    file: File;
    attribute: Partial<Attribute>;
    category: Partial<Category>;
    price: number;
    active: boolean;
    income_account: ChartAccount;
    expense_account: ChartAccount;
    payable_account: ChartAccount;
    withholding_tax: Tax;
    sales_tax: Tax;
}

export interface Form {
    uuid: string;
    title: string;
    description: string;
    file: Partial<File> | null;
    form: Partial<Option> | null;
    shops_category: Partial<Option> | null;
    vendor: Partial<Option> | null;
    price: number;
    active: boolean;
}
