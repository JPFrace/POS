import type { AccountCategory } from "./account-category";
import type { Option } from "./form";

export interface AccountType {
    uuid: string;
    name: string;
    description: string | null;
    is_inactive: boolean;
    seq: number | null;
    category: AccountCategory | Option;
}
