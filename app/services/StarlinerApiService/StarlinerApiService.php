<?php

namespace app\services\StarlinerApiService;

use app\api\Starliner\Credentials;
use app\services\ConfigService;
use app\services\StarlinerApiService\Operations\TrainRouteOperation;
use DI\Container;

class StarlinerApiService
{
    /** @var Container */
    private readonly Container $di;

    /** @var ConfigService */
    private readonly ConfigService $config;

    /** @var Credentials */
    protected readonly Credentials $credentials;

    /** @var \SoapClient */
    protected readonly \SoapClient $soapClient;

    /**
     * @param Container $di
     * @param ConfigService $config
     * @throws \SoapFault
     */
    public function __construct(Container $di, ConfigService $config)
    {
        $this->di = $di;
        $this->config = $config;

        $apiConfig = $this->config->get('services.starliner_api');
        $this->credentials = new Credentials($apiConfig['auth']['login'], $apiConfig['auth']['password'],
            $apiConfig['auth']['terminal'], $apiConfig['auth']['represent_id']);
        $this->soapClient = new \SoapClient($apiConfig['wsdl_url'], $apiConfig['soap_options']);
    }

    /**
     * Метод отдающий маршрут поезда
     *
     * @return TrainRouteOperation
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function trainRouteOperation(): TrainRouteOperation
    {
        return $this->di->make(TrainRouteOperation::class, [
            'soapClient' => $this->soapClient,
            'credentials' => $this->credentials
        ]);
    }
}