import type { Bookmark } from "./bookmark";
import type { ChartAccount } from "./chart-account";
import type { User } from "./user";

export interface Reports {
    id?: number;
    uuid: string;
    name: string;
    description?: string | null;
    created_by?: User | null;
    is_inactive?: boolean;
    template: string;
    bookmark?: Bookmark | null;
}

export interface StatementIncomeExpenseRow extends ChartAccount {
    beginning_balance: number;
    current_month: number;
    year_to_date: number;
    budget_to_date: number;
}
