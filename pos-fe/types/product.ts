import type { File } from "./file";
import type { Option } from "~/types/form";

export interface Product {
    uuid: string;
    sku: string;
    name: string;
    description: string;
    price: number | null;
    file: File | null;
    purchase_description: string;
    cost: number | null;
    expense: Option | null;
    vendor: Option | null;
    payable: Option | null;
    income: Option | null;
    receivable: Option | null;
    category: Option | null;
    depository: Option | null;
    sales_tax: Option | null;
    withholding_tax: Option | null;
}
