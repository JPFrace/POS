export interface Journal {
    uuid: string;
    ref_no: string;
    customer_id: string;
    description: string;
    customer_name: string;
    date: string;
    posted_at: string;
    debit: number;
    credit: number;
    balance: number;
    code: string;
    trans_no: string;
    url?: string;
    chart_account: {
        code: string;
        name: string;
    };
    transactable: {
        url: string;
    };
}

export interface CustomerLedger {
    customer_ledger: Journal[];
    total: {
        debits: number;
        credits: number;
        balance: number;
    };
}

export interface GeneralJournal {
    journals: Journal[];
    total: {
        debits: number;
        credits: number;
    };
}
