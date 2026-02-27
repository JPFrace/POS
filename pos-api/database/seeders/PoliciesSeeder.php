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
