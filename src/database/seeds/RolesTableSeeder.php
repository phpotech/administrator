<?php

use Illuminate\Database\Seeder;
use Keyhunter\Administrator\Model\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->delete();
        \DB::table('roles')->delete();

        Role::create(['name' => 'member', 'active' => true, 'rank' => 1]);
        Role::create(['name' => 'admin', 'active' => true, 'rank' => 2]);
    }
}