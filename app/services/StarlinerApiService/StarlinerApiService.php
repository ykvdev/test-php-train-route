<?php

namespace app\services\StarlinerApiService;

use app\api\Starliner\Credentials;
use app\api\Starliner\Operations\OperationInterface;
use app\api\Starliner\Starliner;
use app\services\ConfigService;
use app\services\LoggerService;
use app\services\TimerService;
use DI\Container;

class StarlinerApiService extends Starliner
{    
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
        $this->timer = $timer;

        $config = $config->get('services.starliner_api');
        $this->logger = $di->make(LoggerService::class, ['logName' => $config['log_name']]);

        $soapClient = new \SoapClient($config['wsdl_url'], $config['soap_options']);
        $credentials = new Credentials($config['auth']['login'], $config['auth']['password'],
            $config['auth']['terminal'], $config['auth']['represent_id']);
        parent::__construct($soapClient, $credentials);
    }

    /**
     * @param OperationInterface $operation
     * @return $this
     * @throws ResponseException
     * @throws \JsonException
     */
    public function exec(OperationInterface $operation): self
    {
        $this->timer->start();
        $result = parent::exec($operation);
        $request = $result->getRequest();
        $response = $result->getResponse();
        $this->timer->stop();

        $this->logger->long(
            "SPENT TIME: {$this->timer->getMinutes()} min {$this->timer->getSeconds()} sec",
            'REQUEST HEADERS:' . PHP_EOL . $request->getHeaders(),
            'REQUEST BODY:' . PHP_EOL . $request->getBody(),
            'RESPONSE HEADERS:' . PHP_EOL . $response->getHeaders(),
            'RESPONSE BODY:' . PHP_EOL . $response->getBody(),
            'RESPONSE RESULT:' . PHP_EOL . var_export($response->getResult(), true),
            'RESPONSE ERROR: ' . ($response->getError() ?: '(none)')
        );

        if($error = $response->getError()) {
            throw new ResponseException($error);
        }

        return $result;
    }
}