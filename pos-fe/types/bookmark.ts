import type { Reports } from "./reports";

export interface Bookmark {
    uuid: String;
    date_from: String;
    date_to: String;
    report?: Reports | String | null;
    name?: String;
    group?: String;
}
