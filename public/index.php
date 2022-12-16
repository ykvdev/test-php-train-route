<?php

use app\Bootstrap;

require __DIR__ . '/../app/init.php';

$bootstrap = new Bootstrap($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
if($fileMimeType = $bootstrap->getMimeTypeIfRequestedPublicFile()) {
    header('Content-Type: ' . $fileMimeType);
    echo file_get_contents(__DIR__ . $_SERVER['REQUEST_URI']);
} else {
    $bootstrap->runAction();
}