<?php

namespace app\services;

class ConfigService
{
    private array $config;

    public function __construct()
    {
        $this->config = require ROOT_PATH . '/app/config.php';
    }

    public function get(string $path): mixed
    {
        $pathParts = explode('.', $path);
        $config = $this->config;
        foreach ($pathParts as $part) {
            $config = $config[$part] ?? null;
            if(!$config) {
                return false;
            }
        }

        return $config;
    }
}