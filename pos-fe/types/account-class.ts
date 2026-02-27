export interface AccountClass {
    uuid: string;
    code: string;
    name: string;
    description?: string | null;
    short_name: string;
    or_printingroup?: boolean;
    is_inactive?: boolean;
}
