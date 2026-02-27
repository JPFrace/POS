export interface User {
    name: string;
    role: string;
}
export interface AuditTrails {
    user_type: string;
    event: string;
    auditable_type: string;
    old_values: string;
    new_values: string;
    url: string;
    ip_address: string;
    user_agent: string;
    tags: string;
    created_at: string;
    updated_at: string;
    user: User;
}
