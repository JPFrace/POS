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
];
