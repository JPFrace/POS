import type { AccountClass } from "./account-class";
import type { ChartAccount } from "./chart-account";
import type { TaxesAgency } from "./taxes-agency";

export interface Tax {
    uuid: string;
    type: string | null;
    tax_agency: TaxesAgency | null;
    code: string | null;
    name?: string | null;
    description?: string | null;
    tax?: string | null;
    rate?: string | null;
    rate_type?: string | null;
    parent: Tax | null;
    children: Tax[] | null;
    class_id?: string | null;
    class: AccountClass | null;
    chart_account?: ChartAccount | null;
    type_obj: object | null;
    rate_type_obj: object | null;
}
