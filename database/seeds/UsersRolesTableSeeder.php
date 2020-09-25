<?php

use Illuminate\Database\Seeder;
use App\UsersRoles;

class UsersRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UsersRoles::create(['user_id' => 1, 'role_id' => 1]);
    }
}
