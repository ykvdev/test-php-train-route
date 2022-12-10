<?php

namespace app\services;

use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class WhoopsService
{
    private ConfigService $config;

    public function __construct(ConfigService $config)
    {
        $this->config = $config;

        $handler = new PrettyPageHandler;
        $handler->setEditor($this->config->get('services.whoops.editor'));
        (new Run())->prependHandler($handler)->register();
    }
}