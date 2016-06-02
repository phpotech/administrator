<?php

require_once base_path('vendor/keyhunter/administrator/src/helpers.php');
//require_once base_path('packages/keyhunter/administrator/src/helpers.php');

return [
    'title'  => 'Pages 2',

    'description' => 'Silence is gold.',

    'model'  => 'Keyhunter\Administrator\Model\Page',

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

        'info' => column_group('Info', [
            'title',
            'slug'
        ]),

        'body',

        'active' => column_element('Active', false, function($row)
        {
            return output_boolean($row);
        }),

        'dates' => [
            'elements' => [
                'created_at',
                'updated_at',
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

    ],

    /*
    |-------------------------------------------------------
    | Eloquent With Section
    |-------------------------------------------------------
    |
    | Eloquent lazy data loading, just list relations that should be preloaded
    |
    */
    'with' => [

    ],

    /*
    |-------------------------------------------------------
    | QueryBuilder
    |-------------------------------------------------------
    |
    | Extend the main scaffold index query
    |
    */
    'query' => function($query)
    {
        return $query;
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

        'slug' => filter_text(),

        'active' => filter_select('Active', [
            '' => '-- Any --',
            0 => 'No',
            1 => 'Yes'
        ]),

        'created_at' => filter_daterange('Created period')

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

        'id'       => form_key(),

        'slug'     => form_text(),

        'title'    => form_text() + translatable(),

//        'body'    => form_ckeditor() + translatable(),
        'body'    => form_wysi_html5() + translatable(),

        'active' => filter_select('Active', [
            0 => 'No',
            1 => 'Yes'
        ]),

    ]
];