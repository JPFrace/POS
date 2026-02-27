import type { ChartAccount } from "./chart-account";

export interface BankAccounts {
    uuid: string;
    account_name: string;
    account_number: string;
    bank_name: string;
    bank_code: string;
    chart_account: ChartAccount | null;
    is_inactive: boolean;
}
