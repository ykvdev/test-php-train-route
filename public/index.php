<?php

use app\Bootstrap;

define('ROOT_PATH', realpath(__DIR__ . '/..'));

require ROOT_PATH . '/vendor/autoload.php';

(new Bootstrap)->run();