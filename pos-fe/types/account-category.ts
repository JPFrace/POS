export interface AccountCategory {
    uuid: string;
    name: string;
    description: string | null;
    is_inactive: boolean;
    seq: number | null;
    normal_balance: "DEBIT" | "CREDIT";
}
