<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create(['name' => 'admin', 'email' => 'admin@admin.com', 'email_verified_at' => now(),
            'password' => bcrypt('gert657u'), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
    }
}
