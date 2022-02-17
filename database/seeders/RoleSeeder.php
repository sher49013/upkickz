<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::updateOrCreate(['name' => 'super_admin'], [
            'name' => 'super_admin',
            'slug' => 'Super Admin',
            'guard_name' => 'web',
        ]);

        Role::updateOrCreate(['name' => 'app_user'], [
            'name' => 'app_user',
            'slug' => 'App User',
            'guard_name' => 'web',
        ]);
    }
}
