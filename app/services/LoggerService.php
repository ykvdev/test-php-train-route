<?php

namespace app\services;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;

class LoggerService
{
    private ConfigService $config;

    public function __construct(ConfigService $config)
    {
        $this->config = $config;
    }

    public function info(string $msg): void
    {
        $msg = date('Y-m-d H:i:s') . ' [INFO] ' . $msg;
        $this->msg($msg);
    }

    public function error(string $msg): void {
        $msg = date('Y-m-d H:i:s') . ' [ERROR] ' . $msg;
        $this->msg($msg);
    }

    public function msg(string $msg): void {
        file_put_contents($this->config->get('services.logger.logs_path'), $msg . PHP_EOL, FILE_APPEND);
    }
}