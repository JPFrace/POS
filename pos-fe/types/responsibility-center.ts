export interface ResponsibilityCenter {
    uuid: string;
    id: number;
    code: string;
    name: string;
    description?: string | null;
    created_by?: any | null;
    is_inactive?: boolean;
}
