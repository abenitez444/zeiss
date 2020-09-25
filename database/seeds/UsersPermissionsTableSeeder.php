<?php

use Illuminate\Database\Seeder;
use App\UsersPermissions;

class UsersPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UsersPermissions::create(['user_id' => 1, 'permission_id' => 1]);
        UsersPermissions::create(['user_id' => 1, 'permission_id' => 2]);
        UsersPermissions::create(['user_id' => 1, 'permission_id' => 3]);
        UsersPermissions::create(['user_id' => 1, 'permission_id' => 4]);
    }
}
