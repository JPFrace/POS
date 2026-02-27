export interface WithholdingTaxType {
    uuid: string;
    code: string;
    name: string;
}

export interface PayerType {
    id: number;
    name: string;
}

export interface WithholdingTax {
    uuid: string;
    code: string;
    description?: string | null;
    rate: number;
    type?: WithholdingTaxType | null;
    payer_type?: PayerType | null;
    is_inactive?: boolean;
    created_by?: any | null;
}
