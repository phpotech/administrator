<?php

return [
    'title' => 'Site',

    'model' => 'Keyhunter\Administrator\Model\Settings',

    'rules' => [
        'admin::email'   => 'required|email',
        'support::email' => 'required|email'
    ],

    'edit_fields' => [
        'admin::email' => ['type' => 'email'],

        'support::email' => ['type' => 'email'],

        'site::about' => ['type' => 'textarea'],

//        'site::roles' => [
//            'type'    => 'select',
//            'options' => ['guest', 'member', 'admin', 'content manager']
//        ],

        'site::down' => [
            'type' => 'select',
            'options' => [
                1 => 'enable',
                0 => 'disable'
            ]
        ]
    ]
];