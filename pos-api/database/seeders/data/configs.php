<?php

return [
    // ========================
    // DASHBOARD
    // ========================
    [
        'name' => 'Dashboard',
        'slug' => 'dashboard',
        'type' => 'none',
        'children' => [
            [
                'name' => 'Widgets',
                'slug' => 'dashboard_widgets',
                'type' => 'json:multi',
                'options' => json_encode([
                    ['value' => 'overview', 'label' => 'Overview'],
                    ['value' => 'sales', 'label' => 'Sales'],
                    ['value' => 'expenses', 'label' => 'Expenses'],
                    ['value' => 'tasks', 'label' => 'Tasks'],
                ]),
                'value' => '',
            ],
            [
                'name' => 'Default View',
                'slug' => 'dashboard_default_view',
                'type' => 'json:single',
                'options' => json_encode([
                    ['value' => 'overview', 'label' => 'Overview'],
                    ['value' => 'sales', 'label' => 'Sales'],
                    ['value' => 'expenses', 'label' => 'Expenses'],
                    ['value' => 'tasks', 'label' => 'Tasks'],
                ]),
                'value' => '',
            ],
        ],
    ],

    // ========================
    // TRANSACTIONS
    // ========================
    [
        'name' => 'Transactions',
        'slug' => 'transactions',
        'type' => 'none',
        'children' => [
            [
                'name' => 'Recent Transactions',
                'slug' => 'transactions_recent',
                'type' => 'integer',
                'value' => '',
            ],
            [
                'name' => 'Auto Reconcile',
                'slug' => 'transactions_auto_reconcile',
                'type' => 'boolean',
                'value' => '',
            ],
            [
                'name' => 'Auto Post',
                'slug' => 'transactions_auto_post',
                'type' => 'boolean',
                'value' => '',
            ],
        ],
    ],

    // ========================
    // REPORTS
    // ========================
    [
        'name' => 'Reports',
        'slug' => 'reports',
        'type' => 'none',
        'children' => [
            [
                'name' => 'Financial Reports',
                'slug' => 'reports_financial',
                'type' => 'json:single',
                'options' => json_encode([
                    ['value' => 'balance_sheet', 'label' => 'Balance Sheet'],
                    ['value' => 'income_statement', 'label' => 'Income Statement'],
                    ['value' => 'cash_flow', 'label' => 'Cash Flow'],
                ]),
                'value' => '',
            ],
            [
                'name' => 'Custom Reports',
                'slug' => 'reports_custom',
                'type' => 'json:single',
                'options' => json_encode([]),
                'value' => '',
            ],
            [
                'name' => 'Export Format',
                'slug' => 'reports_export_format',
                'type' => 'json:single',
                'options' => json_encode([
                    ['value' => 'pdf', 'label' => 'PDF'],
                    ['value' => 'excel', 'label' => 'Excel'],
                    ['value' => 'csv', 'label' => 'CSV'],
                ]),
                'value' => '',
            ],
            ['name' => 'Show Zero Amounts', 'slug' => 'reports_show_zero_amounts', 'type' => 'boolean', 'value' => ''],

        ],
    ],

    // ========================
    // BUSINESS
    // ========================
    [
        'name' => 'Business',
        'slug' => 'business',
        'type' => 'none',
        'children' => [
            [
                'name' => 'Journal Entry',
                'slug' => 'business_journal_entry',
                'type' => 'none',
                'children' => [
                    ['name' => 'Journal Number', 'slug' => 'business_journal_entry_number', 'type' => 'integer', 'use_prefix' => 1, 'prefix' => '{M}#', 'use_suffix' => 0, 'value' => '000'],
                    [
                        'name' => 'Journal Number Reset',
                        'slug' => 'business_journal_entry_number_reset',
                        'type' => 'json:single',
                        'options' => json_encode([
                            ['value' => 'm', 'label' => 'Monthly'],
                            ['value' => 'y', 'label' => 'Yearly'],
                            ['value' => 'n', 'label' => 'Never'],
                        ]),
                        'value' => 'm',
                    ],
                    ['name' => 'Reference Number', 'slug' => 'business_journal_entry_reference', 'type' => 'integer', 'use_prefix' => 1, 'prefix' => 'JV-{YYYY}-', 'use_suffix' => 0, 'value' => '000'],
                    [
                        'name' => 'Reference Number Reset',
                        'slug' => 'business_journal_entry_reference_reset',
                        'type' => 'json:single',
                        'options' => json_encode([
                            ['value' => 'm', 'label' => 'Monthly'],
                            ['value' => 'y', 'label' => 'Yearly'],
                            ['value' => 'n', 'label' => 'Never'],
                        ]),
                        'value' => 'y',
                    ],
                    ['name' => 'Auto Print After Save', 'slug' => 'business_journal_entry_auto_print', 'type' => 'boolean', 'value' => ''],
                    ['name' => 'Enforce Fiscal Period Lock', 'slug' => 'business_journal_entry_enforce_fiscal_period_lock', 'type' => 'boolean', 'value' => '1'],
                ],
            ],
            [
                'name' => 'Receive Money',
                'slug' => 'business_receive_money',
                'type' => 'none',
                'children' => [
                    ['name' => 'Receipt Number', 'slug' => 'business_receive_money_number', 'type' => 'integer', 'use_prefix' => 1, 'use_suffix' => 1, 'value' => ''],
                    ['name' => 'Reference Number', 'slug' => 'business_receive_money_reference', 'type' => 'integer', 'use_prefix' => 1, 'use_suffix' => 1, 'value' => ''],
                    ['name' => 'Auto Print After Save', 'slug' => 'business_receive_money_auto_print', 'type' => 'boolean', 'value' => ''],
                    ['name' => 'Enforce Fiscal Period Lock', 'slug' => 'business_receive_money_enforce_fiscal_period_lock', 'type' => 'boolean', 'value' => '1'],
                    [
                        'name' => 'Default Payment Method',
                        'slug' => 'business_receive_default_payment_method',
                        'type' => 'json:single',
                        'value' => 'cd8c0267-b0cd-48a1-ab6f-fd09629d852d',
                        'options' => json_encode([
                            ['value' => 'cd8c0267-b0cd-48a1-ab6f-fd09629d852d', 'label' => 'Check'],
                        ])
                    ],
                    [
                        'name' => 'Default Cash In Bank',
                        'slug' => 'business_receive_default_cash_in_bank',
                        'type' => 'json:single',
                        'value' => 'b9790cad-55c6-11f0-a222-60452e044641',
                        'options' => json_encode([
                            ['value' => 'b9790cad-55c6-11f0-a222-60452e044641', 'label' => 'Cash in Bank-BPI'],
                        ])
                    ],
                ],
            ],
            [
                'name' => 'Make Payments',
                'slug' => 'business_make_payments',
                'type' => 'none',
                'children' => [
                    ['name' => 'Reference Number', 'slug' => 'business_make_payments_reference', 'type' => 'integer', 'use_prefix' => 1, 'prefix' => 'DV-{YYYY}-', 'use_suffix' => 0, 'value' => '000'],
                    [
                        'name' => 'Reference Number Reset',
                        'slug' => 'business_make_payments_reference_reset',
                        'type' => 'json:single',
                        'options' => json_encode([
                            ['value' => 'm', 'label' => 'Monthly'],
                            ['value' => 'y', 'label' => 'Yearly'],
                            ['value' => 'n', 'label' => 'Never'],
                        ]),
                        'value' => 'y',
                    ],
                    ['name' => 'DV Number', 'slug' => 'business_make_payments_disbursement_number', 'type' => 'string', 'use_prefix' => 1, 'prefix' => '{PMETHOD}', 'use_suffix' => 0, 'value' => '000'],
                    ['name' => 'Check Number', 'slug' => 'business_make_payments_check_number', 'type' => 'string', 'use_prefix' => 1, 'prefix' => '{BANK}', 'use_suffix' => 0, 'value' => '0000000000'],
                    ['name' => 'Enforce Fiscal Period Lock', 'slug' => 'business_make_payments_enforce_fiscal_period_lock', 'type' => 'boolean', 'value' => '1'],
                    [
                        'name' => 'Default Payment Method',
                        'slug' => 'business_make_payments_default_payment_method',
                        'type' => 'json:single',
                        'value' => 'cd8c0267-b0cd-48a1-ab6f-fd09629d852d',
                        'options' => json_encode([
                            ['value' => 'cd8c0267-b0cd-48a1-ab6f-fd09629d852d', 'label' => 'Check'],
                        ])
                    ],
                    [
                        'name' => 'Default Cash In Bank',
                        'slug' => 'business_make_payments_default_cash_in_bank',
                        'type' => 'json:single',
                        'value' => 'b9790cad-55c6-11f0-a222-60452e044641',
                        'options' => json_encode([
                            ['value' => 'b9790cad-55c6-11f0-a222-60452e044641', 'label' => 'Cash in Bank-BPI'],
                        ])
                    ],
                ],
            ],
            [
                'name' => 'Invoice',
                'slug' => 'business_invoice',
                'type' => 'none',
                'children' => [
                    ['name' => 'Invoice Number', 'slug' => 'business_invoice_number', 'type' => 'integer', 'use_prefix' => 1, 'use_suffix' => 1, 'value' => ''],
                    [
                        'name' => 'Invoice Number Reset',
                        'slug' => 'business_invoice_number_reset',
                        'type' => 'json:single',
                        'options' => json_encode([
                            ['value' => 'm', 'label' => 'Monthly'],
                            ['value' => 'y', 'label' => 'Yearly'],
                            ['value' => 'n', 'label' => 'Never'],
                        ]),
                        'value' => 'y',
                    ],
                    ['name' => 'Reference Number', 'slug' => 'business_invoice_reference', 'type' => 'integer', 'use_prefix' => 1, 'use_suffix' => 1, 'value' => ''],
                    ['name' => 'Auto Print After Save', 'slug' => 'business_invoice_auto_print', 'type' => 'boolean', 'value' => ''],
                ],
            ],
            [
                'name' => 'Bills',
                'slug' => 'business_bills',
                'type' => 'none',
                'children' => [
                    ['name' => 'Bill Number Format', 'slug' => 'business_bills_number', 'type' => 'integer', 'use_prefix' => 1, 'use_suffix' => 1, 'value' => ''],
                    [
                        'name' => 'Bill Number Reset',
                        'slug' => 'business_bills_number_reset',
                        'type' => 'json:single',
                        'options' => json_encode([
                            ['value' => 'm', 'label' => 'Monthly'],
                            ['value' => 'y', 'label' => 'Yearly'],
                            ['value' => 'n', 'label' => 'Never'],
                        ]),
                        'value' => 'y',
                    ],
                    ['name' => 'Reference Number', 'slug' => 'business_bills_reference', 'type' => 'integer', 'use_prefix' => 1, 'use_suffix' => 1, 'value' => ''],
                    ['name' => 'Auto Print After Save', 'slug' => 'business_bills_auto_print', 'type' => 'boolean', 'value' => ''],
                ],
            ],
            [
                'name' => 'Purchase Orders',
                'slug' => 'business_purchase_orders',
                'type' => 'none',
                'children' => [
                    ['name' => 'Purchase Order Number', 'slug' => 'business_purchase_orders_number', 'type' => 'integer', 'use_prefix' => 1, 'use_suffix' => 1, 'value' => ''],
                    [
                        'name' => 'Purchase Order Number Reset',
                        'slug' => 'business_purchase_orders_number_reset',
                        'type' => 'json:single',
                        'options' => json_encode([
                            ['value' => 'm', 'label' => 'Monthly'],
                            ['value' => 'y', 'label' => 'Yearly'],
                            ['value' => 'n', 'label' => 'Never'],
                        ]),
                        'value' => 'y',
                    ],
                    ['name' => 'Reference Number', 'slug' => 'business_purchase_orders_reference', 'type' => 'integer', 'use_prefix' => 1, 'use_suffix' => 1, 'value' => ''],
                    ['name' => 'Auto Print After Save', 'slug' => 'business_purchase_orders_auto_print', 'type' => 'boolean', 'value' => ''],
                ],
            ],
            [
                'name' => 'Bank Deposits',
                'slug' => 'business_bank_deposits',
                'type' => 'none',
                'children' => [
                    ['name' => 'Reference Number', 'slug' => 'business_bank_deposits_reference', 'type' => 'string', 'use_prefix' => 1, 'use_suffix' => 1, 'value' => ''],
                ],
            ],
        ],
    ],

    // ========================
    // BUDGETS
    // ========================
    [
        'name' => 'Budgets',
        'slug' => 'budgets',
        'type' => 'none',
        'children' => [
            [
                'name' => 'Allotments',
                'slug' => 'budgets_allotments',
                'type' => 'none',
                'children' => [
                    ['name' => 'Default Fiscal Year', 'slug' => 'budgets_allotments_fiscal_year', 'type' => 'string', 'value' => ''],
                ],
            ],
        ],
    ],

    // ========================
    // ACCOUNTING
    // ========================
    [
        'name' => 'Accounting',
        'slug' => 'accounting',
        'type' => 'none',
        'children' => [
            [
                'name' => 'Chart of Accounts',
                'slug' => 'accounting_chart_of_accounts',
                'type' => 'none',
                'children' => [
                    [
                        'name' => 'Default Currency',
                        'slug' => 'accounting_chart_of_accounts_currency',
                        'type' => 'json:single',
                        'options' => json_encode([
                            ['value' => 'USD', 'label' => 'USD'],
                            ['value' => 'EUR', 'label' => 'EUR'],
                            ['value' => 'PHP', 'label' => 'PHP'],
                        ]),
                        'value' => '',
                    ],
                    [
                        'name' => 'Enable creating Product after Chart Account Entry',
                        'slug' => 'accounting_add_product_on_chart_account_entry',
                        'type' => 'boolean',
                        'value' => '',
                    ],
                ],
            ],
            [
                'name' => 'Account Class',
                'slug' => 'accounting_account_class',
                'type' => 'json:single',
                'options' => json_encode([
                    ['value' => 'assets', 'label' => 'Assets'],
                    ['value' => 'liabilities', 'label' => 'Liabilities'],
                    ['value' => 'equity', 'label' => 'Equity'],
                    ['value' => 'income', 'label' => 'Income'],
                    ['value' => 'expenses', 'label' => 'Expenses'],
                ]),
                'value' => '',
                'children' => [
                    ['name' => 'Default Class', 'slug' => 'accounting_account_class_default', 'type' => 'json:single', 'value' => ''],
                ],
            ],
            [
                'name' => 'Account Types',
                'slug' => 'accounting_account_types',
                'type' => 'json:single',
                'options' => json_encode([
                    ['value' => 'cash', 'label' => 'Cash'],
                    ['value' => 'bank', 'label' => 'Bank'],
                    ['value' => 'receivables', 'label' => 'Receivables'],
                    ['value' => 'payables', 'label' => 'Payables'],
                ]),
                'value' => '',
                'children' => [
                    ['name' => 'Default Account Type', 'slug' => 'accounting_account_types_default', 'type' => 'json:single', 'value' => ''],
                ],
            ],
            [
                'name' => 'Calendars',
                'slug' => 'accounting_calendars',
                'type' => 'none',
                'children' => [
                    ['name' => 'Default Calendar', 'slug' => 'accounting_calendars_default', 'type' => 'string', 'value' => ''],
                ],
            ],
        ],
    ],

    // ========================
    // CONTACTS
    // ========================
    [
        'name' => 'Contacts',
        'slug' => 'contacts',
        'type' => 'none',
        'children' => [
            [
                'name' => 'Vendors',
                'slug' => 'contacts_vendors',
                'type' => 'none',
                'children' => [
                    ['name' => 'Default Terms', 'slug' => 'contacts_vendors_terms', 'type' => 'string', 'value' => ''],
                ],
            ],
            [
                'name' => 'Customers',
                'slug' => 'contacts_customers',
                'type' => 'none',
                'children' => [
                    ['name' => 'Default Terms', 'slug' => 'contacts_customers_terms', 'type' => 'string', 'value' => ''],
                ],
            ],
        ],
    ],

    // ========================
    // PRODUCTS & SERVICES
    // ========================
    [
        'name' => 'Products & Services',
        'slug' => 'products_services',
        'type' => 'none',
        'children' => [
            [
                'name' => 'Catalogue',
                'slug' => 'products_services_catalogue',
                'type' => 'none',
                'children' => [
                    ['name' => 'Default Product Code', 'slug' => 'products_services_catalogue_code', 'type' => 'string', 'value' => ''],
                ],
            ],
            [
                'name' => 'Categories',
                'slug' => 'products_services_categories',
                'type' => 'none',
                'children' => [
                    ['name' => 'Default Category', 'slug' => 'products_services_categories_default', 'type' => 'string', 'value' => ''],
                ],
            ],
        ],
    ],

    // ========================
    // PRIISMS Collections
    // ========================
    [
        'name' => 'PRIISMS Collections',
        'slug' => 'priisms_collections',
        'type' => 'none',
        'children' => [
            [
                'name' => 'Transactions',
                'slug' => 'priisms_collections_transactions',
                'type' => 'json:single',
                'options' => json_encode([
                    ['value' => 'collections', 'label' => 'Collections'],
                    ['value' => 'payments', 'label' => 'Payments'],
                    ['value' => 'adjustments', 'label' => 'Adjustments'],
                ]),
                'value' => '',
            ],
        ],
    ],

    // ========================
    // SETUP
    // ========================
    [
        'name' => 'Setup',
        'slug' => 'setup',
        'type' => 'none',
        'children' => [
            [
                'name' => 'Company',
                'slug' => 'setup_company',
                'type' => 'none',
                'children' => [
                    ['name' => 'Company Name', 'slug' => 'setup_company_name', 'type' => 'string', 'value' => ''],
                    ['name' => 'Address', 'slug' => 'setup_company_address', 'type' => 'string', 'value' => ''],
                    ['name' => 'Contact Number', 'slug' => 'setup_company_contact', 'type' => 'string', 'value' => ''],
                ],
            ],
            [
                'name' => 'Departments',
                'slug' => 'setup_departments',
                'type' => 'none',
                'children' => [
                    ['name' => 'Default Department', 'slug' => 'setup_departments_default', 'type' => 'string', 'value' => ''],
                ],
            ],
            [
                'name' => 'Report Signatories',
                'slug' => 'setup_report_signatories',
                'type' => 'none',
                'children' => [
                    ['name' => 'Default Report Signatory', 'slug' => 'setup_report_signatories_default', 'type' => 'string', 'value' => ''],
                ],
            ],
            [
                'name' => 'Signatories',
                'slug' => 'setup_signatories',
                'type' => 'none',
                'children' => [
                    ['name' => 'Authorized Signatory', 'slug' => 'setup_signatories_authorized', 'type' => 'string', 'value' => ''],
                ],
            ],
            [
                'name' => 'Payment Types',
                'slug' => 'setup_payment_types',
                'type' => 'none',
                'children' => [
                    ['name' => 'Default Payment Type', 'slug' => 'setup_payment_types_default', 'type' => 'string', 'value' => ''],
                ],
            ],
            [
                'name' => 'Bank Accounts',
                'slug' => 'setup_bank_accounts',
                'type' => 'none',
                'children' => [
                    ['name' => 'Default Bank', 'slug' => 'setup_bank_accounts_default_bank', 'type' => 'string', 'value' => ''],
                    ['name' => 'Default Account Number', 'slug' => 'setup_bank_accounts_default_number', 'type' => 'string', 'value' => ''],
                ],
            ],
            [
                'name' => 'Withholding Taxes',
                'slug' => 'setup_withholding_tax',
                'type' => 'none',
                'children' => [
                    ['name' => 'Default Tax Rate', 'slug' => 'setup_withholding_tax_rate', 'type' => 'integer', 'value' => ''],
                ],
            ],
        ],
    ],

    // ========================
    // SECURITY
    // ========================
    [
        'name' => 'Security',
        'slug' => 'security',
        'type' => 'none',
        'children' => [
            [
                'name' => 'Users',
                'slug' => 'security_users',
                'type' => 'none',
                'children' => [
                    ['name' => 'Default Role', 'slug' => 'security_users_default_role', 'type' => 'string', 'value' => ''],
                ],
            ],
            [
                'name' => 'Roles',
                'slug' => 'security_roles',
                'type' => 'none',
                'children' => [
                    ['name' => 'Default Role Permissions', 'slug' => 'security_roles_default_permissions', 'type' => 'json:single', 'value' => ''],
                ],
            ],
            [
                'name' => "User's Position",
                'slug' => 'security_users_position',
                'type' => 'none',
                'children' => [
                    ['name' => 'Default Position', 'slug' => 'security_users_position_default', 'type' => 'string', 'value' => ''],
                ],
            ],
            [
                'name' => 'Policies',
                'slug' => 'security_policies',
                'type' => 'none',
                'children' => [
                    ['name' => 'Password Policy', 'slug' => 'security_policies_password', 'type' => 'string', 'value' => ''],
                ],
            ],
            [
                'name' => 'Access',
                'slug' => 'security_access',
                'type' => 'none',
                'children' => [
                    ['name' => 'Session Timeout', 'slug' => 'security_access_timeout', 'type' => 'integer', 'value' => ''],
                ],
            ],
            [
                'name' => 'Audit Trails',
                'slug' => 'security_audit_trails',
                'type' => 'none',
                'children' => [
                    ['name' => 'Retention Period', 'slug' => 'security_audit_trails_retention', 'type' => 'integer', 'value' => ''],
                ],
            ],
        ],
    ],
];
