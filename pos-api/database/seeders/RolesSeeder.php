<?php

namespace Database\Seeders;

use App\Enums\Security\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Role::cases() as $role) {
            \App\Models\Role::firstOrCreate([
                'name' => $role->value
            ], [
                'slug' => str($role->value)->slug(),
                'name' => $role->value
            ]);
        }
    }
}
