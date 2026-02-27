import type { User } from "./user";

export interface Reports {
    id?: number;
    uuid: string;
    name: string;
    description?: string | null;
    created_by?: User | null;
    is_inactive?: boolean;
    template: string;
}

