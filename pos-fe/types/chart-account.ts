import type { AccountClass } from "./account-class";
import type { AccountType } from "./account-types";
import type { AccountUsageType } from "./account-usage-type";
import type { BankAccounts } from "./bank-accounts";

export interface ChartAccount {
    uuid: string;
    code: string;
    name: string;
    description: string;
    type: AccountType;
    class: AccountClass;
    is_inactive: boolean;
    budget: string;
    balance: string;
    parent: ChartAccount | null;
    children: ChartAccount[] | null;
    department: ChartAccount[] | null;
    usage: AccountUsageType[] | null;
    add_as_product: boolean;
    bank: BankAccounts | null;
}
