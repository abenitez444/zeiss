<?php

use Illuminate\Database\Seeder;
use App\RolesPermissions;

class RolesPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RolesPermissions::create(['role_id' => 1, 'permission_id' => 1]);
        RolesPermissions::create(['role_id' => 1, 'permission_id' => 2]);
        RolesPermissions::create(['role_id' => 1, 'permission_id' => 3]);
        RolesPermissions::create(['role_id' => 1, 'permission_id' => 4]);
    }
}
