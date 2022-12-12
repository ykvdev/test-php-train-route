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
        /*'guzzle' => [
            'http_errors' => false,
            'user_agent' => 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.132 Safari/537.36',
        ],*/

        'view_renderer' => [
            'views_dir' => ROOT_PATH . '/app/views',
            'views_ext' => 'phtml',
        ],

        'whoops' => [
            'editor' => 'phpstorm',
        ],

        'logger' => [
            'logs_path' => ROOT_PATH . '/data/logs/app.log',
        ],

        'starliner_api' => [

        ],

        'fast_route' => [
            'cache_file' => ROOT_PATH . '/data/fast_route.cache',

            'routes' => [
                ['GET', '/', IndexAction::class],
                ['GET', '/pages/{page}', PagesAction::class],
            ],
        ],
    ],
];