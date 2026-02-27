<?php

namespace Database\Seeders;

use App\Enums\Security\Role;
use App\Models\Member;
use App\Models\Permission;
use App\Models\Policy;
use App\Models\User;
use App\Models\UserRole;
use App\Models\Vendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = \App\Models\Role::firstOrCreate([
            'name' => Role::ADMIN->value
        ], [
            'uuid' => Str::uuid(),
            'slug' => str(Role::ADMIN->value)->slug(),
            'name' => Role::ADMIN->value
        ]);

        $user = User::firstOrCreate(['email' => 'admin@gmail.com'], [
            'uuid' => Str::uuid(),
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('secret'),
        ]);

        UserRole::create([
            'uuid' => Str::uuid(),
            'user_id' => $user->id,
            'role_id' => $adminRole->id,
            'rollable_id' => $user->id,
            'rollable_type' => User::class,
        ]);

        Permission::create([
            'uuid' => Str::uuid(),
            'grantable_id' => $adminRole->id,
            'grantable_type' => Role::class,
            'action_id' => Policy::where('name', 'Policies')->first()->actions()->where('name', 'List')->first()->id,
        ]);
    }
}