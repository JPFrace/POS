import type { calendar } from "./calendar";
import type { ChartAccount } from "./chart-account";
import type { Option } from "./form";
import type { ResponsibilityCenter } from "./responsibility-center";

export interface BudgetType {
    name: string;
    description: string;
    is_inactive: boolean;
}

export interface Budget {
    uuid?: string;
    name: string;
    description: string;
    department: ResponsibilityCenter | null;
    calendar: calendar | null;
    type: BudgetType | null;
    items: BudgetItem[] | null;
    isPosted: boolean;
    is_inactive: boolean;
}

export interface BudgetItem {
    uuid?: string;
    budget: Budget | null;
    account: ChartAccount | null;
    category: object | Option | null;
    amount: number;    
    description: string;
    isBudgeted: boolean;
    periods?: BudgetPeriod | null;
}

export interface period {
    date: string;
    amount: number;
}

export interface BudgetPeriod {
    uuid?: string;
    period_1: period;
    period_2: period;
    period_3: period;
    period_4: period;
    period_5: period;
    period_6: period;
    period_7: period;
    period_8: period;
    period_9: period;
    period_10: period;
    period_11: period;
    period_12: period;
    total: number;
}