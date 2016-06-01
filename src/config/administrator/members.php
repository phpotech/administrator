<?php

use Carbon\Carbon;
use Illuminate\Auth\Guard;
use Illuminate\Database\Eloquent\Builder;
use Keyhunter\Administrator\Schema\Factory AS Schema;
use Keyhunter\Administrator\Schema\SchemaInterface;

return [
    'title'  => 'Members _notworking',
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
            'elements'  => [
                'username' => ['standalone' => true],
                'email' => [
                    'output' => '<a href="mailto:(:email)">(:email)</a>',
                ],
                'name'
            ]
        ],
        'birth_date' => [
            'title' => 'Age',
            'output' => function($row)
            {
                $date  = Carbon::createFromTimestamp(strtotime($row->birth_date));
                $years = $date->diffInYears();
                return sprintf(trans_choice('%d year|%d years|%d years', $years), $years);
            }
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
    | @todo
    */
    'actions' => [
    ],

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
        $query->whereRole('member');
    },

    /*
    |-------------------------------------------------------
    | Global filter
    |-------------------------------------------------------
    |
    | Filters should be defined here
    |
    */
    'filters' => [
        'username' => [
            'type' => 'text',
            'query' => function($query, $value = '')
            {
                $query->where('users.username', '=', $value);
            }
        ],
        'role' => [
            'type' => 'select',
            //'options' => ['guest' => 'Guest', 'member' => 'Member', 'admin' => 'Admin']
            'options' => function(SchemaInterface $schema)
            {
                return ['' => '-- Any --'] + ($schema->get('role')->getValues());
            },
            'multiple' => false
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

        //'time' => ['type' => 'time'],
        'role' => [
            'type'    => 'select',
            'options' => 'users.role'
        ],

        'email' => [
            'type'  => 'email'
        ],

        'name' => [
            'type' => 'text'
        ],

        'birth_date' => [
            'type' => 'date'
        ],

        'active' => [
            'title' => 'Active',
            'type' => 'bool'
        ]
    ]
];