<?php

namespace app\services\StarlinerApiService;

use app\api\Starliner\Credentials;
use app\api\Starliner\ResponseException;
use app\services\ConfigService;
use app\services\LoggerService;
use app\services\TimerService;
use DI\Container;

trait ExecutorTrait
{
    /** @var Container */
    private readonly Container $di;

    /** @var ConfigService */
    private readonly ConfigService $config;

    /** @var TimerService */
    private readonly TimerService $timer;

    /** @var LoggerService */
    private readonly LoggerService $logger;

    /**
     * @param \SoapClient $soapClient
     * @param Credentials $credentials
     * @param Container $di
     * @param ConfigService $config
     * @param TimerService $timer
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function __construct(\SoapClient $soapClient, Credentials $credentials, Container $di, ConfigService $config, TimerService $timer)
    {
        $this->di = $di;
        $this->config = $config;
        $this->timer = $timer;
        $this->logger = $this->di->make(LoggerService::class, ['logName' => $this->config->get('services.starliner_api.log_name')]);

        parent::__construct($soapClient, $credentials);
    }

    /**
     * @return array
     * @throws ResponseException
     */
    public function exec(): array
    {
        $this->timer->start();
        $results = parent::exec();
        $this->timer->stop();

        $this->logger->long(
            "SPENT TIME: {$this->timer->getMinutes()} min {$this->timer->getSeconds()} sec",
            'REQUEST HEADERS:' . PHP_EOL . $results->getRequest()->getHeaders(),
            'REQUEST BODY:' . PHP_EOL . $results->getRequest()->getBody(),
            'RESPONSE HEADERS:' . PHP_EOL . $results->getResponse()->getHeaders(),
            'RESPONSE BODY:' . PHP_EOL . $results->getResponse()->getBody(),
            'RESPONSE ERROR: ' . ($results->getResponse()->getError() ?: '(none)')
        );

        if($error = $results->getResponse()->getError()) {
            throw new ResponseException($error);
        }

        return $results->getResponse()->getBody(true);
    }
}