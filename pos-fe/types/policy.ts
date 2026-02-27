export interface Policy {
    uuid: string;
    children: Policy[];
    name: string;
    sort: Number;
    actions: Action[];
}

export interface Action {
    uuid: string;
    identifier: string;
    name: string;
}

export interface Role {
    uuid: string;
    name: string;
    display_name: string;
    actions?: Action[];
    permissions: Permission[];
}

export interface Permission {
    uuid: string;
    action: Action;
}
