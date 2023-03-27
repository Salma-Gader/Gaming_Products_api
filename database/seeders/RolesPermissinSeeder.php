<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesPermissinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        // Create permissions for Products
        Permission::create(['name' => 'show product']);
        Permission::create(['name' => 'add product']);
        Permission::create(['name' => 'edit every product']);
        Permission::create(['name' => 'edit my product']);
        Permission::create(['name' => 'delete every product']);
        Permission::create(['name' => 'delete my product']);
        // Create permissions for Categories
        Permission::create(['name' => 'show category']);
        Permission::create(['name' => 'add category']);
        Permission::create(['name' => 'edit category']);
        Permission::create(['name' => 'delete category']);
        // Create permissions for Roles
        Permission::create(['name' => 'assign role']);
        // Create permissions for profile
        Permission::create(['name' => 'update profile']);
        
        Role::create(['name' => 'admin'])
            ->givePermissionTo(Permission::all());

        Role::create(['name' => 'seller'])
            ->givePermissionTo(
                'show product',
                'show category',
                'add product',
                'edit my product',
                'delete my product'
            );
        
        Role::create(['name' => 'user'])
            ->givePermissionTo(
                'show product',
                'show category',
                'update profile'
            );
        
        
    }
}
