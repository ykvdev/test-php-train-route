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
     * @param string $path
     * @return mixed
     */
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