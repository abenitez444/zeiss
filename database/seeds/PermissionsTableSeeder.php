<?php

use Illuminate\Database\Seeder;
use App\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'view', 'slug' => 'view']);
        Permission::create(['name' => 'create', 'slug' => 'create']);
        Permission::create(['name' => 'edit', 'slug' => 'edit']);
        Permission::create(['name' => 'delete', 'slug' => 'delete']);
    }
}
