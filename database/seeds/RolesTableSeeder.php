<?php

use Illuminate\Database\Seeder;
use App\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'Admin', 'slug' => 'admin']);
        Role::create(['name' => 'Proveedor', 'slug' => 'proveedor']);
        Role::create(['name' => 'Cliente', 'slug' => 'cliente']);
        Role::create(['name' => 'Manager', 'slug' => 'manager']);
    }
}
