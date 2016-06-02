<?php

use Illuminate\Database\Seeder;
use Keyhunter\Administrator\Model\Role;
use Keyhunter\Administrator\Model\User;
use Faker\Factory;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->delete();

        $roles = Role::whereActive(1)->get();
        $fake = Factory::create();

        $roles->each(function ($role, $key) use ($fake) {
            for ($i = 0; $i < 3; $i++) {
                User::create([
                    'name' => $fake->name,
                    'email' => $fake->email,
                    'role_id' => $role->id,
                    'password' => \Hash::make($fake->word . $fake->phoneNumber)
                ]);
            }
        });

        User::create([
            'name' => 'Keyhunter',
            'email' => 'keyhunter@gmail.com',
            'role_id' => Role::whereName('admin')->first()->id,
            'password' => Hash::make('admin123')
        ]);
    }
}