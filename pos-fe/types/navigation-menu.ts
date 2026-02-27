export interface NavigationMenuItem {
    heading?: string;
    title?: string;
    permissions?: Object;
    pages?: Array<NavigationMenuItem>;
    route?: string;
}
