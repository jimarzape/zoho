<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // Create permissions
        Permission::create(['name' => 'view projects']);
        Permission::create(['name' => 'approve hours']);
        Permission::create(['name' => 'edit user']);

        // Create roles and assign existing permissions
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        $projectManager = Role::create(['name' => 'project manager']);
        $projectManager->givePermissionTo(['view projects', 'approve hours']);

        $customer = Role::create(['name' => 'customer']);
        $customer->givePermissionTo('view projects');
    }
}
