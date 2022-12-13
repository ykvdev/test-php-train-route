<?php

use app\actions\IndexAction;
use app\actions\PagesAction;

return [
    'public_dir_path' => ROOT_PATH . '/public',

    'available_public_file_extensions' => [
        'css' => 'text/css',
        'js' => 'text/javascript',
        'json' => 'application/json',
        'ico' => 'image/x-icon',
    ],

    'services' => [
        'view_renderer' => [
            'views_dir' => ROOT_PATH . '/app/views',
            'views_ext' => 'phtml',
        ],

        'whoops' => [
            'editor' => 'phpstorm',
        ],

        'logger' => [
            'logs_path' => ROOT_PATH . '/data/logs/%s.log',
        ],

        'starliner_api' => [
            'auth' => [
                'wsdl_url' => 'https://test-api.starliner.ru/Api/connect/Soap/Train/1.1.0?wsdl',
                'login' => 'test',
                'password' => 'bYKoDO2it',
                'terminal' => 'htk_test',
                'represent_id' => 22400,
            ],
            'log_name' => 'starliner_api',
        ],

        'fast_route' => [
            'cache_file' => ROOT_PATH . '/data/fast_route.cache',

            'routes' => [
                [['GET', 'POST'], '/', IndexAction::class],
                ['GET', '/pages/{page}', PagesAction::class],
            ],
        ],
    ],
];