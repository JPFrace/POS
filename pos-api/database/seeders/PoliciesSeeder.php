<?php

namespace Database\Seeders;

use App\Models\Policy;
use Illuminate\Database\Seeder;

class PoliciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sort = 1;
        $policies = [
            [
                'name' => 'Dashboard',
                'sort' => $sort++,
                'actions' => [
                    'View',
                ]
            ],
            [
                'name' => 'Transactions',
                'sort' => $sort++,
                'actions' => [
                    'View',
                    'List'
                ]
            ],
            [
                'name' => 'Reports',
                'sort' => $sort++,
                'actions' => [
                    'View',
                    'List'
                ]
            ],
            [
                'name' => 'Business',
                'sort' => $sort++,
                'children' => [
                    [
                        'name' => 'Journal Entry',
                        'sort' => $sort++,
                        'actions' => [
                            'View',
                            'Create',
                            'Edit',
                            'Delete',
                            'Print',
                            'List'
                        ]
                    ],
                    [
                        'name' => 'Receive Money',
                        'sort' => $sort++,
                        'actions' => [
                            'View',
                            'Create',
                            'Edit',
                            'Delete',
                            'Print',
                            'List'
                        ]
                    ],
                    [
                        'name' => 'Make Payments',
                        'sort' => $sort++,
                        'actions' => [
                            'View',
                            'Create',
                            'Edit',
                            'Delete',
                            'Print',
                            'List'
                        ]
                    ],
                    [
                        'name' => 'Invoice',
                        'sort' => $sort++,
                        'actions' => [
                            'View',
                            'Create',
                            'Edit',
                            'Delete',
                            'Print',
                            'List'
                        ]
                    ],
                    [
                        'name' => 'Purchase Orders',
                        'sort' => $sort++,
                        'actions' => [
                            'View',
                            'Create',
                            'Edit',
                            'Delete',
                            'Print',
                            'List'
                        ]
                    ],
                    [
                        'name' => 'Bills',
                        'sort' => $sort++,
                        'actions' => [
                            'View',
                            'Create',
                            'Edit',
                            'Delete',
                            'Print',
                            'List'
                        ]
                    ],
                    [
                        'name' => 'Bank Deposits',
                        'sort' => $sort++,
                        'actions' => [
                            'View',
                            'Create',
                            'Edit',
                            'Delete',
                            'Print',
                            'List'
                        ]
                    ],
                ]
            ],
            [
                'name' => 'Budgeting',
                'sort' => $sort++,
                'children' => [
                    [
                        'name' => 'Annual Budgets',
                        'sort' => $sort++,
                        'actions' => [
                            'View',
                            'Create',
                            'Edit',
                            'Delete',
                            'Print',
                            'List',
                            'Post',
                            'Unpost'
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Taxes',
                'sort' => $sort++,
                'children' => [
                    [
                        'name' => 'Taxes',
                        'sort' => $sort++,
                        'actions' => [
                            'View',
                            'Create',
                            'Edit',
                            'Delete',
                            'Print',
                            'List'
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Accounting',
                'sort' => $sort++,
                'children' => [
                    [
                        'name' => 'Chart of Accounts',
                        'sort' => $sort++,
                        'actions' => [
                            'View',
                            'Create',
                            'Edit',
                            'Delete',
                            'Print',
                            'List'
                        ]
                    ],
                    [
                        'name' => 'Account Class',
                        'sort' => $sort++,
                        'actions' => [
                            'View',
                            'Create',
                            'Edit',
                            'Delete',
                            'Print',
                            'List'
                        ]
                    ],
                    [
                        'name' => 'Account Types',
                        'sort' => $sort++,
                        'actions' => [
                            'View',
                            'Create',
                            'Edit',
                            'Delete',
                            'Print',
                            'List'
                        ]
                    ],
                    [
                        'name' => 'Calendars',
                        'sort' => $sort++,
                        'actions' => [
                            'View',
                            'Create',
                            'Edit',
                            'Delete',
                            'Print',
                            'List'
                        ]
                    ],
                    [
                        'name' => 'Dimensions',
                        'sort' => $sort++,
                        'actions' => [
                            'View',
                            'Create',
                            'Edit',
                            'Delete',
                            'Print',
                            'List'
                        ]
                    ],
                    [
                        'name' => 'Transaction Templates',
                        'sort' => $sort++,
                        'actions' => [
                            'View',
                            'Create',
                            'Edit',
                            'Delete',
                            'Print',
                            'List'
                        ]
                    ],
                ],
            ],
            [
                'name' => 'Setup',
                'sort' => $sort++,
                'children' => [
                    [
                        'name' => 'Company',
                        'sort' => $sort++,
                        'actions' => [
                            'View',
                            'Create',
                            'Edit',
                            'Delete',
                            'Print',
                            'List'
                        ]
                    ],
                    [
                        'name' => 'Departments',
                        'sort' => $sort++,
                        'actions' => [
                            'View',
                            'Create',
                            'Edit',
                            'Delete',
                            'Print',
                            'List'
                        ]
                    ],
                    [
                        'name' => 'Report Signatories',
                        'sort' => $sort++,
                        'actions' => [
                            'View',
                            'Create',
                            'Edit',
                            'Delete',
                            'Print',
                            'List'
                        ]
                    ],
                    [
                        'name' => 'Signatories',
                        'sort' => $sort++,
                        'actions' => [
                            'View',
                            'Create',
                            'Edit',
                            'Delete',
                            'Print',
                            'List'
                        ]
                    ],
                    [
                        'name' => 'Payment Types',
                        'sort' => $sort++,
                        'actions' => [
                            'View',
                            'Create',
                            'Edit',
                            'Delete',
                            'Print',
                            'List'
                        ]
                    ],
                    [
                        'name' => 'Bank Accounts',
                        'sort' => $sort++,
                        'actions' => [
                            'View',
                            'Create',
                            'Edit',
                            'Delete',
                            'Print',
                            'List'
                        ]
                    ],
                    [
                        'name' => 'Withholding Taxes',
                        'sort' => $sort++,
                        'actions' => [
                            'View',
                            'Create',
                            'Edit',
                            'Delete',
                            'Print',
                            'List'
                        ]
                    ],
                    [
                        'name' => 'Change Password On Profile',
                        'sort' => $sort++,
                        'actions' => [
                            'View',
                            'Edit',

                        ]
                    ],
                    [
                        'name' => 'Change Password On User List',
                        'sort' => $sort++,
                        'actions' => [
                            'View',
                            'Edit',
                        ]
                    ],
                    [
                        'name' => 'Setup Reports',
                        'sort' => $sort++,
                        'actions' => [
                            'View',
                            'Create',
                            'Edit',
                            'Delete',
                            'Print',
                            'List'
                        ]
                    ]
                ]
            ],
            [
                'name' => "Security",
                "sort" => $sort++,
                "children" => [
                    [
                        "name" => "Users",
                        "sort" => $sort++,
                        "actions" => [
                            'View',
                            'Create',
                            'Edit',
                            'Delete',
                            'Print',
                            'List'
                        ]
                    ],
                    [
                        "name" => "Roles",
                        "sort" => $sort++,
                        "actions" => [
                            'View',
                            'Create',
                            'Edit',
                            'Delete',
                            'Print',
                            'List'
                        ]
                    ],
                    [
                        "name" => "User's Position",
                        "sort" => $sort++,
                        "actions" => [
                            'View',
                            'Create',
                            'Edit',
                            'Delete',
                            'Print',
                            'List'
                        ]
                    ],
                    [
                        "name" => "Policies",
                        "sort" => $sort++,
                        "actions" => [
                            'View',
                            'Create',
                            'Edit',
                            'Delete',
                            'Print',
                            'List'
                        ]
                    ],
                    [
                        "name" => "Access",
                        "sort" => $sort++,
                        "actions" => [
                            'View',
                            'Create',
                            'Edit',
                            'Delete',
                            'Print',
                            'List'
                        ]
                    ],
                    [
                        "name" => "Audit Trails",
                        "sort" => $sort++,
                        "actions" => [
                            'View',
                            'Create',
                            'Edit',
                            'Delete',
                            'Print',
                            'List'
                        ]
                    ]
                ]
            ],
            [
                'name' => 'PRIISMS Collections',
                'sort' => $sort++,
                'children' => [
                    [
                        'name' => 'External Transaction',
                        'sort' => $sort++,
                        'actions' => [
                            'View',
                            'Create',
                            'Edit',
                            'Delete',
                            'Print',
                            'List'
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Products & Services',
                'sort' => $sort++,
                'children' => [
                    [
                        'name' => 'Catalogue',
                        'sort' => $sort++,
                        'actions' => [
                            'View',
                            'Create',
                            'Edit',
                            'Delete',
                            'Print',
                            'List'
                        ]
                    ],
                    [
                        'name' => 'Categories',
                        'sort' => $sort++,
                        'actions' => [
                            'View',
                            'Create',
                            'Edit',
                            'Delete',
                            'Print',
                            'List'
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Contacts',
                'sort' => $sort++,
                'children' => [
                    [
                        'name' => 'Customers',
                        'sort' => $sort++,
                        'actions' => [
                            'View',
                            'Create',
                            'Edit',
                            'Delete',
                            'Print',
                            'List'
                        ]
                    ],
                    [
                        'name' => 'Vendors',
                        'sort' => $sort++,
                        'actions' => [
                            'View',
                            'Create',
                            'Edit',
                            'Delete',
                            'Print',
                            'List'
                        ]
                    ]
                ]
            ],

        ];

        foreach ($policies as $policy) {
            $policyModel = Policy::firstOrCreate([
                "name" => $policy["name"]
            ], [
                "sort" => $policy["sort"] ?? 0,
                'policy_id' => null,
            ]);

            if (!empty($policy['children'])) {
                $this->children($policy['children'], $policyModel);
            }
            if (!empty($policy["actions"])) {
                foreach ($policy["actions"] as $action) {
                    $policyModel->actions()->firstOrCreate(["name" => $action]);
                }
            }
        }
    }

    protected function children(array $policies = [], Policy $policyModel)
    {
        foreach ($policies as $policy) {
            $childPolicyModel = $policyModel->children()->firstOrCreate([
                "name" => $policy["name"],
            ], [
                "sort" => $policy["sort"] ?? 0
            ]);

            if (!empty($policy['children'])) {
                return $this->children($policy['children'], $childPolicyModel);
            }

            if (!empty($policy["actions"])) {
                foreach ($policy["actions"] as $action) {
                    $childPolicyModel->actions()->firstOrCreate(["name" => $action]);
                }
            }
        }
    }
}
