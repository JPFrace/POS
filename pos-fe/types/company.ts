import type { File as FileObject } from "./file";
export interface Company {
    uuid: string;
    name: string;
    tin_no: string;
    address: string;
    phone: string;
    email: string;
    file?: File | FileObject | null;
}
