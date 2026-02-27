import type { NavigationMenuItem as BaseNavigationMenuItem } from "~/types/navigation-menu";

export interface MenuItem{
    keenthemesIcon?: string;
    bootstrapIcon?: string;
}
/* Extend page items */
export interface NavigationMenuPage extends MenuItem {
    title: string;
    route: string;
    permissions?: string[];
}

/* Extend menu item */
export interface NavigationMenuItem extends Omit<BaseNavigationMenuItem, "pages"> {
    pages: NavigationMenuPage[];
}

const NavigationMenu: Array<NavigationMenuItem> = [
    {
        heading: "Customers",
        pages: [
            {
                title: "New Customer",
                route: "/contacts/customers#new",
                keenthemesIcon: "people ki-outline",
                permissions: ["Contacts.Customers.View"],
            },
            {
                title: "Receive Money",
                route: "/business/receive-money",
                keenthemesIcon: "dollar ki-outline",
                permissions: ["Business.Receive Money.View"],
            },
            {
                title: "New Invoice",
                route: "/business/invoice",
                keenthemesIcon: "receipt-square ki-outline",
                permissions: ["Business.Invoice.View"],
            },
        ],
    },
    {
        heading: "Vendors",
        pages: [
            {
                title: "New Vendor",
                route: "/contacts/vendors#new",
                keenthemesIcon: "people ki-outline",
                permissions: ["Contacts.Vendors.View"],
            },
            {
                title: "Make Payment",
                route: "/business/make-payments",
                keenthemesIcon: "credit-cart ki-outline",
                permissions: ["Business.Make Payments.View"],
            },
            {
                title: "New Bill",
                route: "/business/bills",
                keenthemesIcon: "bill ki-outline",
                permissions: ["Business.Bills.View"],
            },
            {
                title: "New Purchase Order",
                route: "/business/purchase-orders",
                keenthemesIcon: "parcel ki-outline",
                permissions: ["Business.Purchase Orders.View"],
            },
        ],
    },
    {
        heading: "Others",
        pages: [
            {
                title: "Bank Deposits",
                route: "/business/bank-deposits",
                keenthemesIcon: "safe-home ki-outline",
                permissions: ["Business.Bank Deposits.View"],
            },
            {
                title: "Journal Entry",
                route: "/business/journal-entry",
                keenthemesIcon: "book-open ki-outline",
                permissions: ["Business.Journal Entry.View"],
            },
        ],
    },
];

export default NavigationMenu;
