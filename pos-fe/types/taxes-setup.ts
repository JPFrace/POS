import type { calendar } from "./calendar";
import type { Method } from "./method";
import type { Months } from "./months";
import type { Period } from "./period";
import type { Taxes } from "./taxes";

export interface TaxesSetup {
    uuid: string;
    tax: Taxes | null;
    calendar: calendar | null;
    period: Number | null;
    period_obj?: Period | null;
    start_tax_period?: Number | null;
    start_tax_period_obj?: Months | null;
    start_tax_at?: Date | null;
    reporting_method?: string | null;
    reporting_method_obj?: Method | null;

    regno?: string | null;
}
