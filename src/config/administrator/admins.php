<?php

use Carbon\Carbon;
use Illuminate\Auth\Guard;
use Illuminate\Database\Eloquent\Builder;
use Keyhunter\Administrator\Model\Role;
use Keyhunter\Administrator\Schema\Factory AS Schema;
use Keyhunter\Administrator\Schema\SchemaInterface;

return [
    'title'  => 'Admins',
    'model'  => 'App\User',

    /*
    |-------------------------------------------------------
    | Columns/Groups
    |-------------------------------------------------------
    |
    | Describe here full list of columns that should be presented
    | on main listing page
    |
    */
    'columns' => [
        'id',

        'info' => [
            'title'     => 'Info',
            'sort_field'=> 'name',
            'elements'  => [
                'email' => [
                    'output' => '<a href="mailto:(:email)">(:email)</a>',
                ],
                'name'
            ]
        ],

        'active' => [
            'visible' => function() {},
            'output' => function($row) {
                return output_boolean($row);
            }
        ],
        'role_id' => [
            'title' => 'Role',
            'output' => function ($row) {
                return $row->role->name;
            }
        ],
        'dates' => [
            'elements' =>
            [
                'created_at' => ['output' => function($row)
                {
                    return $row->created_at->diffForHumans();
                }],
                'updated_at' => ['output' => function($row)
                {
                    return $row->updated_at->diffForHumans();
                }]
            ]
        ]
    ],

    /*
    |-------------------------------------------------------
    | Actions available to do, including global
    |-------------------------------------------------------
    |
    | Global actions
    |
    */
    'actions' => [],

    /*
    |-------------------------------------------------------
    | Eloquent With Section
    |-------------------------------------------------------
    |
    | Eloquent lazy data loading, just list relations that should be preloaded
    |
    */
    'with' => [],

    /*
    |-------------------------------------------------------
    | QueryBuilder
    |-------------------------------------------------------
    |
    | Extend the main scaffold index query
    |
    */
    'query' => function(Builder $query)
    {
        $query->where('role_id', Role::whereName('admin')->first()->id);
    },

    /*
    |-------------------------------------------------------
    | Global filter
    |-------------------------------------------------------
    */
    'filters' => [
        'email' => [
            'type' => 'text'
        ],

        'active' => [
            'type' => 'select',
            'options' => [
                '' => '-- Any --',
                0 => 'No',
                1 => 'Yes'
            ]
        ],

        'created_at' => [
            'type' => 'date'
        ]
    ],

    /*
    |-------------------------------------------------------
    | Editable area
    |-------------------------------------------------------
    |
    | Describe here all fields that should be editable
    |
    */
    'edit_fields' => [
        'id'       => ['type' => 'key'],

        'email' => [
            'type'  => 'email'
        ],

        'role_id' => [
            'type'    => 'select',
            'options' => function() {
                $options = [];
                Role::whereActive(1)
                    ->get()
                    ->each(function ($role) use (&$options){
                        $options[$role->id] = $role->name;
                    });

                return $options;
            }
        ],

        'name' => [
            'type' => 'text'
        ],

        'active' => [
            'title' => 'Active',
            'type' => 'select',
            'options' => ['Disable', 'Active']
        ]
    ]
];