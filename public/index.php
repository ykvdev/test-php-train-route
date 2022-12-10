<?php

use app\Bootstrap;

require __DIR__ . '/../vendor/autoload.php';

(new Bootstrap($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']))->run();