<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seeds.
     *
     * @var array
     */
    protected $seeds = [
        LanguagesTableSeeder::class,
        OptionsTableSeeder::class,
        RolesTableSeeder::class,
        UsersTableSeeder::class,
        PagesTableSeeder::class
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        array_walk($this->seeds, function ($seed) {
            $this->call($seed);
        });
    }
}
