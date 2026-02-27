import type { BankAccounts } from "./bank-accounts";
import type { Option } from "./form";

export interface ReconciliationSetup {
    bank_account: BankAccounts | null;
    beginning_balance: number | null;
    last_statement_ending_date: string | null;
    ending_balance: number | null;
    ending_date: string | null;
}
