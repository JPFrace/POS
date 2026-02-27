import type { MenuItem } from "~/layouts/default-layout/config/types";

const MainMenuConfig: Array<MenuItem> = [
    {
        pages: [
            {
                heading: "Dashboard",
                route: "/dashboard",
                keenthemesIcon: "element-1 ki-outline",
                bootstrapIcon: "bi-app-indicator",
                permissions: ["Dashboard.View"],
            },
            {
                heading: "Transactions",
                route: "/transactions",
                keenthemesIcon: "delivery-2 ki-outline",
                bootstrapIcon: "bi-person-square",
                permissions: ["Transactions.View"],
            },
            {
                heading: "Reports",
                route: "/reports",
                keenthemesIcon: "book ki-outline",
                bootstrapIcon: "bi-person-square",
                permissions: ["Reports.View"],
            },
        ],
    },

    {
        heading: "Business",
        route: "/business",
        permissions: "business",
        pages: [
            {
                heading: "Receive Money",
                route: "/business/receive-money",
                keenthemesIcon: "dollar ki-outline",
                permissions: ["Business.Receive Money.View"],
            },
            {
                heading: "Make Payments",
                route: "/business/make-payments",
                keenthemesIcon: "credit-cart ki-outline",
                permissions: ["Business.Make Payments.View"],
            },
            {
                heading: "Invoice",
                route: "/business/invoice",
                keenthemesIcon: "receipt-square ki-outline",
                permissions: ["Business.Invoice.View"],
            },
            {
                heading: "Purchase Orders",
                route: "/business/purchase-orders",
                keenthemesIcon: "parcel ki-outline",
                permissions: ["Business.Purchase Orders.View"],
            },
            {
                heading: "Bills",
                route: "/business/bills",
                keenthemesIcon: "bill ki-outline",
                permissions: ["Business.Bills.View"],
            },
        ],
    },

    {
        heading: "PRIISMS Collections",
        route: "/collections",
        permissions: "priisms collections",
        pages: [
            {
                heading: "Transactions",
                route: "/collections/transactions",
                keenthemesIcon: "wallet",
                permissions: [],
            },
        ],
    },

    {
        heading: "Products & Services",
        route: "/products",
        permissions: "products & services",
        pages: [
            {
                heading: "Catalogue",
                route: "/products/catalogue",
                keenthemesIcon: "book-square",
                permissions: ["Products & Services.Catalogue.View"],
            },
            {
                heading: "Categories",
                route: "/products/categories",
                keenthemesIcon: "lots-shopping",
                permissions: ["Products & Services.Categories.View"],
            },
        ],
    },

    {
        heading: "Contacts",
        route: "/contacts",
        permissions: "contacts",
        pages: [
            {
                heading: "Customers",
                route: "/contacts/customers",
                keenthemesIcon: "people ki-outline",
                permissions: ["Contacts.Customers.View"],
            },
            {
                heading: "Vendors",
                route: "/contacts/vendors",
                keenthemesIcon: "people ki-outline",
                permissions: ["Contacts.Vendors.View"],
            },
        ],
    },
    {
        heading: "Budgeting",
        route: "/budgets",
        permissions: "budgeting",
        pages: [
            {
                heading: "Annual Budgets",
                route: "/budgeting/annual-budgets",
                keenthemesIcon: "dollar ki-outline",
                bootstrapIcon: "bi-cash-coin",
                permissions: ["Budgeting.Annual Budgets.View"],
            },
        ],
    },
    {
        heading: "Taxes",
        route: "/taxes",
        permissions: "taxes",
        pages: [
            {
                heading: "Taxes",
                route: "/taxes",
                keenthemesIcon: "picture ki-outline",
                permissions: ["Taxes.Taxes.View"],
            },
        ],
    },
    {
        heading: "Accounting",
        route: "/accounting",
        permissions: "accounting",
        pages: [
            {
                heading: "Chart of Accounts",
                route: "/accounting/chart-of-accounts",
                keenthemesIcon: "text-align-justify-center ki-outline",
                bootstrapIcon: "bi-person-square",
                permissions: ["Accounting.Chart of Accounts.View"],
            },
            {
                heading: "Account Class",
                route: "/accounting/account-class",
                keenthemesIcon: "dollar ki-outline",
                permissions: ["Accounting.Account Class.View"],
            },
            {
                heading: "Account Types",
                route: "/accounting/account-types",
                keenthemesIcon: "note-2 ki-outline",
                permissions: ["Accounting.Account Types.View"],
            },

            {
                heading: "Calendars",
                route: "/accounting/calendars",
                keenthemesIcon: "calendar-2 ki-outline",
                permissions: ["Accounting.Calendars.View"],
            },

            {
                heading: "Dimensions",
                route: "/accounting/dimensions",
                keenthemesIcon: "calendar-2 ki-outline",
                permissions: ["Accounting.Dimensions.View"],
            },

            {
                heading: "Templates",
                route: "/accounting/transaction-templates",
                keenthemesIcon: "picture ki-outline",
                permissions: ["Accounting.Transaction Templates.View"],
            },
        ],
    },

    {
        heading: "Setup",
        route: "/setup",
        permissions: "setup",
        pages: [
            {
                heading: "Company",
                route: "/setup/company",
                keenthemesIcon: "safe-home ki-outline",
                permissions: ["Setup.Company.View"],
            },
            {
                heading: "Departments",
                route: "/setup/departments",
                keenthemesIcon: "safe-home ki-outline",
                permissions: ["Setup.Departments.View"],
            },
            {
                heading: "Report Signatories",
                route: "/setup/report-signatories",
                keenthemesIcon: "badge ki-outline",
                bootstrapIcon: "bi-backpack-fill",
                permissions: ["Setup.Report Signatories.View"],
            },
            {
                heading: "Signatories",
                route: "/setup/user-signatories",
                keenthemesIcon: "badge ki-outline",
                bootstrapIcon: "bi-backpack-fill",
                permissions: ["Setup.Signatories.View"],
            },
            {
                heading: "Payment Methods",
                route: "/setup/payment-types",
                keenthemesIcon: "safe-home ki-outline",
                permissions: ["Setup.Payment Types.View"],
            },
            {
                heading: "Bank Accounts",
                route: "/setup/bank-accounts",
                keenthemesIcon: "safe-home ki-outline",
                permissions: ["Setup.Bank Accounts.View"],
            },
            {
                heading: "Withholding Taxes",
                route: "/setup/withholding-tax",
                keenthemesIcon: "abstract-39 ki-outline",
                permissions: ["Setup.Withholding Taxes.View"],
            },
            {
                heading: "Reports",
                route: "/setup/reports",
                keenthemesIcon: "abstract-39 ki-outline",
                permissions: ["Setup.Setup Reports.View"],
            },
        ],
    },
    {
        heading: "Security",
        route: "/security",
        permissions: "security",
        pages: [
            {
                heading: "Users",
                route: "/security/users",
                keenthemesIcon: "user-square ki-outline",
                bootstrapIcon: "bi-person-square",
                permissions: ["Security.Users.View"],
            },
            {
                heading: "Roles",
                route: "/security/user-roles",
                keenthemesIcon: "people ki-outline",
                permissions: ["Security.Roles.View"],
            },
            {
                heading: "User's Position",
                route: "/security/user-positions",
                keenthemesIcon: "briefcase ki-outline",
                permissions: ["Security.User's Position.View"],
            },
            {
                heading: "Policies",
                route: "/security/policies",
                keenthemesIcon: "key ki-outline",
                bootstrapIcon: "bi-person-square",
                permissions: ["Security.Policies.View"],
            },
            {
                heading: "Access",
                route: "/security/access",
                keenthemesIcon: "user-square ki-outline",
                bootstrapIcon: "bi-person-square",
                permissions: ["Security.Access.View"],
            },
            {
                heading: "Audit Trails",
                route: "/security/audit-trails",
                keenthemesIcon: "subtitle ki-outline",
                bootstrapIcon: "bi-body-text",
                permissions: ["Security.Audit Trails.View"],
            },
        ],
    },
];

export default MainMenuConfig;
