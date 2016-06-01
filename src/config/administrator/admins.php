<?php

use Carbon\Carbon;
use Illuminate\Auth\Guard;
use Illuminate\Database\Eloquent\Builder;
use Keyhunter\Administrator\Schema\Factory AS Schema;
use Keyhunter\Administrator\Schema\SchemaInterface;

return [
    'title'  => 'Admins _notworking',
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
            'sort_field'=> 'username',
            'elements'  => [
                'username' => ['standalone' => true],
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
        $query->where('role', '=', 'admin');
    },

    /*
    |-------------------------------------------------------
    | Global filter
    |-------------------------------------------------------
    */
    'filters' => [
        'username' => [
            'type' => 'text',
            'query' => function($query, $value = '')
            {
                $query->where('users.username', '=', $value);
            }
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

        'username' => ['type' => 'text'],

        'email' => [
            'type'  => 'email'
        ],

        'name' => [
            'type' => 'text'
        ],

        'active' => [
            'title' => 'Active',
            'type' => 'bool'
        ]
    ]
];