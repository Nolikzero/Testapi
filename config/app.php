<?php
return [
    'db' =>
        [
            'database_type' => 'mysql',
            'database_name' => 'testapi',
            'server' => 'localhost',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
    'api' =>
        [
            'route' =>
                [
                    'user' => 'api\ApiUser',
                    'recipe' => 'api\ApiRecipe',
                ]
        ]
];
