<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        Role::create(['name' => 'super_admin']);
        Role::create(['name' => 'restaurant_owner']);
        Role::create(['name' => 'restaurant_staff']);

        // Create super admin user
        $admin = User::create([
            'name'     => 'Super Admin',
            'email'    => 'admin@qrmenu.com',
            'password' => Hash::make('password'),
        ]);

        $admin->assignRole('super_admin');
    }
}