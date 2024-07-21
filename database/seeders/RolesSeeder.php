<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $permissions = Permission::where('guard_name', 'web')->pluck('id')->toArray();
        $adminRole->syncPermissions($permissions);

        $freeRole = Role::firstOrCreate(['name' => 'free']);
        $freePermissions = Permission::where('guard_name', 'web')->pluck('id')->toArray();
        $freeRole->syncPermissions($freePermissions);

        $buyerRole = Role::firstOrCreate(['name' => 'buyer']);
        $buyerPermissions = Permission::where('guard_name', 'web')->pluck('id')->toArray();
        $buyerRole->syncPermissions($buyerPermissions);


        $sellerRole = Role::firstOrCreate(['name' => 'seller']);
        $sellerPermissions = Permission::where('guard_name', 'web')->pluck('id')->toArray();
        $sellerRole->syncPermissions($sellerPermissions);


        $tenantRole = Role::firstOrCreate(['name' => 'tenant']);
        $tenantPermissions = Permission::where('guard_name', 'web')->pluck('id')->toArray();
        $tenantRole->syncPermissions($tenantPermissions);


        $landlordRole = Role::firstOrCreate(['name' => 'landlord']);
        $landlordPermissions = Permission::where('guard_name', 'web')->pluck('id')->toArray();
        $landlordRole->syncPermissions($landlordPermissions);

        $contractorRole = Role::firstOrCreate(['name' => 'contractor']);
        $contractorPermissions = Permission::where('guard_name', 'web')->pluck('id')->toArray();
        $contractorRole->syncPermissions($contractorPermissions);

    }
}
