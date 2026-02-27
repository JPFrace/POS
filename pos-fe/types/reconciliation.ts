import type { ChartAccount } from "./chart-account";

export interface Reconciliation {
    uuid: string | null;
    start_at: string | null;
    end_at: string | null;
    bank_statement_ending_balance: number | null;
    ending_balance: number | null;
    cash_in_bank: ChartAccount | null;
    closed_at: string | null;
    closed_by: User | null;
}
