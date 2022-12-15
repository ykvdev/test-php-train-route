<?php

namespace app\services;

use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class WhoopsService
{
    /**
     * @param ConfigService $config
     */
    public function __construct(ConfigService $config)
    {
        $handler = (new PrettyPageHandler)->setEditor($config->get('services.whoops.editor'));
        (new Run)->prependHandler($handler)->register();
    }
}