<?php

namespace app\services;

class ConfigService
{
    /** @var array */
    private array $config;

    public function __construct()
    {
        $this->config = require ROOT_PATH . '/app/config.php';
    }

    /**
     * @param string|null $path
     * @return mixed
     */
    public function get(?string $path = null): mixed
    {
        return $path ? getArrayItemByPath($this->config, $path) : $this->config;
    }
}