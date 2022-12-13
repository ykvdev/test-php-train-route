<?php

namespace app\services;

class LoggerService
{
    /** @var ConfigService */
    private ConfigService $config;

    /** @var string */
    private string $logName;

    /**
     * @param ConfigService $config
     * @param string $logName
     */
    public function __construct(ConfigService $config, string $logName)
    {
        $this->config = $config;
        $this->logName = $logName;
    }

    /**
     * @param string $msg
     * @return void
     */
    public function info(string $msg): void
    {
        $msg = date('Y-m-d H:i:s') . ' [INFO] ' . $msg;
        $this->log($msg);
    }

    /**
     * @param string $msg
     * @return void
     */
    public function error(string $msg): void {
        $msg = date('Y-m-d H:i:s') . ' [ERROR] ' . $msg;
        $this->log($msg);
    }

    /**
     * @param string $msg
     * @return void
     */
    public function log(string $msg): void {
        file_put_contents(
            sprintf($this->config->get('services.logger.logs_path'), $this->logName),
            $msg . PHP_EOL,
            FILE_APPEND
        );
    }
}