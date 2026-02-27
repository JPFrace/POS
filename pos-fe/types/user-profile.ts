import type { ResponsibilityCenter } from "./responsibility-center";
import type { userPosition } from "./user-position";

export interface userProfile {
    uuid: string;
    photo: File | string | null;
    name: string;
    email: string;
    contacts: string;
    address: string;
    position: userPosition | null;
    department: ResponsibilityCenter | null;
    role: string;
}
