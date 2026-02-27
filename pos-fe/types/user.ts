import type { Collection } from "lodash";

export interface User {
    uuid: string;
    name: string;
    email: string;
    roles: string | string[];
    roles_assigned: string | string[];
    password: string | null;
    default_password: boolean;
    send_email_account: boolean;
    permissions: Object;
}
