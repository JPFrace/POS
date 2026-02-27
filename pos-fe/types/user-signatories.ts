import type {ResponsibilityCenter} from './responsibility-center';
import type {UserPosition} from './user-position';

export interface UserSignatories {
    name: string | null;
    position: UserPosition | null;
    department: ResponsibilityCenter | null;
    e_signature: File | null;
}