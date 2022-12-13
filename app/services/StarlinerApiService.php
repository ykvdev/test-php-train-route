<?php

namespace app\services;

use app\api\Starliner\Client;
use app\api\Starliner\Credentials;
use app\api\Starliner\Operations\AbstractOperation;
use app\api\Starliner\ResponseException;
use DI\Container;

class StarlinerApiService extends Client
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
     * @param Container $di
     * @param ConfigService $config
     * @param TimerService $timer
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \SoapFault
     */
    public function __construct(Container $di, ConfigService $config, TimerService $timer)
    {
        $this->di = $di;
        $this->config = $config;
        $this->timer = $timer;
        $this->logger = $this->di->make(LoggerService::class, ['logName' => $this->config->get('services.starliner_api.log_name')]);

        $apiConfig = $this->config->get('services.starliner_api.auth');
        parent::__construct(new Credentials(
            $apiConfig['wsdl_url'],
            $apiConfig['login'],
            $apiConfig['password'],
            $apiConfig['terminal'],
            $apiConfig['represent_id'],
        ));
    }

    /**
     * @param AbstractOperation $operation
     * @return array
     * @throws ResponseException
     */
    public function execOperation(AbstractOperation $operation): array
    {
        $this->timer->start();
        $results = $operation->exec();
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