import type { ChartAccount } from "./chart-account";

export interface JournalItem {
    uuid?: string;
    account: ChartAccount | null;
    debit: number | null;
    credit: number | null;
    description: string | null;
    contact: any | null;
    attachment: File | null;
    active: boolean | null;
}

export interface JournalEntry {
    uuid?: string;
    date: string | null;
    je_no: string | null;
    je_no_auto: boolean;
    ref_no: string | null;
    ref_no_auto: boolean;
    memo: string | null;
    attachment: File | null;
    status: JournalEntryStatus;
    items: Partial<JournalItem>[];
}

export interface JournalEntryStatus {
    uuid?: string;
    name: string;
    description: string;
}
