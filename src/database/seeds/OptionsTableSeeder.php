<?php

use Illuminate\Database\Seeder;
use Keyhunter\Administrator\Model\Settings;

class OptionsTableSeeder extends Seeder
{
    /**
     * @var array
     */
    protected $data_seed = [
        'site' => [
            'admin::email'   => 'keyhunter@gmail.com',
            'site::name'     => 'Test Admin Panel',
            'site::about'    => 'About site',
            'site::down'     => '1',
            'support::email' => 'keyhunter@support.com',
        ],
        'test' => [
            'option::test_1' => 'test1',
            'option::test_2' => 'test1'
        ]
        /*
         * 'group' => [
         *     'key_1' => 'value',
         *     'key_2' => 'value'
         * ]
         */
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('options')->delete();

        array_walk($this->data_seed, function ($keys, $group) {
            array_walk($keys, function($value, $key) use ($group) {
                Settings::create([
                    'key'   => $key,
                    'value' => $value,
                    'group' => $group
                ]);
            });
        });
    }
}