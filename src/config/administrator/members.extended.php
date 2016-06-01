<?php

use Carbon\Carbon;
use Illuminate\Auth\Guard;
use Illuminate\Database\Eloquent\Builder;
use Keyhunter\Administrator\Schema\Factory AS Schema;
use Keyhunter\Administrator\Schema\SchemaInterface;

return [
    'title'  => 'Members',
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
        'image' => [
            'output' => '<img src="(:image)" height="100" />',
            'visible' => false
        ],
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
        'contacts' => [
            'title'      => 'User Contacts',
            'elements'   => ['phone' => [
                'standalone' => true,
                'output' => '<a href="callto:(:phone)">(:phone)</a>'
            ], 'skype']
        ],
        'birth_date' => [
            'title' => 'Age',
            'output' => function($row)
            {
                $date  = Carbon::createFromTimestamp(strtotime($row->birth_date));
                $years = $date->diffInYears();
                return sprintf(trans_choice('%d year|%d years', $years), $years);
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
    |
    */
    'actions' => [
        //'test'  => (new Keyhunter\Administrator\Actions\Action('test', 'Test Me', ''))->setPermission(function() {}),
        'activate' => [
            'title'     => 'Activate',
            'messages'  => [],
            'permission'=> function(Guard $auth, $row = null)
            {
                if (null == $row) {
                    return $auth->check();
                }
                return (! $row->active);
            },
            'callback' => function($row)
            {
                $row->activated = true;
                $row->save();
            },
            'confirmation' => "Activate? Are you sure?"
        ]
    ],

    /*
    |-------------------------------------------------------
    | Eloquent With Section
    |-------------------------------------------------------
    |
    | Eloquent lazy data loading, just list relations that should be preloaded
    |
    */
    'with' => ['contacts'],

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
        $query->addSelect('c.phone', 'c.skype', 'i.image');
        $query->leftJoin('galleries AS g', 'g.user_id', '=', 'users.id');
        $query->leftJoin('user_details AS c', 'c.user_id', '=', 'users.id');
        $query->leftJoin('images AS i', 'i.user_id', '=', 'users.id');

        $query->whereNotIn('users.role', ['admin']);
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

        'phone' => [
            'type' => 'select',
            'label' => 'Has Phone',
            'options' => [
                '' => '-- Any --',
                1 => 'Has phone',
                0 => 'No phone'
            ],
            'query' => function($query, $value = '')
            {
                switch ((int) $value) {
                    case 0:
                        $query->whereRaw("c.phone IS NOT NULL");

                        break;

                    case 1:
                        $query->whereRaw("c.phone IS NULL");
                        break;

                    default;
                        break;
                }
            }
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
        'image'    => [
            'type' => 'image',
            // not the recommended way to hard-code this values here,
            // the better way is to reference from global configurable source, like config, repository, etc...
            'location' => '/storage/members/[id]',
            'sizes'    => [
                'original'  => '800x800',
                'image'     => '400x400',
                'side'      => '200x200',
                'small'     => '100x100'
            ],
            /*'alias' => [
                'image' => 'original',
                'small' => 'side',
                'side'  => 'small'
            ],*/
            // when images are stored into the images table and btw User and Image models is defined relation User::images()
            'relation' => 'images.image'
        ],

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

        'phone' => [
            'type'     => 'tel',
            'relation' => 'contacts.phone'
        ],

        'age' => [
            'type' => 'number',
            'min' => 0,
            'max' => 20,
            'step' => 10
        ],

        'about' => [
            'type'      => 'textarea',
            'relation'  => 'about.about',
            'mui'       => true
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